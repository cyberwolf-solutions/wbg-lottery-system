<?php

// app/Models/Transaction.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = "transactions";

    protected $fillable = [
        'wallet_id',
        'amount',
        'type', 
        'lottery_id', 
        'lottery_dashboard_id', 
        'transaction_date',
        'picked_number'
    ];

    // Relationship: A transaction belongs to a wallet
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function lottery()
    {
        return $this->belongsTo(Lotteries::class);
    }

    // Define the relationship with LotteryDashboard model
    public function lotteryDashboard()
    {
        return $this->belongsTo(LotteryDashboards::class);
    }

    
}

