<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::post('/transactions', [TransactionController::class, 'store']);
Route::post('/callback', [TransactionController::class, 'payment_callback']);