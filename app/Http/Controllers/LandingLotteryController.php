<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Lotteries;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LandingLotteryController extends Controller
{
    //
    public function index(){



        $lotteries = Lotteries::all();

      return   Inertia::render('User/lotteryPage',[
        'lotteries' => $lotteries
      ]);
    }
}