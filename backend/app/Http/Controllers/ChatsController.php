<?php

namespace App\Http\Controllers;

use App\Models\Chats;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    public function store(Request $request) {
        
        $validated = $request->validate([
            'message' => ['required', 'max:500'],
        ]);

        Chats::create([
            'user_id' => auth()->id,
            'message' => $validated['message']
        ]);

        return redirect('/');

    }

    public function destroy(Chats $chats) {
        $chats->delete();
    }
}
