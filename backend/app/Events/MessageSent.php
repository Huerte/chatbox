<?php

namespace App\Events;

use App\Models\Chats;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;

    public function __construct(Chats $chat)
    {
        $this->chat = $chat;
    }

    public function broadcastOn(): array
    {
        if ($this->chat->group_id) {
            return [
                new PrivateChannel('chat-group.' . $this->chat->group_id),
            ];
        }

        return [
            new PrivateChannel('chat.' . $this->chat->receiver_id),
        ];
    }
}
