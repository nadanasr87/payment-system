<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('payments', [PaymentController::class, 'index']);
Route::post('payments/create', [PaymentController::class, 'create']);
Route::post('payments/process', [PaymentController::class, 'process']);
Route::get('payments/{id}', [PaymentController::class, 'show']);

