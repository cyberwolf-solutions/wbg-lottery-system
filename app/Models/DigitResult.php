<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DigitResult extends Model
{
    use SoftDeletes;

    protected $table = 'digit_result';

    protected $fillable = [
        'lottery_id',
        'digit_dashboard_id',
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
        return $this->belongsTo(DigitLotteryDashboard::class, 'digit_dashboard_id');
    }
}
