<?php

namespace App\Repositories;

interface PaymentRepositoryInterface
{
    /**
     * Create a new payment record.
     *
     * @param array $data
     * @return \App\Models\Payment
     */
    public function create(array $data);
}
