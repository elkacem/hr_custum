<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Models\Deposit;
use App\Models\Gateway;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DepositController extends Controller
{
    public function pending($userId = null) {
        $pageTitle = 'Pending Payment';
        $deposits  = $this->depositData('pending', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function approved($userId = null) {
        $pageTitle = 'Approved Payment';
        $deposits  = $this->depositData('approved', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function successful($userId = null) {
        $pageTitle = 'Successful Payment';
        $deposits  = $this->depositData('successful', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function rejected($userId = null) {
        $pageTitle = 'Rejected Payment';
        $deposits  = $this->depositData('rejected', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function initiated($userId = null) {
        $pageTitle = 'Initiated Payment';
        $deposits  = $this->depositData('initiated', userId: $userId);
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function deposit($userId = null) {
        $pageTitle   = 'Payment History';
        $depositData = $this->depositData($scope = null, $summary = true, userId: $userId);
        $deposits    = $depositData['data'];

        $summary     = $depositData['summary'];
        $successful  = $summary['successful'];
        $pending     = $summary['pending'];
        $rejected    = $summary['rejected'];
        $initiated   = $summary['initiated'];
//        dd($depositData);
        return view('admin.deposit.log', compact('pageTitle', 'deposits', 'successful', 'pending', 'rejected', 'initiated'));
    }


    protected function depositData($scope = null, $summary = false, $userId = null) {
        if ($scope) {
            $deposits = Deposit::$scope()->with(['user', 'gateway', 'rentLog']);
        } else {
            $deposits = Deposit::with(['user', 'gateway', 'rentLog']);
        }

        if ($userId) {
            $deposits = $deposits->where('user_id', $userId);
        }

        $deposits = $deposits->searchable(['trx', 'user:username'])->dateFilter();

        $request = request();

//        if ($request->method) {
//            if ($request->method != Status::GOOGLE_PAY) {
//                $method   = Gateway::where('alias', $request->method)->firstOrFail();
//                $deposits = $deposits->where('method_code', $method->code);
//            } else {
//                $deposits = $deposits->where('method_code', Status::GOOGLE_PAY);
//            }
//        }

        if (!$summary) {
            return $deposits->orderBy('id', 'desc')->paginate(getPaginate());
        } else {
            $successful = clone $deposits;
            $pending    = clone $deposits;
            $rejected   = clone $deposits;
            $initiated  = clone $deposits;

            $successfulSummary = $successful->where('status', Status::PAYMENT_SUCCESS)->sum('amount');
            $pendingSummary    = $pending->where('status', Status::PAYMENT_PENDING)->sum('amount');
            $rejectedSummary   = $rejected->where('status', Status::PAYMENT_REJECT)->sum('amount');
            $initiatedSummary  = $initiated->where('status', Status::PAYMENT_INITIATE)->sum('amount');

            return [
                'data'    => $deposits->orderBy('id', 'desc')->paginate(getPaginate()),
                'summary' => [
                    'successful' => $successfulSummary,
                    'pending'    => $pendingSummary,
                    'rejected'   => $rejectedSummary,
                    'initiated'  => $initiatedSummary,
                ],
            ];
        }
    }

    public function details($id) {
        $deposit   = Deposit::where('id', $id)->with(['user', 'gateway'])->firstOrFail();
        $pageTitle = $deposit->user->username . ' requested ' . showAmount($deposit->amount);
        $details   = ($deposit->detail != null) ? json_encode($deposit->detail) : null;
        return view('admin.deposit.detail', compact('pageTitle', 'deposit', 'details'));
    }

    public function approve($id) {
//        $deposit = Deposit::where('id', $id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();

        $deposit = Deposit::where('id', $id)
            ->where('status', Status::PAYMENT_PENDING)
            ->with('rentLog')
            ->firstOrFail();

        $rent = $deposit->rentLog;
        $vehicleId = $rent->vehicle_id;
        $pickup    = Carbon::parse($rent->pick_time);
        $return    = Carbon::parse($rent->drop_time);

        // 🔒 Vérification disponibilité
        $overlap = Deposit::where('status', Status::ENABLE)
            ->where('id', '!=', $deposit->id)
            ->whereHas('rentLog', function ($q) use ($vehicleId, $pickup, $return) {
                $q->where('vehicle_id', $vehicleId)
                    ->where(function ($q2) use ($pickup, $return) {
                        $q2->where('pick_time', '<', $return)
                            ->where('drop_time', '>', $pickup);
                    });
            })
            ->exists();

        if ($overlap) {
            return back()->withErrors([
                'booking' => '⚠️ Ce véhicule est déjà réservé sur cette période, approbation impossible.'
            ]);
        }

        PaymentController::userDataUpdate($deposit, true);

        // 📄 Regenerate invoice & contract (reuse helper from BookVehiculeController)
//        $this->generateInvoiceAndContract($deposit);


        $notify[] = ['success', 'Payment request approved successfully'];

        return to_route('admin.deposit.pending')->withNotify($notify);
    }

    public function reject(Request $request) {
        $request->validate([
            'id'      => 'required|integer',
            'message' => 'required|string|max:255',
        ]);
        $deposit = Deposit::where('id', $request->id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();


        $deposit->admin_feedback = $request->message;
        $deposit->status         = Status::PAYMENT_REJECT;
        $deposit->save();



//        notify($deposit->user, 'DEPOSIT_REJECT', [
//            'method_name'       => $deposit->methodName(),
//            'method_currency'   => $deposit->method_currency,
//            'method_amount'     => showAmount($deposit->final_amount, currencyFormat: false),
//            'amount'            => showAmount($deposit->amount, currencyFormat: false),
//            'charge'            => showAmount($deposit->charge, currencyFormat: false),
//            'rate'              => showAmount($deposit->rate, currencyFormat: false),
//            'trx'               => $deposit->trx,
//            'rejection_message' => $request->message,
//        ]);

        $notify[] = ['success', 'Payment request rejected successfully'];
        return to_route('admin.deposit.pending')->withNotify($notify);

    }

    protected function generateInvoiceAndContract(Deposit $deposit): void
    {
        $trx   = $deposit->trx;
        $user  = $deposit->user;
        $rent  = $deposit->rentLog()->with(['dossier','pickUpLocation','dropLocation'])->firstOrFail();
        $vehicle = $rent->vehicle;

        // 🗑️ Delete old files if they exist
        if ($deposit->invoice && Storage::disk('private')->exists($deposit->invoice)) {
            Storage::disk('private')->delete($deposit->invoice);
        }
        if ($deposit->contract && Storage::disk('private')->exists($deposit->contract)) {
            Storage::disk('private')->delete($deposit->contract);
        }

        // ----------------------------------------
        // 🧮 Recalculate amounts
        // ----------------------------------------
        $days            = max(1, ceil(Carbon::parse($rent->pick_time)->diffInHours(Carbon::parse($rent->drop_time)) / 24));
        $insurance_total = $rent->insurance_price ?? 0;
        $carburant_fee   = $deposit->carburant_fee ?? 3000; // ✅ if you add this to deposit
        $subtotal        = $days * $vehicle->price;
        $taxPercent      = gs()->tax ?? 0;
        $tax             = ($subtotal + $insurance_total + $carburant_fee) * $taxPercent / 100;

        $amount    = $subtotal + $insurance_total + $carburant_fee + $tax;
        $paid      = $deposit->final_amount ?? 0;
        $remaining = max(0, $amount - $paid);

        // ----------------------------------------
        // 📄 Generate Invoice
        // ----------------------------------------
        $filename     = 'invoice_' . $trx . '.pdf';
        $invoicePath  = 'invoices/' . $filename;

        $pdf = Pdf::loadView('invoice.facture', [
            'deposit'     => $deposit,
            'user'        => $user,
            'description' => 'Payment to ' . gs()->site_name,
        ]);
        Storage::disk('private')->put($invoicePath, $pdf->output());
        $deposit->invoice = $invoicePath;

        // ----------------------------------------
        // 📄 Generate Contract
        // ----------------------------------------
        $contractFilename = 'contract_' . $trx . '.pdf';
        $contractPath     = 'contracts/' . $contractFilename;

        $admin = Auth::guard('admin')->user();

        $contractPdf = Pdf::loadView('invoice.contract', [
            'deposit'   => $deposit,
            'rent'      => $rent,
            'user'      => $user,
            'dossier'   => $vehicle,
            'subtotal'  => $subtotal,
            'insurance' => $insurance_total,
            'carburant' => $carburant_fee,
            'tax'       => $tax,
            'amount'    => $amount,
            'paid'      => $paid,
            'remaining' => $remaining,
            'admin'     => $admin,
        ]);
        Storage::disk('private')->put($contractPath, $contractPdf->output());
        $deposit->contract = $contractPath;

        // Save final updates
        $deposit->save();
    }




}
