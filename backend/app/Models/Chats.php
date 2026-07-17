<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    /** @use HasFactory<\Database\Factories\ChatsFactory> */
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'group_id',
        'message',
        'attachment_path',
        'attachment_type',
        'reply_to_id',
        'is_forwarded',
    ];

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function group() {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function replyTo() {
        return $this->belongsTo(Chats::class, 'reply_to_id');
    }

    public function deletions() {
        return $this->hasMany(ChatDeletion::class, 'chat_id');
    }

    public function scopeBetweenUsers($query, $userId1, $userId2) {
        return $query->whereNull('group_id')->where(function ($q) use ($userId1, $userId2) {
            $q->where('sender_id', $userId1)->where('receiver_id', $userId2);
        })->orWhere(function ($q) use ($userId1, $userId2) {
            $q->whereNull('group_id')->where('sender_id', $userId2)->where('receiver_id', $userId1);
        });
    }

    public function reactions() {
        return $this->hasMany(Reaction::class, 'chat_id');
    }
}
