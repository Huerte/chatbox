<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\Request;

class FriendshipController extends Controller
{
    public function sendRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $receiver = User::where('email', $request->email)->first();

        if ($receiver->id === auth()->id()) {
            return back()->with('error', 'You cannot send a friend request to yourself.');
        }

        // Check if friendship already exists
        $existing = Friendship::where(function ($q) use ($receiver) {
            $q->where('sender_id', auth()->id())->where('receiver_id', $receiver->id);
        })->orWhere(function ($q) use ($receiver) {
            $q->where('sender_id', $receiver->id)->where('receiver_id', auth()->id());
        })->first();

        if ($existing) {
            if ($existing->status === 'accepted') {
                return back()->with('error', 'You are already friends.');
            }
            return back()->with('error', 'Friend request is already pending.');
        }

        Friendship::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver->id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Friend request sent successfully!');
    }

    public function acceptRequest(Friendship $friendship)
    {
        abort_if($friendship->receiver_id !== auth()->id(), 403);

        $friendship->update(['status' => 'accepted']);

        return back()->with('success', 'Friend request accepted!');
    }

    public function declineRequest(Friendship $friendship)
    {
        abort_if($friendship->receiver_id !== auth()->id() && $friendship->sender_id !== auth()->id(), 403);

        $friendship->delete();

        return back()->with('success', 'Friend request removed.');
    }
}
