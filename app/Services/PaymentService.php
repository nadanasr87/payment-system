<?php

namespace App\Services;

use App\Models\Payment;
use App\Factories\PaymentGatewayFactory;
use App\Repositories\PaymentRepositoryInterface;

class PaymentService
{
    protected PaymentGatewayFactory $gatewayFactory;
    protected PaymentRepositoryInterface $paymentRepository;

    public function __construct(
    PaymentGatewayFactory $gatewayFactory,
    PaymentRepositoryInterface $paymentRepository
) {
    $this->gatewayFactory = $gatewayFactory;
    $this->paymentRepository = $paymentRepository;
}


    public function process(string $method, float $amount, string $currency = 'USD'): Payment
    {
        $gateway = $this->gatewayFactory->create($method);

        $result = $gateway->pay($amount, $currency);

        return $this->paymentRepository->create([
            'payment_method' => $method,
            'amount' => $amount,
            'currency' => $currency,
            'status' => $result['status'] ?? 'pending',
            'transaction_id' => $result['transaction_id'] ?? null,
            'payment_details' => $result['raw'] ?? [],
        ]);
    }
}
