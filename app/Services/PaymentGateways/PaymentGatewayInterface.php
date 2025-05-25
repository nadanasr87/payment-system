<?php

namespace App\Services\PaymentGateways;

interface PaymentGatewayInterface
{
    /**
     * Process a payment.
     *
     * @param float $amount
     * @param string $currency
     * @return array
     */
    public function pay(float $amount, string $currency): array;
}
