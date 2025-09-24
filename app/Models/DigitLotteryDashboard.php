<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitLotteryDashboard extends Model
{
    //
    protected $table = "digit_lottery_dashboards";
    protected $fillable = [
        'lottery_id',
        'dashboard',
        'price',
        'date',
        'draw',
        'draw_number',
        'winning_numbers',

        'status',
        'dashboardType',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function lottery()
    {
        return $this->belongsTo(Lotteries::class, 'lottery_id');
    }
    public function pickedNumbers()
    {
        return $this->hasMany(DigitPickedNumber::class, 'digit_lottery_dashboard_id');
        // Explicitly specify the foreign key column name
    }
}
