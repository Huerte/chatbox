<?php

namespace App\Http\Controllers;

use App\Models\Chats;
use App\Models\User;
use App\Models\Group;
use App\Models\Friendship;
use App\Models\ChatDeletion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatsController extends Controller
{
    public function index()
    {
        $friends = auth()->user()->friends();
        $groups = auth()->user()->groups()->with('members')->get();

        $pendingRequests = Friendship::with('sender')
            ->where('receiver_id', auth()->id())
            ->where('status', 'pending')
            ->get();

        return view('chat.index', [
            'users' => $friends,
            'groups' => $groups,
            'receiver' => null,
            'messages' => collect(),
            'pendingRequests' => $pendingRequests,
        ]);
    }

    public function show(User $receiver)
    {
        if (!auth()->user()->isFriendWith($receiver->id)) {
            return redirect()->route('chat.index')->with('error', 'You can only chat with friends.');
        }

        $friends = auth()->user()->friends();
        $groups = auth()->user()->groups()->with('members')->get();

        $pendingRequests = Friendship::with('sender')
            ->where('receiver_id', auth()->id())
            ->where('status', 'pending')
            ->get();

        // Fetch messages that are NOT deleted for me
        $deletions = ChatDeletion::where('user_id', auth()->id())->pluck('chat_id');

        $messages = Chats::with(['sender', 'reactions', 'replyTo.sender'])
            ->betweenUsers(auth()->id(), $receiver->id)
            ->whereNotIn('id', $deletions)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('chat.index', [
            'users' => $friends,
            'groups' => $groups,
            'receiver' => $receiver,
            'messages' => $messages,
            'pendingRequests' => $pendingRequests,
        ]);
    }

    public function store(Request $request, User $receiver)
    {
        if (!auth()->user()->isFriendWith($receiver->id)) {
            return response()->json(['error' => 'You can only chat with friends.'], 403);
        }

        $validated = $request->validate([
            'message' => 'nullable|string|max:500',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:10240', // 10MB limit
            'reply_to_id' => 'nullable|exists:chats,id',
            'is_forwarded' => 'nullable|boolean',
        ]);

        if (empty($validated['message']) && !$request->hasFile('attachment')) {
            return response()->json(['error' => 'Message or attachment is required.'], 422);
        }

        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = strtolower($file->getClientOriginalExtension());
            $attachmentType = ($extension === 'gif') ? 'gif' : 'image';
            
            // Store file on public disk
            $path = $file->store('attachments', 'public');
            $attachmentPath = Storage::url($path);
        }

        $chat = Chats::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver->id,
            'message' => $validated['message'] ?? '',
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
            'reply_to_id' => $validated['reply_to_id'] ?? null,
            'is_forwarded' => $validated['is_forwarded'] ?? false,
        ]);

        // Load reply info if present
        $chat->load(['sender', 'reactions', 'replyTo.sender']);

        broadcast(new \App\Events\MessageSent($chat))->toOthers();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'chat' => $chat
            ]);
        }

        return back();
    }

    public function storeGroup(Request $request, Group $group)
    {
        if (!$group->members()->where('user_id', auth()->id())->exists()) {
            return response()->json(['error' => 'You are not a member of this group.'], 403);
        }

        $validated = $request->validate([
            'message' => 'nullable|string|max:500',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:10240',
            'reply_to_id' => 'nullable|exists:chats,id',
            'is_forwarded' => 'nullable|boolean',
        ]);

        if (empty($validated['message']) && !$request->hasFile('attachment')) {
            return response()->json(['error' => 'Message or attachment is required.'], 422);
        }

        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = strtolower($file->getClientOriginalExtension());
            $attachmentType = ($extension === 'gif') ? 'gif' : 'image';
            
            $path = $file->store('attachments', 'public');
            $attachmentPath = Storage::url($path);
        }

        $chat = Chats::create([
            'sender_id' => auth()->id(),
            'group_id' => $group->id,
            'message' => $validated['message'] ?? '',
            'attachment_path' => $attachmentPath,
            'attachment_type' => $attachmentType,
            'reply_to_id' => $validated['reply_to_id'] ?? null,
            'is_forwarded' => $validated['is_forwarded'] ?? false,
        ]);

        $chat->load(['sender', 'reactions', 'replyTo.sender']);

        broadcast(new \App\Events\MessageSent($chat))->toOthers();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'chat' => $chat
            ]);
        }

        return back();
    }

    public function hide(Chats $chat)
    {
        // Delete for me
        ChatDeletion::firstOrCreate([
            'chat_id' => $chat->id,
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', 'Message removed for you.');
    }

    public function destroy(Chats $chat)
    {
        // Only sender can delete for everyone
        abort_if($chat->sender_id !== auth()->id(), 403);
        
        $chat->delete();

        return redirect()->route('chat.index')->with('success', 'Message deleted for everyone.');
    }
}
