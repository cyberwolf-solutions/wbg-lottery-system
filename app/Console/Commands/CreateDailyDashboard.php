<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\LotteryDashboards;
use Illuminate\Support\Facades\Log;

class CreateDailyDashboard extends Command
{
    protected $signature = 'generate:dashboard';
    protected $description = 'Generate a new dashboard every day for each lottery and dashboard combination';

    public function handle()
    {
        try {
            // Log the start of the dashboard creation process
            Log::info('Starting the dashboard creation process.');

            // Get all unique lottery_id and dashboard combinations
            $lotteryDashboardCombinations = LotteryDashboards::select('lottery_id', 'dashboard')
                ->distinct()
                ->get();

            foreach ($lotteryDashboardCombinations as $combination) {
                $lotteryId = $combination->lottery_id;
                $dashboardField = $combination->dashboard;

                // Get the last dashboard for this specific lottery_id and dashboard
                $lastDashboard = LotteryDashboards::where('lottery_id', $lotteryId)
                    ->where('dashboard', $dashboardField)
                    ->orderBy('id', 'desc')
                    ->first();

                // Determine the new draw number
                $newDrawNumber = $lastDashboard ? (int)$lastDashboard->draw + 1 : 1;
                $formattedDrawNumber = str_pad($newDrawNumber, 3, '0', STR_PAD_LEFT);

                // Get the new date based on the last dashboard's date
                $newDate = $lastDashboard
                    ? Carbon::parse($lastDashboard->date)->addDay()->format('Y-m-d')
                    : Carbon::now()->format('Y-m-d');

                // Get other attributes from the last dashboard
                $price = $lastDashboard ? $lastDashboard->price : 1000;
                $winningNumbers = $this->generateWinningNumbers();

                // Create a new dashboard for this lottery_id and dashboard
                LotteryDashboards::create([
                    'lottery_id' => $lotteryId,
                    'dashboard' => $dashboardField,
                    'price' => $price,
                    'date' => $newDate,
                    'draw' => $newDrawNumber, // Save as 1, 2, 3...
                    'draw_number' => $formattedDrawNumber, // Save as 001, 002, 003...
                    'winning_numbers' => json_encode($winningNumbers),
                    'status' => 'active',
                ]);

                Log::info("Dashboard created successfully for lottery_id: {$lotteryId}, dashboard: {$dashboardField}, with draw number: {$newDrawNumber}");
            }

            // Output a success message
            $this->info('All dashboards created successfully.');
        } catch (\Exception $e) {
            // Log the error details
            Log::error("Error creating dashboards: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());

            // Output an error message
            $this->error("Error creating dashboards: " . $e->getMessage());
        }
    }

    private function generateWinningNumbers()
    {
        $numbers = [];
        for ($i = 0; $i < 100; $i++) {
            $numbers[] = str_pad($i, 2, '0', STR_PAD_LEFT);
        }
        return $numbers;
    }
}
