<?php

// app/Models/Wallet.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "wallets";

    // Define the fillable attributes for the wallet
    protected $fillable = [
        'user_id',
        'available_balance',
    ];

    // Relationship: A wallet belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: A wallet has many transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Relationship: A wallet has many deposits
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    // Relationship: A wallet has many withdrawals
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }
}
