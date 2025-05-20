<?php

namespace Tests\Feature;

use Mockery;
use Tests\TestCase;
use App\Models\Payment;
use App\Services\StripeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
{
    parent::setUp();
$this->withoutMiddleware(Middleware::class);
}


    /** @test */
    public function it_can_create_a_payment_record()
    {
        $response = $this->postJson('/payments/create', [
            'payment_method' => 'stripe',
            'amount' => 99.99,
            'currency' => 'USD',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'payment_method' => 'stripe',
                'amount' => 99.99,
                'currency' => 'USD',
                'status' => 'pending',
            ]);

        $this->assertDatabaseHas('payments', [
            'payment_method' => 'stripe',
            'amount' => 99.99,
        ]);
    }

    /** @test */
    public function it_returns_error_for_unsupported_payment_method()
    {
        $response = $this->postJson('/payments/process', [
            'payment_method' => 'unknown',
            'amount' => 50,
        ]);

        $response->assertStatus(422);
        $response->assertJson(['error' => 'Unsupported payment method']);
    }

    /** @test */
    public function it_processes_stripe_payment_successfully()
    {
        $fakePaymentIntent = (object)[
            'id' => 'pi_test123',
            'toArray' => fn () => [
                'id' => 'pi_test123',
                'status' => 'succeeded',
            ],
        ];

        $mockStripeService = Mockery::mock(StripeService::class);
        $mockStripeService->shouldReceive('createPaymentIntent')
            ->once()
            ->with(100, 'USD', 'pm_test')
            ->andReturn($fakePaymentIntent);

        $this->app->instance(StripeService::class, $mockStripeService);


        $this->app->instance(StripeService::class, $mockStripeService);

        $response = $this->postJson('/payments/process', [
            'payment_method' => 'stripe',
            'amount' => 100,
            'currency' => 'USD',
            'payment_data' => ['payment_method_id' => 'pm_test'],
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment(['success' => true]);

        $this->assertDatabaseHas('payments', [
            'payment_method' => 'stripe',
            'amount' => 100,
            'status' => 'completed',
            'transaction_id' => 'pi_test123',
        ]);
    }

    /** @test */
    public function it_handles_stripe_failure()
    {
        $mock = Mockery::mock(StripeService::class);
$mock->shouldReceive('createPaymentIntent')
    ->once()
    ->andThrow(new \Exception('Card declined'));

$this->app->instance(StripeService::class, $mock);


        $this->app->instance(StripeService::class, $mock);

        $response = $this->postJson('/payments/process', [
            'payment_method' => 'stripe',
            'amount' => 100,
            'currency' => 'USD',
            'payment_data' => ['payment_method_id' => 'pm_invalid'],
        ]);

        $response->assertStatus(500)
            ->assertJsonFragment([
                'success' => false,
                'message' => 'Payment processing failed.',
            ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
