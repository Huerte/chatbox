<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $fillable = ['chat_id', 'user_id', 'emoji'];

    public function chat()
    {
        return $this->belongsTo(Chats::class, 'chat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
