<?php

namespace App\Http\Controllers;

use App\Models\DigitWinners;
use App\Models\Lotteries;
use App\Models\Winner;
use Inertia\Inertia;
use Illuminate\Http\Request;

class WinnersController extends Controller
{
    public function index($id)
    {
        $lottery = Lotteries::find($id);

        if (!$lottery) {
            return redirect()->route('lotteries.index');
        }


        $winners = Winner::with(['user', 'lotteryDashboard'])
            ->where('lottery_id', $id)
            ->get()
            ->sortByDesc(function ($winner) {
                return optional($winner->lotteryDashboard)->date;
            })
            ->groupBy(function ($winner) {
                return $winner->lotteryDashboard ? $winner->lotteryDashboard->dashboard : 'Unknown';
            });

        return Inertia::render('AdminDashboard/Winners', [
            'lottery' => $lottery,
            'lotteryId' => $id,
            'winners' => $winners,
        ]);
    }
    public function digitindex($id)
    {
        $lottery = Lotteries::find($id);

        if (!$lottery) {
            return redirect()->route('lotteries.index');
        }


        $winners = DigitWinners::with(['user', 'lotteryDashboard'])
            ->where('lottery_id', $id)
            ->get()
            ->sortByDesc(function ($winner) {
                return optional($winner->lotteryDashboard)->date;
            })
            ->groupBy(function ($winner) {
                return $winner->lotteryDashboard ? $winner->lotteryDashboard->dashboard : 'Unknown';
            });

        return Inertia::render('AdminDashboard/Winners', [
            'lottery' => $lottery,
            'lotteryId' => $id,
            'winners' => $winners,
        ]);
    }
}
