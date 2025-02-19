<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Lottery;
use Illuminate\Database\Eloquent\SoftDeletes;

class LotteryDashboards extends Model
{
    //
    use SoftDeletes;


    protected $table = 'lottery_dashboards';
    protected $fillable = [
        'lottery_id',
        'dashboard',
        'price',
        'date',
        'draw',
        'draw_number',
        'winning_numbers',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // public function lottery()
    // {
    //     return $this->belongsTo(Lottery::class);
    // }
    public function lottery()
    {
        return $this->belongsTo(Lotteries::class, 'lottery_id');
    }
}
