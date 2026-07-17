<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chats;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Metrics
        $totalUsers = User::count();
        $totalChats = Chats::count();
        $totalAdmins = User::where('is_admin', true)->count();
        $chatsToday = Chats::whereDate('created_at', today())->count();

        // Search Users
        $userQuery = User::latest();
        if ($request->has('search_user')) {
            $searchTerm = $request->search_user;
            $userQuery->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
        $users = $userQuery->paginate(10)->withQueryString();

        // Search Chats
        $chatQuery = Chats::with(['sender', 'receiver'])->latest();
        if ($request->has('search_chat')) {
            $searchTerm = $request->search_chat;
            $chatQuery->where('message', 'like', "%{$searchTerm}%")
                      ->orWhereHas('sender', function($q) use ($searchTerm) {
                          $q->where('name', 'like', "%{$searchTerm}%");
                      })
                      ->orWhereHas('receiver', function($q) use ($searchTerm) {
                          $q->where('name', 'like', "%{$searchTerm}%");
                      });
        }
        $chats = $chatQuery->paginate(10)->withQueryString();
        
        return view('admin.index', compact(
            'users', 'chats', 'totalUsers', 'totalChats', 'totalAdmins', 'chatsToday'
        ));
    }

    public function toggleAdmin(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        $status = $user->is_admin ? 'promoted to Admin' : 'demoted to User';
        return back()->with('success', "{$user->name} has been {$status}.");
    }

    public function destroyUser(User $user)
    {
        // Don't allow super admin to delete themselves
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        // Delete all chats related to user
        Chats::where('sender_id', $user->id)->orWhere('receiver_id', $user->id)->delete();
        $user->delete();

        return back()->with('success', 'User and all related chats deleted successfully.');
    }

    public function destroyChat(Chats $chat)
    {
        $chat->delete();
        return back()->with('success', 'Chat message deleted successfully.');
    }
}
