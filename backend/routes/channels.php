<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat-group.{groupId}', function ($user, $groupId) {
    return \App\Models\Group::find($groupId)?->members()->where('user_id', $user->id)->exists();
});
