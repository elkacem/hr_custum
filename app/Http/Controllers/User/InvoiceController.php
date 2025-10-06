<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Core\Authentication\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function download($trx)
    {
        $deposit = Deposit::where('id', $trx)->where('user_id', auth()->id())->firstOrFail();

        if (!$deposit->invoice || !Storage::disk('private')->exists($deposit->invoice)) {
            abort(404, 'Invoice not found.');
        }

        return Storage::disk('private')->download($deposit->invoice, 'invoice_' . $deposit->trx . '.pdf');
    }
}
