@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Payment for: {{ $product->name }}</h2>

    <div id="alert-placeholder"></div> {{-- Place to show alerts --}}

    <div class="card shadow-sm p-4">
        <p><strong>Description:</strong> {{ $product->description }}</p>
        <p><strong>Price:</strong> ${{ number_format($product->price, 2) }} USD</p>

        <form id="payment-form" method="POST" action="{{ route('payments.store') }}">
            @csrf
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

            <button type="submit" class="btn btn-success w-100" id="submit-btn">Pay Now</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {
    $('#payment-form').submit(function (e) {
        e.preventDefault(); // prevent default form submit

        $('#submit-btn').attr('disabled', true).text('Processing...');

        // Clear previous alerts
        $('#alert-placeholder').html('');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                $('#alert-placeholder').html(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Payment successful!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                $('#submit-btn').attr('disabled', false).text('Pay Now');
            },
            error: function (xhr) {
                let errorMessage = 'Something went wrong. Please try again.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                $('#alert-placeholder').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${errorMessage}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                $('#submit-btn').attr('disabled', false).text('Pay Now');
            }
        });
    });
});
</script>
@endsection
