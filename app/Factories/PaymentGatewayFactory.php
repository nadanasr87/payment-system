<?php
namespace App\Factories;

use App\Services\PaymentGateways\PaymentGatewayInterface;
use Illuminate\Contracts\Container\Container;

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
            // add other gateways...
            default => throw new \Exception("Unsupported payment gateway [$gateway]"),
        };
    }
}
