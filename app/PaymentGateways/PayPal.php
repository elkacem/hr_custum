<?php

namespace App\PaymentGateways;

use App\Constants\Status;
use App\Lib\FileManager;
use App\Models\Deposit;
use App\Models\Gateway;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalHttp\HttpException;

class PayPal implements PaymentGateway
{
    protected $client;
    protected $paymentGateway;
    public function __construct()
    {
        $this->paymentGateway = Gateway::where('name', 'PayPal')->firstOrFail();
    }
    protected function client()
    {
        $params = json_decode($this->paymentGateway->gateway_parameters, true);
        $clientId = $params['client_id']['value'] ?? $params['paypal_email']['value'] ?? null;
        $clientSecret = $params['client_secret']['value'] ?? $params['secret_id']['value'] ?? null;

        // Optional fallback
        if (!$clientId || !$clientSecret) {
            throw new \Exception('PayPal credentials are missing.');
        }


//        dd($this->paymentGateway->gateway_parameters['paypal_email']['value']);
        if (!$this->client) {
//            $env = (config('services.paypal.mode') === 'live')
//                ? new ProductionEnvironment($clientId, $clientSecret)
//                : new SandboxEnvironment($clientId, $clientSecret);

            $mode = $this->paymentGateway->mode ?? 'sandbox'; // fallback to sandbox
            $env = ($mode === 'live')
                ? new ProductionEnvironment($clientId, $clientSecret)
                : new SandboxEnvironment($clientId, $clientSecret);

            $this->client = new PayPalHttpClient($env);
        }

        return $this->client;



    }

    public function create($deposit)
    {

//        dd($deposit);

//        $deposit = $data['deposit'];
//
//        dd($data);
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => $deposit->trx,
                "amount" => [
                    "value" => number_format($deposit->final_amount, 2, '.', ''),
                    "currency_code" => $deposit->method_currency,
                ],
                "description" => "Payment to " . gs()->site_name,
                "custom_id" => $deposit->trx,
            ]],
            "application_context" => [
                "cancel_url" => route('payment.cancel', ['PayPal', 'trx' => $deposit->trx]),
//                "cancel_url" => route('payment.cancel', 'paypal') . '?trx=' . $deposit->trx,
                "return_url" => route('payment.return', 'PayPal'),
            ]
        ];

        // ✅ Log the URLs you're sending to PayPal
        \Log::info('Sending PayPal order', [
//            'cancel_url' => route('payment.cancel', ['paypal', 'trx' => $deposit->trx]),
            'cancel_url' => route('payment.cancel', ['alias' => 'paypal']) . '?trx=' . $deposit->trx,
            'return_url' => route('payment.return', 'paypal'),
            "landing_page" => "LOGIN", // Add this

        ]);

        try {

            $response = $this->client()->execute($request);

// Update original deposit with the real PayPal order ID
            $deposit->paypal_order_id = $response->result->id;
            $deposit->detail = (array) $response->result;
            $deposit->status = Status::PAYMENT_PENDING;
            $deposit->save();

//            dd($deposit);

            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') {
                    return redirect()->away($link->href);
                }
            }

            throw new \Exception('No approval link found in PayPal response.');

        } catch (HttpException $ex) {
            \Log::error("PayPal payment creation failed", [
                'status' => $ex->statusCode,
                'message' => $ex->getMessage()
            ]);

            return redirect()->route('user.deposit.failed')->with('error', 'Unable to initiate PayPal payment.');
        }
    }

    public function verify($id): Deposit
    {
        \Log::info("[PayPal] Verifying PayPal order", ['order_id' => $id]);

        // Step 1: Try finding the Deposit by PayPal Order ID
        $deposit = Deposit::where('paypal_order_id', $id)
            ->where('method_code', $this->paymentGateway->code)
            ->first();

        if (!$deposit) {
            \Log::error("[PayPal] Deposit not found for order ID", [
                'order_id' => $id,
                'method_code' => $this->paymentGateway->id,
            ]);
            abort(404, 'Deposit not found.');
        }

        \Log::info("[PayPal] Deposit found", [
            'id' => $deposit->id,
            'trx' => $deposit->trx,
            'paypal_order_id' => $deposit->paypal_order_id,
            'status' => $deposit->status,
        ]);

        // Step 2: Return early if already paid
        if ($deposit->status == Status::PAYMENT_SUCCESS) {
            \Log::info("[PayPal] Deposit already marked as successful", ['trx' => $deposit->trx]);
            return $deposit;
        }

        // Step 3: Attempt capture
        $request = new OrdersCaptureRequest($id);
        \Log::info("[PayPal] Sending capture request to PayPal", ['paypal_order_id' => $id]);

        try {
            $response = $this->client()->execute($request);

            \Log::info("[PayPal] Capture response received", [
                'status' => $response->result->status,
                'payer_email' => $response->result->payer->email_address ?? null,
                'amount' => $response->result->purchase_units[0]->amount->value ?? null,
            ]);

            if ($response->result->status === 'COMPLETED') {
                $deposit->status = Status::PAYMENT_SUCCESS;
                $deposit->detail = (array) $response->result;
                $deposit->save();

                \Log::info("[PayPal] Deposit marked as successful", ['trx' => $deposit->trx]);
            } else {
                // Mark as failed
                $deposit->status = Status::PAYMENT_REJECT;
                $deposit->detail = (array) $response->result;
                $deposit->save();

                \Log::warning("[PayPal] Payment rejected", [
                    'paypal_order_id' => $id,
                    'status' => $response->result->status
                ]);

                throw new \Exception('Payment not completed. Status: ' . $response->result->status);
            }

        }
//        catch (HttpException $ex) {
//            \Log::error("[PayPal] Capture request failed", [
//                'status_code' => $ex->statusCode,
//                'message' => $ex->getMessage(),
//                'paypal_order_id' => $id,
//            ]);
//            throw new \Exception('PayPal payment verification failed: ' . $ex->getMessage());
//        }
        catch (HttpException $ex) {
            \Log::error("[PayPal] Capture request failed", [
                'status_code' => $ex->statusCode,
                'message' => $ex->getMessage(),
                'paypal_order_id' => $id,
                'body' => $ex->getMessage(), // Add this
                'debug_id' => $ex->headers['paypal-debug-id'] ?? null, // Add this
            ]);
            throw new \Exception('PayPal payment verification failed: ' . $ex->getMessage());
        }

        return $deposit;
    }


}
