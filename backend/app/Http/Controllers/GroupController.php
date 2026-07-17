<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $group = Group::create([
            'name' => $validated['name'],
            'creator_id' => auth()->id(),
        ]);

        // Automatically join group creator
        $group->members()->attach(auth()->id());

        // Attach friends
        if (!empty($validated['members'])) {
            $friendIds = auth()->user()->friendIds();
            $validMembers = array_intersect($validated['members'], $friendIds);
            $group->members()->attach($validMembers);
        }

        return redirect()->route('group.show', $group)->with('success', 'Group Chat created successfully!');
    }

    public function addMember(Request $request, Group $group)
    {
        abort_if($group->creator_id !== auth()->id(), 403, 'Only the group creator can add members.');

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userId = $validated['user_id'];

        if (!auth()->user()->isFriendWith($userId)) {
            return back()->with('error', 'You can only add friends to this group.');
        }

        if ($group->members()->where('user_id', $userId)->exists()) {
            return back()->with('error', 'User is already a member.');
        }

        $group->members()->attach($userId);

        return back()->with('success', 'Member added successfully!');
    }

    public function show(Group $group)
    {
        // Authorize member access
        if (!$group->members()->where('user_id', auth()->id())->exists()) {
            abort(403, 'You are not a member of this group.');
        }

        // Fetch messages that are NOT deleted for me
        $deletions = \App\Models\ChatDeletion::where('user_id', auth()->id())->pluck('chat_id');

        $messages = \App\Models\Chats::with(['sender', 'reactions', 'replyTo.sender'])
            ->where('group_id', $group->id)
            ->whereNotIn('id', $deletions)
            ->orderBy('created_at', 'asc')
            ->get();

        $friends = auth()->user()->friends();
        $groups = auth()->user()->groups()->with('members')->get();

        // Pending friend requests received
        $pendingRequests = Friendship::with('sender')
            ->where('receiver_id', auth()->id())
            ->where('status', 'pending')
            ->get();

        return view('chat.index', [
            'users' => $friends,
            'groups' => $groups,
            'receiver' => null,
            'group' => $group,
            'messages' => $messages,
            'pendingRequests' => $pendingRequests,
        ]);
    }
}
