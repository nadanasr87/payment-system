<?php
namespace App\Factories;

use Illuminate\Contracts\Container\Container;
use App\Services\PaymentGateways\PaypalGateway;
use App\Services\PaymentGateways\PaymentGatewayInterface;

class PaymentGatewayFactory
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function create(string $gateway): PaymentGatewayInterface
    {
        return match ($gateway) {
            'stripe' => $this->container->make(\App\Services\PaymentGateways\StripeGateway::class),
            'paypal' => $this->container->make(PaypalGateway::class),
            // add other gateways...
            default => throw new \Exception("Unsupported payment gateway [$gateway]"),
        };
    }
}
