<?php

namespace App\Http\Controllers;

use App\Jobs\PickNumberJob;
use App\Models\PickedNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\LotteryDashboards;
use Illuminate\Support\Facades\Auth;

class NumberPickController extends Controller
{
    //
    public function pickNumber(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required',
            'lottery_dashboard_id' => 'required|exists:lottery_dashboards,id',
            'lottery_id' => 'required|numeric'
        ]);

        $user = Auth::user();
        $wallet = $user->Wallet;

        $lotteryDashboard = LotteryDashboards::find($validated['lottery_dashboard_id']);
        if(!$wallet){
            return response()->json(['message' => 'Wallet not found'],400);

        }

        //check insufficient balance
        if($wallet->available_balance < $lotteryDashboard->price){
            return response()->json(['message'=> 'Insufficient balance'], 400);
        }


        // Check if the number has already been picked within the same lottery_id
        if (PickedNumber::where('picked_number', $validated['number'])
            ->whereHas('lotteryDashboard', function ($query) use ($validated) {
                $query->where('lottery_id', $validated['lottery_id']);
            })->exists()
        ) {
            return response()->json(['message' => 'Number already picked in this lottery'], 400);
        }

        $wallet->decrement('available_balance',$lotteryDashboard->price);

        Log::info("Dispatching PickNumberJob: Number = {$validated['number']}, Lottery ID = {$validated['lottery_dashboard_id']},lottery_id = {$validated['lottery_id']}, User ID = " . Auth::id(),);


        // dd($request->all);

        PickNumberJob::dispatch($validated['number'], $validated['lottery_dashboard_id'], $validated['lottery_id'] , Auth::id());

        return response()->json(['message' => 'Number picked successfully']);
    }
}
