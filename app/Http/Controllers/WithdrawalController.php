<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class WithdrawalController extends Controller
{
    //
    public function index()
    {
        // Fetch the withdrawals with the related wallet and user data
        $credits = Withdrawal::with('wallet.user')->get();
        // $withdrawals = Deposit::with('wallet.user')->get();
        
        // Log for debugging
        // Log::info($withdrawals);
        
        // Pass withdrawals to the Inertia view
        return Inertia::render('AdminDashboard/Credit', [
            
            'status' => session('status'),
            'credits' => $credits,
        ]);
    }
    
    

}
