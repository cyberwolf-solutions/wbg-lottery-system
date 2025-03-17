<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\PickedNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    //
    public function dashboard()
{
    $purchaseHistory = PickedNumber::with('lotteryDashboard')->get();

    return Inertia::render('Dashboard', [
        'purchaseHistory' => $purchaseHistory,
    ]);
}

}
