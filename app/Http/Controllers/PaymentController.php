<?php
// App\Http\Controllers\PaymentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StripeService;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string|in:stripe',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
        ]);

        $payment = Payment::create([
            'payment_method' => $validated['payment_method'],
            'amount' => $validated['amount'],
            'currency' => strtoupper($validated['currency']),
            'status' => 'pending',
        ]);

        return response()->json($payment);
    }

    public function process(Request $request, StripeService $stripeService)
    {
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'payment_data.payment_method_id' => 'required|string',
        ]);

        if ($validated['payment_method'] !== 'stripe') {
            return response()->json(['error' => 'Unsupported payment method'], 422);
        }

        try {
            $intent = $stripeService->createPaymentIntent(
                $validated['amount'],
                $validated['currency'],
                $validated['payment_data']['payment_method_id']
            );

            $payment = Payment::create([
                'payment_method' => 'stripe',
                'amount' => $validated['amount'],
                'currency' => strtoupper($validated['currency']),
                'status' => 'completed',
                'transaction_id' => $intent->id,
            ]);

            return response()->json(['success' => true, 'payment' => $payment]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
