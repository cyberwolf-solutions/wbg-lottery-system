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
      $todayAt8PM = now()->setTime(20, 0, 0);
      $startDate = now()->lt($todayAt8PM)
        ? $todayAt8PM
        : now()->addDay()->setTime(20, 0, 0);

      $query->where('date', '>=', $startDate)
        ->orderBy('date', 'asc')
        ->limit(1);
    }])->get();

    // Debug output - remove this after testing
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
