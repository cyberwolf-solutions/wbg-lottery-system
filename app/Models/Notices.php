<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notices extends Model
{
    protected $fillable = ['title', 'message', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}