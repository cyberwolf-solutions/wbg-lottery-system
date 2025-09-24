<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lotteries extends Model
{

    use SoftDeletes;

    protected $table = 'lotteries';
    protected $fillable = [
        'name',
        'description',
        'image',
        'color',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    //

    // public function dashboards()
    // {
    //     return $this->hasMany(LotteryDashboards::class);
    // }

    public function dashboards()
    {
        return $this->hasMany(LotteryDashboards::class, 'lottery_id');
    }
    public function digitDashboards()
    {
        return $this->hasMany(DigitLotteryDashboard::class, 'lottery_id');
    }

    public function winners()
    {
        return $this->hasManyThrough(Winner::class, LotteryDashboards::class, 'lottery_id', 'lottery_dashboard_id');
    }
    public function digitwinners()
    {
        return $this->hasManyThrough(
            DigitWinners::class,
            DigitLotteryDashboard::class,
            'lottery_id',                 // Foreign key on DigitLotteryDashboard
            'digit_lottery_dashboard_id', // ✅ correct foreign key on digit_winners
            'id',                         // Local key on Lotteries
            'id'                          // Local key on DigitLotteryDashboard
        );
    }


    public function results()
    {
        return $this->hasManyThrough(
            Results::class,
            LotteryDashboards::class,
            'lottery_id', // Foreign key on LotteryDashboards table
            'dashboard_id', // Foreign key on Results table
            'id', // Local key on Lotteries table
            'id' // Local key on LotteryDashboards table
        );
    }
}
