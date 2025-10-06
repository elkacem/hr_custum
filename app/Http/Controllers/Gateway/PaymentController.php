<?php

namespace App\Http\Controllers\Gateway;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\PlanLog;
use App\Models\RentLog;
use App\Models\User;
use App\Notify\Email;
use App\PaymentGateways\PaymentGatwayFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function deposit() {
        if (!session()->has('rent_id') && !session()->has('plan_id')) {
            $notify[] = ['error', 'Invalid request!'];
            return back()->withNotify($notify);
        }
        $rentId = session('rent_id');
        $planId = session('plan_id');

        $insurancePrice = 0;
        $fuelServiceFee = 3000;
        $amount_ = 0;
        $oneDayPrice = 0;
        $totalDays = 1; // default fallback
        if ($rentId) {
            $rentLog = RentLog::where('id', $rentId)->first();
            $amount_  = getAmount($rentLog->price);

            // Get 1-day price from related dossier
            $oneDayPrice = $rentLog->vehicle->price ?? 0;

            // Calculate total days
            $pick = \Carbon\Carbon::parse($rentLog->pick_time);
            $drop = \Carbon\Carbon::parse($rentLog->drop_time);
            $totalDays = max(1, ceil($pick->diffInDays($drop, false)));

            // Determine insurance price based on rentLog.insurance
            if ($rentLog->insurance !== 0) {
                $insurancePrice = $rentLog->insurance_price ?? 12000; // Default insurance price if not set
            }
        } else {
            $planLog = PlanLog::where('id', $planId)->first();
            $amount_  = getAmount($planLog->price);
        }

        $amount = $amount_ + $insurancePrice + $fuelServiceFee;

        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('name')->get();

        $pageTitle = 'Payment Methods';
        return view('template.user.payment.deposit', compact('gatewayCurrency', 'pageTitle', 'amount', 'oneDayPrice', 'insurancePrice', 'fuelServiceFee', 'totalDays'));
    }

//    public function depositInsert(Request $request) {
////        dd($request->all());
//        $request->validate([
//            'amount'   => 'required|numeric|gt:0',
//            'gateway'  => 'required',
//            'currency' => 'required',
//        ]);
//
//        if (!session()->has('rent_id') && !session()->has('plan_id')) {
//            $notify[] = ['error', 'Invalid request!'];
//            return back()->withNotify($notify);
//        }
//
//        $user = auth()->user();
//        $gate = GatewayCurrency::whereHas('method', function ($gate) {
//            $gate->where('status', Status::ENABLE);
//        })->where('method_code', $request->gateway)->where('currency', $request->currency)->first();
//        if (!$gate) {
//            $notify[] = ['error', 'Invalid gateway'];
//            return back()->withNotify($notify);
//        }
//
//        $insurancePrice = 0;
//        $fuelServiceFee = 3000;
//        $amount_ = 0;
//        $finalAmount = 0 ;
//        $vehicleOneDayPrice = 0;
//        $partialUsed = false;
//        if (session('plan_id')) {
//            $planLog = PlanLog::where('id', session('plan_id'))->first();
//            $amount_  = getAmount($planLog->price);
//        }
//
//        if (session('rent_id')) {
//            $rentLog = RentLog::where('id', session('rent_id'))->first();
//            $amount_  = getAmount($rentLog->price);
//
//            if ($rentLog->insurance !== 0) {
//                $insurancePrice = 12000;
//            }
//
//            $vehicleOneDayPrice = getAmount(optional($rentLog->dossier)->price);
//        }
//
//        if (!$amount_) {
//            $notify[] = ['error', 'Invalid request!'];
//            return back()->withNotify($notify);
//        }
//
//        $amount = $amount_ + $insurancePrice + $fuelServiceFee;
//
//        // Apply discount or partial pay logic
//        $originalAmount = $amount;
//        $paymentNote = null;
//
//        // NEW VARIABLE
//        $discount = 0;
//        $partialRate = 0;
//        $restAmount = 0;
//
//        if ($gate->full_payment_discount > 0) {
//            $discount = $gate->full_payment_discount;
//            $finalAmount = $originalAmount - ($originalAmount * $discount / 100);
//            $paymentNote = "A discount of {$discount}% has been applied.";
//        } elseif ($gate->allow_one_day_pay == 1 && $vehicleOneDayPrice > 0) {
//            $partialUsed = true;
//            $partialRate = $gate->allow_one_day_pay;
//            $finalAmount = $originalAmount * $partialRate / 100;
//            // Calculate rest to be paid later
//            $restAmount = $originalAmount - $finalAmount;
//            $paymentNote = "Only {$partialRate}% of the amount is charged now, rest is due at counter.";
//        }
//
//        if ($gate->min_amount > $amount || $gate->max_amount < $amount) {
//            $notify[] = ['error', 'Please follow deposit limit'];
//            return back()->withNotify($notify);
//        }
//
//        $charge      = $gate->fixed_charge + ($finalAmount * $gate->percent_charge / 100);
//        $payable     = $finalAmount + $charge;
//        $finalAmount = $payable * $gate->rate;
//
//        $data                  = new Deposit();
//        $data->user_id         = $user->id;
//        $data->rent_log_id     = session('rent_id');
//        $data->plan_log_id     = session('plan_id') ?? 0;
//        $data->method_code     = $gate->method_code;
//        $data->method_currency = strtoupper($gate->currency);
//        $data->amount          = $amount;
//        $data->charge          = $charge;
//        $data->rate            = $gate->rate;
//        $data->final_amount    = $finalAmount;
//        $data->rest_amount = $restAmount;
//        // Save the active payment rule only
//        $data->full_payment_discount = $discount > 0 ? $discount : null;
//        $data->allow_one_day_pay     = $partialRate > 0 ? $partialRate : null;
//
//        $data->btc_amount      = 0;
//        $data->btc_wallet      = "";
//        $data->trx             = getTrx();
//        $data->success_url     = urlPath('user.deposit.history');
//        $data->failed_url      = urlPath('user.deposit.history');
//        $data->save();
//
//
//        session()->forget('plan_id');
//        session()->forget('rent_id');
//
//        session()->put('Track', $data->trx);
//        return to_route('user.deposit.confirm');
//    }

    public function depositInsert(Request $request)
    {
        $request->validate([
            'amount'   => 'required|numeric|gt:0',
            'gateway_currency_id' => 'required|exists:gateway_currencies,id',
            'currency' => 'required',
        ]);

        if (!session()->has('rent_id') && !session()->has('plan_id')) {
            $notify[] = ['error', 'Invalid request!'];
            return back()->withNotify($notify);
        }

        $user = auth()->user();

        $gatewayCurrencyId = $request->gateway_currency_id;
        $gate = GatewayCurrency::with('method')
            ->where('id', $gatewayCurrencyId)
            ->whereHas('method', function ($q) {
                $q->where('status', Status::ENABLE);
            })
            ->firstOrFail();

        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }

        // Setup base amounts
        $fuelServiceFee = 3000;
        $amount_ = 0;
        $finalAmount = 0;
        $insurancePrice = 0;
        $vehicleOneDayPrice = 0;
        $restAmount = 0;
        $discount = 0;
        $partialUsed = false;

        // Load rent or plan
        if (session('rent_id')) {
            $rentLog = RentLog::with('dossier')->find(session('rent_id'));
            if (!$rentLog) {
                $notify[] = ['error', 'Invalid Rent!'];
                return back()->withNotify($notify);
            }

            $insurancePrice = $rentLog->insurance_price ?? 0;

            $amount_ = getAmount($rentLog->price) + $insurancePrice + $fuelServiceFee;

            $vehicleOneDayPrice = getAmount(optional($rentLog->vehicle)->price);
        } elseif (session('plan_id')) {
            $planLog = PlanLog::find(session('plan_id'));
            if (!$planLog) {
                $notify[] = ['error', 'Invalid Plan!'];
                return back()->withNotify($notify);
            }

            $amount_ = getAmount($planLog->price);
            $vehicleOneDayPrice = $amount_;
        }

        if (!$amount_) {
            $notify[] = ['error', 'Invalid request!'];
            return back()->withNotify($notify);
        }


        if ($gate->full_payment_discount > 0) {

            $discount = $gate->full_payment_discount;
            $finalAmount = $amount_ - ($amount_ * $discount / 100);
            $paymentNote = "A discount of {$discount}% has been applied.";
        } elseif ($gate->allow_one_day_pay == 1 && $vehicleOneDayPrice > 0) {
            $partialUsed = true;
            $finalAmount = $vehicleOneDayPrice + $fuelServiceFee;
            $restAmount = $amount_ - $finalAmount;
            $paymentNote = "Only 1-day price charged. Rest is due at pickup.";
        } else {
            $finalAmount = $amount_;
        }

        // Check deposit limits
        if ($gate->min_amount > $finalAmount || $gate->max_amount < $finalAmount) {
            $notify[] = ['error', 'Please follow deposit limit'];
            return back()->withNotify($notify);
        }

        // Gateway charge
        $charge = $gate->fixed_charge + ($finalAmount * $gate->percent_charge / 100);
        $payable = $finalAmount + $charge;
        $finalAmountConverted = $payable * $gate->rate;

        // Create deposit
        $data = new Deposit();
        $data->user_id = $user->id;
        $data->rent_log_id = session('rent_id');
        $data->plan_log_id = session('plan_id') ?? 0;
        $data->method_code = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount = $amount_; // original amount before any partial/discount
        $data->charge = $charge;
        $data->rate = $gate->rate;
        $data->final_amount = $finalAmountConverted;
        $data->rest_amount = $restAmount;

        $data->full_payment_discount = $discount > 0 ? $discount : null;
        $data->allow_one_day_pay = $partialUsed ? 1 : null;

        $data->btc_amount = 0;
        $data->btc_wallet = '';
        $data->trx = getTrx();
        $data->success_url = urlPath('user.deposit.history');
        $data->failed_url = urlPath('user.deposit.history');
        $data->save();

        // Clear session
        session()->forget('plan_id');
        session()->forget('rent_id');
        session()->put('Track', $data->trx);

        return to_route('user.deposit.confirm');
    }


    public function depositConfirm() {
        $track   = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

//        dd($deposit);
        if ($deposit->method_code >= 1000) {
            return to_route('user.deposit.manual.confirm');
        }

        $new = PaymentGatwayFactory::create($deposit->gateway->alias);

        $response = $new->create($deposit);

        // If create() returns a redirect response, return it immediately:
        if ($response instanceof \Illuminate\Http\RedirectResponse) {
            return $response;
        }

        // If create() returns something else (like array or JSON string), handle accordingly
        // For example, if $response is JSON string:
        $data = json_decode($response);

        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return back()->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        $pageTitle = 'Payment Confirm';
        return view($data->view, compact('data', 'pageTitle', 'deposit'));
    }

    public static function userDataUpdate($deposit, $isManual = null) {
        if ($deposit->status == Status::PAYMENT_INITIATE || $deposit->status == Status::PAYMENT_PENDING) {
            $deposit->status = Status::PAYMENT_SUCCESS;
            $deposit->save();

            $user = User::find($deposit->user_id);

            if ($deposit->rent_log_id) {
                $rentLog         = RentLog::where('id', $deposit->rent_log_id)->first();
                $amount          = $rentLog->price;
                $rentLog->status = Status::YES;
                $rentLog->trx    = $deposit->trx;
                $rentLog->save();
            } else {
                $planLog         = PlanLog::where('id', $deposit->plan_log_id)->first();
                $amount          = $planLog->price;
                $planLog->status = Status::YES;
                $planLog->trx    = $deposit->trx;
                $planLog->save();
            }

            $user->balance += $amount;
            $user->save();

//            $methodName = $deposit->methodName();

//            if (!$isManual) {
//                $adminNotification            = new AdminNotification();
//                $adminNotification->user_id   = $user->id;
//                $adminNotification->title     = 'Deposit successful via ' . $methodName;
//                $adminNotification->click_url = urlPath('admin.deposit.successful');
//                $adminNotification->save();
//            }

            //Forget session
            session()->forget(['rent_id', 'plan_id']);
        }
    }

    public function callback(Request $request, $alias)
    {
        \Log::info("PayPal callback received", ['request' => $request->all()]);

        $orderId = $request->get('token');
        $payerId = $request->get('PayerID'); // Null if user cancelled

        if (!$orderId) {
            return redirect()->route('user.deposit.failed')->with('error', 'Missing PayPal order ID.');
        }

        // Handle Cancelled Payment (no PayerID = user clicked cancel)
        if (!$payerId) {
            $deposit = Deposit::where('paypal_order_id', $orderId)->first();
            if ($deposit && $deposit->status === Status::PAYMENT_INITIATE) {
                $deposit->status = Status::PAYMENT_REJECT;
                $deposit->save();
            }

            return redirect()->route('user.deposit.history')->with('error', 'You cancelled the PayPal payment.');
        }

        // Proceed with normal capture
        try {
            $gateway = PaymentGatwayFactory::create($alias);
            $deposit = $gateway->verify($orderId);

            $details = is_string($deposit->detail)
                ? json_decode($deposit->detail, true)
                : json_decode(json_encode($deposit->detail), true); // convert stdClass to array

            // Generate PDF invoice as before...
            if ($deposit->status == Status::PAYMENT_SUCCESS || !$deposit->invoice || !Storage::disk('private')->exists($deposit->invoice)) {
                $filename = 'invoice_' . $deposit->trx . '.pdf';
                $relativePath = 'invoices/' . $filename;

//                dd($deposit);

                $pdf = Pdf::loadView('invoice.facture', [
                    'deposit' => $deposit,
                    'user' => $deposit->user,
                    'description' => 'Payment to ' . gs()->site_name,
                ]);

                $pdfContent = $pdf->output();

                Storage::disk('private')->put($relativePath, $pdfContent);
                $deposit->invoice = $relativePath;
                $deposit->save();

                $user =  $deposit->user;

//                dd($user, showAmount($deposit->amount), optional($deposit->gateway)->name, $deposit->trx, now()->format('d/m/Y H:i'), url('/user/invoice/' . $deposit->trx . '/download') );

                \Log::info('Sending invoice email to user', ['email' => $user->email]);

                $methodName = $deposit->methodName();

                notify($user, 'DEPOSIT_COMPLETE', [
                    'method_name'     => $methodName,
                    'method_currency' => $deposit->method_currency,
                    'method_amount'   => showAmount($deposit->final_amount, currencyFormat: false),
                    'amount'          => showAmount($deposit->amount, currencyFormat: false),
                    'charge'          => showAmount($deposit->charge, currencyFormat: false),
                    'rate'            => showAmount($deposit->rate, currencyFormat: false),
                    'trx'             => $deposit->trx,
                    'post_balance'    => showAmount($user->balance),
                ], ['email'], true, null, [ // ✅ attachments array
                    [
                        'data' => $pdfContent,
                        'name' => $filename,
                        'mime' => 'application/pdf',
                    ]
                ]);



                \Log::info('Notification sent successfully.');

            }

            return redirect()->route('user.deposit.history')->with('success', 'Deposit successful');

        } catch (\Exception $ex) {
            \Log::error("PayPal callback failed", ['error' => $ex->getMessage()]);
            return redirect()->route('user.deposit.failed')->with('error', 'Payment verification failed.');
        }
    }



    public function cancel(Request $request) {
        $track = $request->get('trx');
        if (!$track) {
            $notify[] = ['error', 'Invalid request!'];
            return back()->withNotify($notify);
        }

        $deposit = Deposit::where('trx', $track)->where('status', Status::PAYMENT_PENDING)->first();
        if (!$deposit) {
            $notify[] = ['error', 'Invalid request!'];
            return back()->withNotify($notify);
        }

        $deposit->status = Status::PAYMENT_REJECT;
        $deposit->save();

        return to_route('user.deposit.history')->with('error', 'Deposit cancelled');
    }

    public function manualDepositConfirm() {
        $track = session()->get('Track');
        $data  = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        abort_if(!$data, 404);
        if ($data->method_code > 999) {
            $pageTitle = 'Confirm Deposit';
            $method    = $data->gatewayCurrency();
            $gateway   = $method->method;
            return view('template.user.payment.manual', compact('data', 'pageTitle', 'method', 'gateway'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request) {
        $track = session()->get('Track');
        $data  = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        abort_if(!$data, 404);
        $gatewayCurrency = $data->gatewayCurrency();
        $gateway         = $gatewayCurrency->method;
        $formData        = $gateway->form->form_data;

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);

        $data->detail = $userData;
        $data->status = Status::PAYMENT_PENDING;
        $data->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $data->user->id;
        $adminNotification->title     = 'Deposit request from ' . $data->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        notify($data->user, 'DEPOSIT_REQUEST', [
            'method_name'     => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount'   => showAmount($data->final_amount, currencyFormat: false),
            'amount'          => showAmount($data->amount, currencyFormat: false),
            'charge'          => showAmount($data->charge, currencyFormat: false),
            'rate'            => showAmount($data->rate, currencyFormat: false),
            'trx'             => $data->trx,
        ]);

        $notify[] = ['success', 'You have deposit request has been taken'];
        return to_route('user.deposit.history')->withNotify($notify);
    }




}
