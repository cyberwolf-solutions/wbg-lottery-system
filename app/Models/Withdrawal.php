<?php

// app/Models/Withdrawal.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Withdrawal extends Model
{
    use HasFactory ,SoftDeletes;
    protected $table = "withdrawals";

    protected $fillable = [
        'wallet_id',
        'amount',
        'withdrawal_type',
        'withdrawal_date',
        'status',
        'decline_reason'
    ];

    protected $dates = ['deleted_at'];

    // Relationship: A withdrawal belongs to a wallet
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
