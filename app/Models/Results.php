<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Results extends Model
{
    use SoftDeletes;

    protected $table = 'results';

    protected $fillable = [
        'lottery_id',
        'dashboard_id',
        'winning_number',
        'additional_info', 
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Relationship with Lottery
    public function lottery()
    {
        return $this->belongsTo(Lotteries::class, 'lottery_id');
    }

    // Relationship with LotteryDashboard
    public function dashboard()
    {
        return $this->belongsTo(LotteryDashboards::class, 'dashboard_id');
    }
}
