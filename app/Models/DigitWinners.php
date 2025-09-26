<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DigitWinners extends Model
{
    use SoftDeletes;

    protected $table = 'digit_winners';

    protected $fillable = [
        'lottery_id',
        'digit_lottery_dashboard_id',
        'user_id',
        'winning_number',
        'price'
    ];

    // Relationship with Lottery
    public function lottery()
    {
        return $this->belongsTo(Lotteries::class, 'lottery_id');
    }



    public function dashboard()
    {
        return $this->belongsTo(DigitLotteryDashboard::class, 'digit_lottery_dashboard_id');
    }


    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
