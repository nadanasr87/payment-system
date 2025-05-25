<?php

namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository implements PaymentRepositoryInterface
{
    /**
     * Store a new payment record in the database.
     *
     * @param array $data
     * @return Payment
     */
    public function create(array $data): Payment
    {
        return Payment::create([
            'payment_method'   => $data['payment_method'],
            'amount'           => $data['amount'],
            'currency'         => $data['currency'] ?? 'USD',
            'status'           => $data['status'] ?? 'pending',
            'transaction_id'   => $data['transaction_id'] ?? null,
            'payment_details'  => json_encode($data['payment_details'] ?? []),
        ]);
    }
}
