<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentStatusController extends Controller
{
    public function success()
    {
        return view('payment.success');
    }

    public function cancel()
    {
        return view('payment.cancel');
    }
}