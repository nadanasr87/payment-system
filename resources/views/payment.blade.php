{{-- resources/views/payment.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Payment for: {{ $product->name }}</h2>

    <div class="card shadow-sm p-4">
        <p><strong>Description:</strong> {{ $product->description }}</p>
        <p><strong>Price:</strong> ${{ number_format($product->price, 2) }} USD</p>

        <form method="POST" action="{{ route('payments.store') }}">
            @csrf
            <input type="hidden" name="payment_method" value="stripe">

            <input type="hidden" name="amount" value="{{ $product->price }}">
            <input type="hidden" name="currency" value="USD">
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="mb-3">
                <label for="payment_method_input" class="form-label">Payment Method</label>
                <select name="payment_method" id="payment_method_input" class="form-select" required>
                    <option value="stripe" selected>Stripe</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success w-100">Pay Now</button>
        </form>
    </div>
</div>
@endsection
