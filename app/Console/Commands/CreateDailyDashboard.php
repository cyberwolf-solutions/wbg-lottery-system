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

            $now = Carbon::now('Asia/Colombo');
            Log::info('Current time: ' . $now);

            if ($now->format('H:i') == '00:00') {
                // Get all unique lottery_id and dashboard combinations
                $lotteryDashboardCombinations = LotteryDashboards::select('lottery_id', 'dashboard', 'dashboardType')
                    ->distinct()
                    ->get();

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

                    // If there's no last dashboard, we can't determine the next date
                    if (!$lastDashboard) {
                        Log::info("No previous dashboard found for lottery_id: {$lotteryId}, dashboard: {$dashboardField}");
                        continue;
                    }

                    // Start from the day after the last dashboard date
                    $currentDate = Carbon::parse($lastDashboard->date);
                    $nextValidDate = null;
                    $maxDaysToCheck = 7; 

                    // Find the next non-holiday date
                    for ($i = 1; $i <= $maxDaysToCheck; $i++) {
                        $checkDate = $currentDate->copy()->addDays($i);
                        $isHoliday = Holiday::where('date', $checkDate->format('Y-m-d'))->exists();
                        
                        if (!$isHoliday) {
                            $nextValidDate = $checkDate;
                            break;
                        }
                        Log::info("Skipping holiday date: {$checkDate->format('Y-m-d')} for lottery_id: {$lotteryId}");
                    }

                    if (!$nextValidDate) {
                        Log::info("Could not find a non-holiday date within {$maxDaysToCheck} days for lottery_id: {$lotteryId}");
                        continue;
                    }

                    // Check if a dashboard already exists for this date
                    $existingDashboard = LotteryDashboards::where('lottery_id', $lotteryId)
                        ->where('dashboard', $dashboardField)
                        ->where('dashboardType', $dashboardType)
                        ->where('date', $nextValidDate->format('Y-m-d'))
                        ->first();

                    if ($existingDashboard) {
                        Log::info("Dashboard already exists for lottery_id: {$lotteryId}, dashboard: {$dashboardField}, date: {$nextValidDate->format('Y-m-d')}");
                        continue;
                    }

                    // Calculate the number of days since last dashboard to determine draw increments
                    $daysSinceLast = $currentDate->diffInDays($nextValidDate);
                    $newDrawNumber = (int)$lastDashboard->draw_number + $daysSinceLast;
                    $newDraw = $lastDashboard->draw + $daysSinceLast;
                    $formattedDrawNumber = str_pad($newDrawNumber, 3, '0', STR_PAD_LEFT);

                    // Get other attributes from the last dashboard
                    $price = $lastDashboard->price;
                    $winningNumbers = $this->generateWinningNumbers();

                    // Create the new dashboard
                    LotteryDashboards::create([
                        'lottery_id' => $lotteryId,
                        'dashboard' => $dashboardField,
                        'price' => $price,
                        'date' => $nextValidDate->format('Y-m-d'),
                        'draw' => $newDraw,
                        'draw_number' => $formattedDrawNumber,
                        'winning_numbers' => json_encode($winningNumbers),
                        'dashboardType' => $dashboardType,
                        'status' => 'active',
                    ]);

                    Log::info("Dashboard created successfully for lottery_id: {$lotteryId}, dashboard: {$dashboardField}, date: {$nextValidDate->format('Y-m-d')}, draw number: {$formattedDrawNumber}");

                    // Notify users
                    $users = User::all();
                    foreach ($users as $user) {
                        $user->notify(new NewDashboardCreatedNotification(
                            $lotteryId,
                            $dashboardField,
                            $formattedDrawNumber,
                            $nextValidDate->format('Y-m-d')
                        ));
                    }
                }

                $this->info('All dashboards created successfully.');
            } else {
                Log::info('Command skipped. Current time is not 00:00 AM Sri Lanka time.');
                $this->info('Skipped: It is not 00:00 AM Sri Lanka time.');
            }
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