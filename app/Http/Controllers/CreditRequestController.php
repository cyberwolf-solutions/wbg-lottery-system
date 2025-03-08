<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CreditRequestController extends Controller
{
    //
    public function index()
{
    $credits = Deposit::with('wallet.user')->get();

    // dd($credits);
    // Log::info('Fetched Deposits:', ['credits' => $credits]);

    return Inertia::render('AdminDashboard/Credit', [
        'credits' => $credits,
        'status' => session('status'),
    ]);
}

}
