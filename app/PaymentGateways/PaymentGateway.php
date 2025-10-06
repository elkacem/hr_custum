<?php
namespace App\PaymentGateways;

use App\Models\Deposit;
use Illuminate\Contracts\Support\Responsable;

interface PaymentGateway
{
    /**
     * Process the payment.
     *
     * @param array $data
     * @return mixed
     */
    public function create($deposit);

    /**
     * Verify the payment.
     *
     * @param array $data
     * @return mixed
     */
    public function verify($id) : Deposit;

}
