<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Models\Payment;
use App\Services\PaymentGateways\StripeGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\PaymentGateways\PaymentGatewayInterface;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }


    public function test_user_can_store_payment()
    {
        $amount = 100.99;

        $mockStripeGateway = \Mockery::mock(StripeGateway::class);
        $mockStripeGateway->shouldReceive('pay')
            ->once()
            ->with($amount, 'USD')
            ->andReturn([
                'transaction_id' => 'test_txn_123',
                'status' => 'completed',
                'raw' => [],
            ]);

        // Bind mock so factory returns this mock when resolving StripeGateway
        $this->app->instance(StripeGateway::class, $mockStripeGateway);

        $payload = [
            'payment_method' => 'stripe',
            'amount' => $amount,
            'currency' => 'USD',
        ];

        $response = $this->postJson(route('payments.store'), $payload);

        $response->assertStatus(200);

        $this->assertDatabaseHas('payments', [
            'payment_method' => 'stripe',
            'amount' => $amount,
            'currency' => 'USD',
            'status' => 'completed',
            'transaction_id' => 'test_txn_123',
        ]);
    }

}
