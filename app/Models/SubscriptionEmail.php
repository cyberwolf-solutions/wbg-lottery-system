<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionEmail extends Model
{
    protected $table = 'subscription_emails';
    
    protected $fillable = ['email'];
}