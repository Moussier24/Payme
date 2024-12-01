<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransactionViewController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->get();
        return view('transactions.index', compact('transactions'));
    }
}