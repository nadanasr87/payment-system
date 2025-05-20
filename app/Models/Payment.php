<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'payment_method',
        'amount',
        'currency',
        'status',
        'transaction_id',
        'payment_details',
    ];

    protected $casts = [
        'payment_details' => 'array',
    ];
}
