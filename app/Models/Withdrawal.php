<?php

// app/Models/Withdrawal.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;
    protected $table = "withdrawals";

    protected $fillable = [
        'wallet_id',
        'amount',
        'description',
        'withdrawal_date',
        'status'
    ];

    // Relationship: A withdrawal belongs to a wallet
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
