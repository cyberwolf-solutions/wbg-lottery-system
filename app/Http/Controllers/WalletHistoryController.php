<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Wallet;

class WalletHistoryController extends Controller
{
    //
    public function index()
    {
        $transactions = Transaction::with(['wallet.user'])->get();

        // dd($transactions);

        return Inertia::render('AdminDashboard/WalletHistory', [
            'transactions' => $transactions,
        ]);
    }
}
