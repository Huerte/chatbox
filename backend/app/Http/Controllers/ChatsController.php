<?php

namespace App\Http\Controllers;

use App\Models\Chats;
use App\Models\User;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    
    public function index() {
        $users = User::all()->except(auth()->id());
        return view('chat.index', [
            'users' => $users,
            'receiver' => null,
            'messages' => collect()
        ]);
    }

    public function store(Request $request, User $receiver) {
        
        $validated = $request->validate([
            'message' => ['required', 'max:500'],
        ]);

        Chats::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver->id,
            'message' => $validated['message']
        ]);

        return back();

    }

    public function show(User $receiver) {

        $currentUserId = auth()->id();

        $messages = Chats::betweenUsers($currentUserId, $receiver->id)->orderBy('created_at', 'asc')->get();
        $users = User::all()->except(auth()->id());

        return view('chat.index', [
            'users' => $users,
            'receiver' => $receiver,
            'messages' => $messages,
        ]);

    }

    public function destroy(Chats $chat) {
        abort_if($chat->user_id !== auth()->id(), 403);
        
        $chat->delete();

        return redirect()->route('chat.index');
    }
}
