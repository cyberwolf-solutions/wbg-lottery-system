<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\PickedNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function dashboard()
{
    $purchaseHistory = PickedNumber::with('lotteryDashboard.lottery')
        ->where('user_id', Auth::id()) 
        ->get();
    // dd($purchaseHistory);

    return Inertia::render('Dashboard', [
        'purchaseHistory' => $purchaseHistory,
    ]);
}

}
