<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PickedNumber extends Model
{
    //
    use SoftDeletes;
    protected $table = "picked_numbers";

    protected $fillable = ['lottery_id', 'lottery_dashboard_id', 'user_id', 'picked_number', 'status'];


    public function lottery()
    {
        return $this->belongsTo(Lotteries::class, 'lottery_id');
    }

    public function lotteryDashboard()
    {
        return $this->belongsTo(LotteryDashboards::class, 'lottery_dashboard_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
