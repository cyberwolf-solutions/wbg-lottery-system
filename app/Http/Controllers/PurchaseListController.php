<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Lotteries;
use Illuminate\Http\Request;
use App\Models\LotteryDashboards;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\PickedNumber;

class PurchaseListController extends Controller
{
    //

    public function index($id)
{
    $lottery = LotteryDashboards::with('lottery')->where('lottery_id', $id)->get();

    // Fetch picked numbers grouped by lottery_dashboard_id
    $pickedNumbers = PickedNumber::where('lottery_id', $id)
        ->get()
        ->groupBy('lottery_dashboard_id')
        ->map(function ($items) {
            return $items->pluck('picked_number')->toArray();
        });

    return Inertia::render('AdminDashboard/Purchase', [
        'lottery' => $lottery,
        'pickedNumbers' => $pickedNumbers,
    ]);
}

}
