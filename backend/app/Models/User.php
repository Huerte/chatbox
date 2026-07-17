<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'is_admin'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function chats() {
        return $this->hasMany(Chats::class, 'sender_id');
    }

    public function friendshipsSent() {
        return $this->hasMany(Friendship::class, 'sender_id');
    }

    public function friendshipsReceived() {
        return $this->hasMany(Friendship::class, 'receiver_id');
    }

    public function groups() {
        return $this->belongsToMany(Group::class, 'group_members', 'user_id', 'group_id')->withTimestamps();
    }

    public function friendIds() {
        $sent = $this->friendshipsSent()->where('status', 'accepted')->pluck('receiver_id')->toArray();
        $received = $this->friendshipsReceived()->where('status', 'accepted')->pluck('sender_id')->toArray();
        return array_unique(array_merge($sent, $received));
    }

    public function friends() {
        return User::whereIn('id', $this->friendIds())->get();
    }

    public function isFriendWith($userId) {
        return in_array($userId, $this->friendIds());
    }

}
