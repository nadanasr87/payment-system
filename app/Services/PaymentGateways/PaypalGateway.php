<?php
namespace App\Services\PaymentGateways;

use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class PaypalGateway implements PaymentGatewayInterface
{
    protected $client;

    public function __construct()
    {
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.secret');
        $environment = new SandboxEnvironment($clientId, $clientSecret); 
        $this->client = new PayPalHttpClient($environment);
    }

    public function pay(float $amount, string $currency = 'USD', array $options = []): array
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => strtoupper($currency),
                    'value' => number_format($amount, 2, '.', ''),
                ]
            ]],
        ];

        $response = $this->client->execute($request);

        return [
            'transaction_id' => $response->result->id,
            'status' => $response->result->status,
            'raw' => json_decode(json_encode($response->result), true),
        ];
    }
}
