<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Lotteries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class LandingLotteryController extends Controller
{
  //
  public function index()
  {


    $lotteries = Lotteries::with(['dashboards' => function ($query) {
      $query->where('date', '>=', today())
        ->where('status', 'active') 
        ->orderBy('date', 'asc')
        ->limit(1);
    }])->get();


    // dd($lotteries);



    foreach ($lotteries as $lottery) {
      if ($lottery->dashboards->isNotEmpty()) {
        Log::info('Dashboard date:', [
          'raw' => $lottery->dashboards[0]->date,
          'parsed' => now()->parse($lottery->dashboards[0]->date)->format('Y-m-d H:i:s')
        ]);
      }
    }
    // dd($lotteries);

    return   Inertia::render('User/lotteryPage', [
      'lotteries' => $lotteries
    ]);
  }
}
