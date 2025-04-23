<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $table = 'messages';
    protected $fillable = [
        'user_id',
        'adimin_id',
        'message',
        'is_from_user',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id'); 
    }
}
