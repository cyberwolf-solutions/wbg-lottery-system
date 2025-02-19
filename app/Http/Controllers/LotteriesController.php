<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Lottery;
use App\Models\Lotteries;
use Illuminate\Http\Request;
use App\Models\LotteryDashboards;
use Illuminate\Support\Facades\Log;

class LotteriesController extends Controller
{


    public function index(){
        return Inertia::render();
    }

    
    public function show($id)
    {
        

        $lotteries = Lotteries::find($id);

        Log::info( $lotteries);

        // if(!$lotteries){
        //     return redirect()->back()->with('error' , 'Lottery not found');
        // }

        $lotterydashboards = LotteryDashboards::where('lottery_id', $id)->get();

        // dd($lotterydashboards);
        

        // Pass the lottery data to the Inertia page
        return Inertia::render('User/lottery', [
            'lotterie' => $lotteries,
             'lotterydashboards' => $lotterydashboards,
            'status' => session('status'),
        ]);
    }
}

