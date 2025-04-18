<?php

namespace App\Console\Commands;

use App\Models\Holiday;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;
use App\Models\LotteryDashboards;
use Illuminate\Support\Facades\Log;
use App\Notifications\NewDashboardCreatedNotification;

class CreateDailyDashboard extends Command
{
    protected $signature = 'generate:dashboard';
    protected $description = 'Generate a new dashboard every day for each lottery and dashboard combination';

    public function handle()
    {
        try {
            Log::info('Starting the dashboard creation process.');

            // Get all unique lottery_id and dashboard combinations
            $lotteryDashboardCombinations = LotteryDashboards::select('lottery_id', 'dashboard', 'dashboardType')
                ->distinct()
                ->get();

            // Get tomorrow's date
            $tomorrow = Carbon::tomorrow();
            $tomorrowDate = $tomorrow->format('Y-m-d');

            // Check if tomorrow is a holiday
            $isHoliday = Holiday::where('date', $tomorrowDate)->exists();
            if ($isHoliday) {
                Log::info("Skipped dashboard creation for tomorrow ({$tomorrowDate}) because it's a holiday");
                $this->info("Skipped dashboard creation for tomorrow ({$tomorrowDate}) because it's a holiday");
                return;
            }

            foreach ($lotteryDashboardCombinations as $combination) {
                $lotteryId = $combination->lottery_id;
                $dashboardField = $combination->dashboard;
                $dashboardType = $combination->dashboardType;

                // Get the last dashboard for this combination
                $lastDashboard = LotteryDashboards::where('lottery_id', $lotteryId)
                    ->where('dashboard', $dashboardField)
                    ->where('dashboardType', $dashboardType)
                    ->orderBy('id', 'desc')
                    ->first();

                // Determine the new draw number and draw count
                if ($lastDashboard) {
                    $newDrawNumber = (int)$lastDashboard->draw_number + 1;
                    $newDraw = $lastDashboard->draw + 1;
                } else {
                    $newDrawNumber = 1;
                    $newDraw = 1;
                }
                $formattedDrawNumber = str_pad($newDrawNumber, 3, '0', STR_PAD_LEFT);

                // Get other attributes from the last dashboard or use defaults
                $price = $lastDashboard ? $lastDashboard->price : 1000;
                $winningNumbers = $this->generateWinningNumbers();

                // Check if a dashboard already exists for tomorrow's date
                $existingDashboard = LotteryDashboards::where('lottery_id', $lotteryId)
                    ->where('dashboard', $dashboardField)
                    ->where('dashboardType', $dashboardType)
                    ->where('date', $tomorrowDate)
                    ->first();

                if ($existingDashboard) {
                    Log::info("Dashboard already exists for lottery_id: {$lotteryId}, dashboard: {$dashboardField}, date: {$tomorrowDate}");
                    continue;
                }

                // Create the new dashboard
                LotteryDashboards::create([
                    'lottery_id' => $lotteryId,
                    'dashboard' => $dashboardField,
                    'price' => $price,
                    'date' => $tomorrowDate, // Always use tomorrow's date
                    'draw' => $newDraw,
                    'draw_number' => $formattedDrawNumber,
                    'winning_numbers' => json_encode($winningNumbers),
                    'dashboardType' => $dashboardType,
                    'status' => 'active',
                ]);

                Log::info("Dashboard created successfully for lottery_id: {$lotteryId}, dashboard: {$dashboardField}, date: {$tomorrowDate}, draw number: {$formattedDrawNumber}");

                // Notify users
                $users = User::all();
                foreach ($users as $user) {
                    $user->notify(new NewDashboardCreatedNotification(
                        $lotteryId,
                        $dashboardField,
                        $formattedDrawNumber,
                        $tomorrowDate
                    ));
                }
            }

            $this->info('All dashboards created successfully.');
        } catch (\Exception $e) {
            Log::error("Error creating dashboards: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
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