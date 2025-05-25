<?php

namespace App\Providers;

use App\Services\StripeService;
use App\Repositories\PaymentRepository;
use Illuminate\Support\ServiceProvider;
use App\Factories\PaymentGatewayFactory;
use App\Services\PaymentGateways\StripeGateway;
use App\Repositories\PaymentRepositoryInterface;
use App\Services\PaymentGateways\PaymentGatewayInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
   public function register(): void
{
    $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);

    $this->app->singleton(PaymentGatewayFactory::class, function ($app) {
        return new PaymentGatewayFactory($app);
    });
}


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
