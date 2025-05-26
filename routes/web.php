<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PaymentController;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
Route::view('/payment', 'payment');

