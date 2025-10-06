<?php

namespace App\PaymentGateways;

use Illuminate\Support\Str;

class PaymentGatwayFactory
{

    /**
     * Create an instance of the payment gateway.
     *
     * @param string $name
     * @return PaymentGateway
     * @throws \Exception
     */

    public static function create($name) : PaymentGateway
    {
        $class = 'App\\PaymentGateways\\' . Str::studly($name);

        if (!class_exists($class)) {
            throw new \Exception("Payment gateway class {$class} does not exist.");
        }

        return new $class();
    }
}

