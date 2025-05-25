<?php

namespace App\Services\PaymentGateways;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeGateway implements PaymentGatewayInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function pay(float $amount, string $currency = 'USD', array $options = []): array
    {
        $paymentIntent = PaymentIntent::create([
            'amount' => intval($amount * 100), // amount in cents
            'currency' => strtolower($currency),
            'payment_method_types' => ['card'],
        ]);

        return [
            'transaction_id' => $paymentIntent->id,
            'status' => $paymentIntent->status,
            'raw' => $paymentIntent->jsonSerialize(),
        ];
    }
}
