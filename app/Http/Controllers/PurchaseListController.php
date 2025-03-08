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


        // $lottery = LotteryDashboards::where('lottery_id',$id)->get();
        $lottery = LotteryDashboards::with('lottery')->where('lottery_id', $id)->get();

        Log::info($lottery);

        // Your controller method is fine
        $pickedNumbers = PickedNumber::where('lottery_id', $id)->pluck('picked_number')->toArray();




        Log::info('Number', ['pickednumber' => $pickedNumbers]);


        return Inertia::render('AdminDashboard/Purchase', [
            'lottery' => $lottery,
            'pickedNumbers' => $pickedNumbers,
        ]);
    }
}
