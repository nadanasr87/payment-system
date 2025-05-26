<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\PaymentController;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
Route::get('/payment', function (Request $request) {
    $product = Product::findOrFail($request->product_id);
    return view('payment', compact('product'));
})->name('payment.page');
Route::get('/products', function () {
    $products = Product::all();
    // dd($products);
    return view('products', compact('products'));
});

