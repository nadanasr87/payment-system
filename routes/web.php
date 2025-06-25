<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\PaymentController;

Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
Route::get('/payment', function (Request $request) {
    $product = Product::findOrFail($request->product_id);
    return view('payment', compact('product'));
})->name('payment.page');
Route::get('/', function () {
    $products = Product::all();
    return view('products', compact('products'));
});

