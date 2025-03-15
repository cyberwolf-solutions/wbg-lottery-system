<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Winner extends Model
{
    use SoftDeletes;

    protected $table = 'winners';

    protected $fillable = [
        'lottery_id',
        'lottery_dashboard_id',
        'user_id',
        'winning_number',
        'price'
    ];

    // Relationship with Lottery
    public function lottery()
    {
        return $this->belongsTo(Lotteries::class, 'lottery_id');
    }

    

    public function lotteryDashboard()
    {
        return $this->belongsTo(LotteryDashboards::class, 'lottery_dashboard_id');
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
