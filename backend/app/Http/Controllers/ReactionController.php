<?php

namespace App\Http\Controllers;

use App\Events\ReactionUpdated;
use App\Models\Chats;
use App\Models\Reaction;
use Illuminate\Http\Request;

class ReactionController extends Controller
{
    public function toggle(Request $request, Chats $chat)
    {
        $validated = $request->validate([
            'emoji' => 'required|string'
        ]);

        $userId = auth()->id();
        $emoji = $validated['emoji'];

        $existing = Reaction::where('chat_id', $chat->id)
            ->where('user_id', $userId)
            ->where('emoji', $emoji)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            Reaction::create([
                'chat_id' => $chat->id,
                'user_id' => $userId,
                'emoji' => $emoji
            ]);
        }

        // Get updated grouped reactions
        $reactions = Reaction::where('chat_id', $chat->id)
            ->selectRaw('emoji, count(*) as count')
            ->groupBy('emoji')
            ->get();

        // Broadcast to receiver
        $receiverId = $chat->sender_id === $userId ? $chat->receiver_id : $chat->sender_id;
        broadcast(new ReactionUpdated($chat->id, $reactions, $receiverId))->toOthers();

        return response()->json([
            'status' => 'success',
            'reactions' => $reactions
        ]);
    }
}
