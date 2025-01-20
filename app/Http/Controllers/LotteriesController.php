<?php

namespace App\Http\Controllers;

use App\Models\Lottery;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LotteriesController extends Controller
{
    public function index($id)
    {
        // Fetch the specific lottery by ID
        // $lottery = Lottery::findOrFail($id);

        

        $lotteries = [
            5 => [
                'name' => 'US Powerball',
                'jackpot' => '$541,000,000',
                'draw_time' => 'Next draw in 2 days',
            ],
            // Add other lottery data here based on ID
        ];

        $lottery = $lotteries[$id] ?? null;

        // Pass the lottery data to the Inertia page
        return Inertia::render('User/lottery',  ['lottery' => $lottery,
            'status' => session('status'),
        ]);
    }
}

