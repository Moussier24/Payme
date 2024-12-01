<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentStatusController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/success', [PaymentStatusController::class, 'success'])->name('payment.success');
Route::get('/cancel', [PaymentStatusController::class, 'cancel'])->name('payment.cancel');
