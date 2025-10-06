<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceController extends Controller
{
    public function view($trx)
    {
        $deposit = Deposit::where('trx', $trx)->firstOrFail(); // No user restriction for admin

//        dd($deposit);

        if (!$deposit->invoice || !Storage::disk('private')->exists($deposit->invoice)) {
            abort(404, 'Invoice not found.');
        }

        return Storage::disk('private')->response($deposit->invoice, null, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="invoice_' . $deposit->trx . '.pdf"',
        ]);
    }

    public function viewContract($trx)
    {
        $deposit = Deposit::where('trx', $trx)->firstOrFail(); // No user restriction for admin

        if (!$deposit->contract || !Storage::disk('private')->exists($deposit->contract)) {
            abort(404, 'Invoice not found.');
        }

        return Storage::disk('private')->response($deposit->contract, null, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="contract_' . $deposit->trx . '.pdf"',
        ]);
    }

}
