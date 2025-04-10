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
        ->with('user:id,name') 
        ->get()
        ->groupBy('lottery_dashboard_id')
        ->map(function ($items) {
            return $items->map(function ($item) {
                return [
                    'number' => $item->picked_number,
                    'user' => $item->user->name 
                ];
            });
        });
        return Inertia::render('AdminDashboard/Purchase', [
            'lottery' => $lottery,
            'pickedNumbers' => $pickedNumbers,
        ]);
    }
}
