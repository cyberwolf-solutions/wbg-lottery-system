<?php

namespace App\Http\Controllers;

use App\Jobs\PickNumberJob;
use App\Models\PickedNumber;
use Illuminate\Http\Request;
use App\Models\LotteryDashboards;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NumberPickController extends Controller
{
    //
    // public function pickNumber(Request $request)
    // {
    //     $validated = $request->validate([
    //         'number' => 'required',
    //         'lottery_dashboard_id' => 'required|exists:lottery_dashboards,id',
    //         'lottery_id' => 'required|numeric'
    //     ]);

    //     $user = Auth::user();
    //     $wallet = $user->Wallet;

    //     $lotteryDashboard = LotteryDashboards::find($validated['lottery_dashboard_id']);
    //     if(!$wallet){
    //         return response()->json(['message' => 'Wallet not found'],400);

    //     }


    //     //check insufficient balance
    //     if($wallet->available_balance < $lotteryDashboard->price){
    //         return response()->json(['message'=> 'Insufficient balance'], 400);
    //     }


    //     // Check if the number has already been picked within the same lottery_id
    //     if (PickedNumber::where('picked_number', $validated['number'])
    //         ->whereHas('lotteryDashboard', function ($query) use ($validated) {
    //             $query->where('lottery_id', $validated['lottery_id']);
    //         })->exists()
    //     ) {
    //         return response()->json(['message' => 'Number already picked in this lottery'], 400);
    //     }

    //     $wallet->decrement('available_balance',$lotteryDashboard->price);

    //     Log::info("Dispatching PickNumberJob: Number = {$validated['number']}, Lottery ID = {$validated['lottery_dashboard_id']},lottery_id = {$validated['lottery_id']}, User ID = " . Auth::id(),);


    //     // dd($request->all);

    //     PickNumberJob::dispatch($validated['number'], $validated['lottery_dashboard_id'], $validated['lottery_id'] , Auth::id());

    //     return response()->json(['message' => 'Number picked successfully']);
    // }



    public function pickNumber(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required',
            'lottery_dashboard_id' => 'required|exists:lottery_dashboards,id',
            'lottery_id' => 'required|numeric'
        ]);

        Log::info('Request Data:', $request->all());


        $user = Auth::user();

        // Check if number is already allocated
        $alreadyPicked = PickedNumber::where('picked_number', $validated['number'])
            ->where('lottery_dashboard_id', $validated['lottery_dashboard_id'])
            ->exists();

        if ($alreadyPicked) {
            return response()->json(['message' => 'Number already picked'], 400);
        }

        // Temporarily mark the number as allocated
        PickedNumber::create([
            'lottery_id' => $validated['lottery_id'],
            'lottery_dashboard_id' => $validated['lottery_dashboard_id'],
            'user_id' => $user->id,
            'picked_number' => $validated['number'],
            'status' => 'allocated'
        ]);


        // Check if all numbers are picked
        $pickedCount = PickedNumber::where('lottery_dashboard_id', $validated['lottery_dashboard_id'])->count();

        Log::info('Picked Count:', ['picked_count' => $pickedCount]);

        if ($pickedCount >= 100) {
            // Close the current dashboard
            $dashboard = LotteryDashboards::find($validated['lottery_dashboard_id']);
            $dashboard->update(['status' => 'closed']);

            // Create a new dashboard with the same details
            $newDashboard = LotteryDashboards::create([
                'lottery_id' => $dashboard->lottery_id,
                'dashboard' => $dashboard->dashboard,
                'price' => $dashboard->price,
                'date' => $dashboard->date,
                'draw' => $dashboard->draw,
                'draw_number' => $dashboard->draw_number,
                'winning_numbers' => $dashboard->winning_numbers,
                'status' => 'active',
                
            ]);

            return response()->json([
                'message' => 'Number allocated successfully. Dashboard is full and a new dashboard has been created.',
                'new_dashboard_id' => $newDashboard->id
            ]);
        }



        return response()->json([
            'message' => 'Number allocated successfully',
            'number' => $validated['number']
        ]);
    }


    public function checkout(Request $request)
    {




        $user = Auth::user();
        $wallet = $user->Wallet;

        Log::debug('Request data:', $request->all());

        if (!$wallet) {
            return response()->json(['message' => 'Wallet not found'], 400);
        }


        $numbers = $request->input('numbers', []);
        $lotteryId = $request->input('lottery_id');

        $totalPrice = $request->input('total_price');


        // Log::debug('Cart data: ', ['numbers' => $numbers, 'lottery_id' => $lotteryId, 'dashboard_id' => $dashboardId, 'total_price' => $totalPrice]);


        if (empty($numbers)) {
            return response()->json(['message' => 'No numbers selected for checkout'], 400);
        }


        $calculatedTotalPrice = collect($numbers)->sum('price');


        if ($totalPrice != $calculatedTotalPrice) {
            return response()->json(['message' => 'Total price mismatch'], 400);
        }


        if ($wallet->available_balance < $totalPrice) {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }


        $wallet->decrement('available_balance', $totalPrice);


        // Loop through the numbers array
        foreach ($request->input('numbers') as $numberData) {
            // Access the dashboard_id from each individual number entry
            $dashboardId = $numberData['dashboard_id'];  // This is correct

            // Now dispatch the job with the correct values
            PickNumberJob::dispatch($numberData['number'], $dashboardId, $lotteryId, $user->id);
        }


        session()->forget('cart');

        return response()->json(['message' => 'Checkout successful']);
    }


    public function cancel(Request $request)
    {
        $user = Auth::user();

        // Delete the picked numbers for the authenticated user with status 'allocated'
        DB::table('picked_numbers')
            ->where('user_id', $user->id)
            ->where('status', 'allocated')
            ->delete();

        return response()->json(['message' => 'Picked numbers deleted successfully.']);
    }
}
