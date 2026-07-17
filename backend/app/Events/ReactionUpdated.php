<?php

namespace App\Events;

use App\Models\Chats;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReactionUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat_id;
    public $reactions;
    public $receiver_id;

    /**
     * Create a new event instance.
     */
    public function __construct($chat_id, $reactions, $receiver_id)
    {
        $this->chat_id = $chat_id;
        $this->reactions = $reactions;
        $this->receiver_id = $receiver_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->receiver_id),
        ];
    }
}
