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
}
