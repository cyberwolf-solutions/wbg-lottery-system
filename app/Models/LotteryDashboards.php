<?php

namespace App\Models;

use App\Models\PickedNumber;
use Illuminate\Support\Lottery;
use Illuminate\Database\Eloquent\Model;
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
        return $this->hasMany(PickedNumber::class, 'lottery_dashboard_id');
        // Explicitly specify the foreign key column name
    }
    // In LotteryDashboards.php model
    public function winners()
    {
        return $this->hasMany(Winner::class, 'lottery_dashboard_id');
    }
}
