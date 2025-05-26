<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    protected PaymentService $service;

    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string|max:3',
            'product_id' => 'nullable|integer|exists:products,id',
        ]);

        $payment = $this->service->process(
            $request->payment_method,
            $request->amount,
            $request->currency ?? 'USD',
            $request->product_id
        );

        return back()->with([
            'message' => 'Payment processed successfully.',
            'payment' => $payment,
        ]);
    }

}
