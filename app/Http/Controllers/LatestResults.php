<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

use App\Models\Results;
use App\Models\Lotteries;
use Illuminate\Http\Request;

class LatestResults extends Controller
{
    public function index()
    {
        $lotteries = Lotteries::with(['dashboards', 'results' => function ($query) {
            $query->latest()->limit(1);
        }])->get()->map(function ($lottery) {
            $latestResult = $lottery->results->first();

            $dashboardInfo = null;
            if ($latestResult && $latestResult->dashboard) {
                $winningNumbers = explode(',', $latestResult->dashboard->winning_numbers);

                $dashboardInfo = [
                    'dashboardType' => $latestResult->dashboard->dashboardType,
                    'winning_numbers' => $latestResult->dashboard->winning_numbers,
                    'displayDigits' => $this->getDisplayDigits(
                        $winningNumbers,
                        $latestResult->dashboard->dashboardType
                    ),
                    'draw_number' => $latestResult->dashboard->draw_number,
                    'date' => $latestResult->dashboard->date,
                ];
            }

            return [
                'id' => $lottery->id,
                'name' => $lottery->name,
                'image' => $lottery->image,
                'color' => $lottery->color,
                'latestResult' => $latestResult ? [
                    'winning_number' => $latestResult->winning_number,
                    'additional_info' => $latestResult->additional_info,
                ] : null,
                'dashboardInfo' => $dashboardInfo,
            ];
        });

        return Inertia::render('User/LatestResults', [
            'lotteries' => $lotteries,
            'status' => session('status'),
        ]);
    }

    private function getDisplayDigits($winningNumbers, $dashboardType)
    {
        if (empty($winningNumbers)) return null;

        if ($dashboardType === 'First Digit') {
            return [
                'type' => 'first',
                'digits' => [reset($winningNumbers)], 
                'position' => 'first'
            ];
        } else {
            return [
                'type' => 'last',
                'digits' => [end($winningNumbers)], 
                'position' => 'last'
            ];
        }
    }
}
