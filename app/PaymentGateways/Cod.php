<?php

namespace App\PaymentGateways;

use App\Constants\Status;
use App\Models\Deposit;
use App\Models\Gateway;
use Illuminate\Contracts\Support\Responsable;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalHttp\HttpException;

class Cod implements PaymentGateway
{
    protected $client;
    protected $paymentGateway;
    public function __construct()
    {
        $this->paymentGateway = Gateway::where('name', 'Cod')->firstOrFail();
    }
    protected function client()
    {
        if (!$this->client) {
            $this->client = new PayPalHttpClient(new SandboxEnvironment(
                $this->paymentGateway->gateway_parameters['client_id'],
                $this->paymentGateway->gateway_parameters['client_secret']));
        }

        return $this->client;

    }

    public function create($deposit)
    {

        try {
            $data = [
                'user_id' => $deposit['user_id'],
                'method_code' => $this->paymentGateway->id,
                'method_currency' => $deposit->method_currency,
                'amount' => $deposit->amount,
                'final_amount' => $deposit->final_amount,
                'charge' => $deposit->charge,
                'status' => Status::PAYMENT_PENDING,
            ];

            $deposit = Deposit::create($data);

            return redirect()->route('home')->with([
                'message' => 'Payment initiated successfully. Please complete the payment at your convenience.',
                'deposit' => $deposit
            ]);

        } catch (HttpException $ex) {
            \Log::error("PayPal payment creation failed", [
                'status' => $ex->statusCode,
                'message' => $ex->getMessage()
            ]);

            return redirect()->route('user.deposit.failed')->with('error', 'Unable to initiate PayPal payment.');
        }
    }

    public function verify($id) : Deposit
    {
        $deposit = Deposit::where('trx', $id)->firstOrFail()->where('payment_gateway_id', $this->paymentGateway->id);

        if ($deposit->status == Status::PAYMENT_SUCCESS) {
            return $deposit;
        }

        $request = new OrdersCaptureRequest($id);
        try {
            $response = $this->client()->execute($request);

            if ($response->result->status === 'COMPLETED') {
                $deposit->status = Status::PAYMENT_SUCCESS;
                $deposit->detail = (array) $response->result;
                $deposit->save();
            } else {
                throw new \Exception('Payment not completed.');
            }

        } catch (HttpException $ex) {
            \Log::error("PayPal payment verification failed", [
                'status' => $ex->statusCode,
                'message' => $ex->getMessage()
            ]);
            throw new \Exception('PayPal payment verification failed.');
        }

        return $deposit;
    }
}
