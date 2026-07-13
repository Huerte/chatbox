<?php

namespace App\Http\Controllers;

use App\Models\Chats;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    
    public function index() {
        $chats = Chats::with('user')->orderBy('created_at', 'asc')  ->get();

        return view('chat.index', ['chats' => $chats]);
    }

    public function store(Request $request) {
        
        $validated = $request->validate([
            'message' => ['required', 'max:500'],
        ]);

        Chats::create([
            'user_id' => auth()->id(),
            'message' => $validated['message']
        ]);

        return redirect()->route('chat.index');

    }

    public function destroy(Chats $chat) {
        abort_if($chat->user_id !== auth()->id(), 403);
        
        $chat->delete();

        return redirect()->route('chat.index');
    }
}
