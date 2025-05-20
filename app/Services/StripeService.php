<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createPaymentIntent(float $amount, string $currency, string $paymentMethodId): PaymentIntent
    {
        return PaymentIntent::create([
            'amount' => intval($amount * 100), // convert to cents
            'currency' => strtolower($currency),
            'payment_method' => $paymentMethodId,
            'confirmation_method' => 'automatic',
            'confirm' => true,
        ]);
    }
}
