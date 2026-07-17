<x-layout>
<div class="flex h-screen overflow-hidden bg-slate-950 font-sans text-slate-100" x-data="{
    sidebarOpen: true,
    activeTab: 'contacts', // contacts, groups, requests
    replyingTo: null, // null or { id, name, text }
    forwardingMessage: null, // null or { id, text }
    showAddFriend: false,
    showCreateGroup: false,
    showAddMember: false,
    isGroupChat: {{ isset($group) ? 'true' : 'false' }}
}">
    
    {{-- SIDEBAR DRAWER --}}
    <aside class="bg-slate-900 border-r border-slate-800 flex flex-col transition-all duration-300 z-30"
           :class="sidebarOpen ? 'w-80' : 'w-0 overflow-hidden border-r-0'"
           x-show="sidebarOpen"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="transform -translate-x-full opacity-0"
           x-transition:enter-end="transform translate-x-0 opacity-100"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="transform translate-x-0 opacity-100"
           x-transition:leave-end="transform -translate-x-full opacity-0">
        
        {{-- Header --}}
        <div class="p-4 border-b border-slate-800 flex items-center justify-between">
            <h1 class="text-lg font-bold text-slate-50 flex items-center gap-2">
                <i data-lucide="message-square" class="w-5 h-5 text-blue-500 stroke-[1.5]"></i>
                ChatBox
            </h1>
            <button @click="sidebarOpen = false" class="p-2 text-slate-400 hover:text-blue-400 hover:bg-slate-800 rounded-lg transition-colors" title="Close Sidebar">
                <i data-lucide="panel-left-close" class="w-4 h-4 stroke-[1.5]"></i>
            </button>
        </div>

        {{-- Sidebar Tabs --}}
        <div class="flex border-b border-slate-800 text-xs">
            <button @click="activeTab = 'contacts'" :class="activeTab === 'contacts' ? 'border-b-2 border-blue-500 text-blue-400 font-semibold' : 'text-slate-400'" class="flex-1 py-3 text-center transition-colors hover:text-slate-200">
                Friends
            </button>
            <button @click="activeTab = 'groups'" :class="activeTab === 'groups' ? 'border-b-2 border-blue-500 text-blue-400 font-semibold' : 'text-slate-400'" class="flex-1 py-3 text-center transition-colors hover:text-slate-200">
                Groups
            </button>
            <button @click="activeTab = 'requests'" :class="activeTab === 'requests' ? 'border-b-2 border-blue-500 text-blue-400 font-semibold' : 'text-slate-400'" class="flex-1 py-3 text-center transition-colors hover:text-slate-200 relative">
                Requests
                @if(isset($pendingRequests) && $pendingRequests->count() > 0)
                    <span class="absolute top-2 right-4 w-2 h-2 bg-rose-500 rounded-full"></span>
                @endif
            </button>
        </div>

        {{-- Search (Contextual) --}}
        <div class="p-4">
            <div class="relative flex items-center">
                <i data-lucide="search" class="absolute left-3 w-4 h-4 text-slate-500 stroke-[1.5]"></i>
                <input type="text" id="search-sidebar" placeholder="Search..." class="w-full bg-slate-950 border border-slate-800 rounded-lg py-2 pl-9 pr-4 text-sm text-slate-50 placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
            </div>
        </div>

        {{-- Tab content --}}
        <div class="flex-1 overflow-y-auto p-2 scrollbar-thin">
            
            {{-- TAB: CONTACTS (FRIENDS LIST) --}}
            <div x-show="activeTab === 'contacts'">
                <div class="flex justify-between items-center px-2 pb-2">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Friends</span>
                    <button @click="showAddFriend = true" class="text-xs text-blue-400 hover:text-blue-300 font-medium flex items-center gap-1">
                        <i data-lucide="user-plus" class="w-3.5 h-3.5"></i> Add Friend
                    </button>
                </div>
                <ul class="space-y-1">
                    @forelse ($users as $user)
                        @php
                            $isActive = isset($receiver) && $receiver->id === $user->id;
                        @endphp
                        <li class="contact-item" data-name="{{ $user->name }}">
                            <a href="{{ route('chat.show', $user->id) }}" class="flex items-center gap-3 p-2 rounded-lg transition-colors {{ $isActive ? 'bg-blue-600/10 border-l-2 border-blue-500' : 'hover:bg-slate-800/40 border-l-2 border-transparent' }}">
                                <div class="relative flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-sm font-semibold text-slate-300">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-slate-900"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="text-sm font-medium text-slate-50 truncate block">{{ $user->name }}</span>
                                    <p class="text-[11px] text-slate-500 truncate">Online</p>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="p-4 text-center text-xs text-slate-500">Add friends to start chatting</li>
                    @endforelse
                </ul>
            </div>

            {{-- TAB: GROUPS --}}
            <div x-show="activeTab === 'groups'" style="display: none;">
                <div class="flex justify-between items-center px-2 pb-2">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Group Chats</span>
                    <button @click="showCreateGroup = true" class="text-xs text-blue-400 hover:text-blue-300 font-medium flex items-center gap-1">
                        <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i> Create Group
                    </button>
                </div>
                <ul class="space-y-1">
                    @forelse ($groups ?? [] as $gp)
                        @php
                            $isActive = isset($group) && $group->id === $gp->id;
                        @endphp
                        <li class="group-item" data-name="{{ $gp->name }}">
                            <a href="{{ route('group.show', $gp->id) }}" class="flex items-center gap-3 p-2 rounded-lg transition-colors {{ $isActive ? 'bg-blue-600/10 border-l-2 border-blue-500' : 'hover:bg-slate-800/40 border-l-2 border-transparent' }}">
                                <div class="w-10 h-10 rounded-full bg-indigo-950/60 border border-indigo-500/20 flex items-center justify-center text-sm font-semibold text-indigo-400 flex-shrink-0">
                                    <i data-lucide="users" class="w-5 h-5"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <span class="text-sm font-medium text-slate-50 truncate block">{{ $gp->name }}</span>
                                    <p class="text-[11px] text-slate-500 truncate">{{ $gp->members->count() }} members</p>
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="p-4 text-center text-xs text-slate-500">Create or join a group chat</li>
                    @endforelse
                </ul>
            </div>

            {{-- TAB: REQUESTS --}}
            <div x-show="activeTab === 'requests'" style="display: none;">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider px-2 block pb-2">Pending Requests</span>
                <ul class="space-y-2 px-1">
                    @forelse($pendingRequests ?? [] as $req)
                        <li class="p-3 bg-slate-950/50 rounded-xl border border-slate-800 flex flex-col gap-2">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-300">
                                    {{ strtoupper(substr($req->sender->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <span class="text-xs font-medium text-slate-200 block truncate">{{ $req->sender->name }}</span>
                                    <span class="text-[10px] text-slate-500 block truncate">{{ $req->sender->email }}</span>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <form action="{{ route('friend.accept', $req) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-1 text-center bg-blue-600 hover:bg-blue-500 text-white rounded text-[11px] font-medium transition-colors">
                                        Accept
                                    </button>
                                </form>
                                <form action="{{ route('friend.decline', $req) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-1 text-center bg-slate-800 hover:bg-slate-700 text-slate-300 rounded text-[11px] font-medium transition-colors">
                                        Decline
                                    </button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="p-4 text-center text-xs text-slate-500">No pending friend requests</li>
                    @endforelse
                </ul>
            </div>

        </div>

        {{-- Profile Bottom --}}
        <div class="border-t border-slate-800 bg-slate-950 relative">
            <button id="profile-trigger" type="button" class="w-full p-4 flex items-center gap-3 hover:bg-slate-900/50 transition-colors">
                <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="text-sm flex-1 text-left min-w-0">
                    <p class="font-semibold text-slate-50 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-[11px] text-green-500 flex items-center gap-1 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block animate-pulse"></span>
                        Online
                    </p>
                </div>
                <i data-lucide="chevrons-up-down" class="w-4 h-4 text-slate-500 flex-shrink-0"></i>
            </button>
        </div>

        {{-- Profile Dropdown --}}
        <div id="profile-menu" class="hidden fixed z-[9999] w-72 bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl overflow-hidden" style="bottom: 80px; left: 12px;">
            <div class="flex items-center gap-3 px-4 py-3.5 border-b border-slate-800 bg-slate-950">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-sm font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-50 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </div>
            <div class="py-1.5">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-800 hover:text-slate-50 transition-colors">
                    <div class="w-7 h-7 rounded-lg bg-slate-800 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="user-pen" class="w-3.5 h-3.5 text-blue-400"></i>
                    </div>
                    Change Profile
                </a>
                @if(auth()->user()->is_admin)
                <a href="{{ route('admin.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-blue-400 hover:bg-blue-500/10 hover:text-blue-300 transition-colors">
                    <div class="w-7 h-7 rounded-lg bg-blue-500/10 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="shield" class="w-3.5 h-3.5 text-blue-400"></i>
                    </div>
                    Admin Dashboard
                </a>
                @endif
            </div>
            <div class="border-t border-slate-800 py-1.5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 transition-colors text-left">
                        <div class="w-7 h-7 rounded-lg bg-rose-500/10 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="log-out" class="w-3.5 h-3.5 text-rose-400"></i>
                        </div>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- MAIN CHAT AREA --}}
    <main class="flex-1 flex flex-col min-w-0 bg-slate-950 relative">
        
        {{-- Open Sidebar Drawer button when closed --}}
        <div class="absolute top-4 left-4 z-40" x-show="!sidebarOpen">
            <button @click="sidebarOpen = true" class="p-2.5 bg-slate-900 border border-slate-800 text-slate-300 hover:text-white rounded-xl shadow-xl hover:bg-slate-800 transition-all flex items-center justify-center" title="Open Sidebar">
                <i data-lucide="panel-left-open" class="w-5 h-5 stroke-[1.5]"></i>
            </button>
        </div>

        @if(!isset($receiver) && !isset($group))
            {{-- Empty State --}}
            <div class="flex-1 flex flex-col items-center justify-center text-slate-500 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-opacity-5">
                <div class="w-16 h-16 rounded-3xl bg-slate-900 border border-slate-800/80 flex items-center justify-center mb-4 shadow-xl">
                    <i data-lucide="message-square" class="w-8 h-8 text-blue-500 stroke-[1.5]"></i>
                </div>
                <p class="text-sm font-medium text-slate-400">Select a contact or group to start chatting</p>
                <p class="text-xs text-slate-600 mt-1">Or add a friend to expand your contacts</p>
            </div>
        @else
            {{-- Chat Header --}}
            <header class="h-16 px-6 border-b border-slate-800/60 flex items-center justify-between flex-shrink-0 bg-slate-900/40 backdrop-blur-md relative z-10" :class="!sidebarOpen ? 'pl-20' : ''">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        @if(isset($group))
                            <div class="w-10 h-10 rounded-full bg-indigo-900/50 border border-indigo-500/20 flex items-center justify-center text-sm font-semibold text-indigo-400">
                                <i data-lucide="users" class="w-5 h-5"></i>
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-sm font-semibold text-slate-300">
                                {{ strtoupper(substr($receiver->name, 0, 1)) }}
                            </div>
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-slate-950"></span>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-50">{{ isset($group) ? $group->name : $receiver->name }}</h2>
                        <p class="text-[11px] text-slate-500">
                            @if(isset($group))
                                Group Chat • {{ $group->members->count() }} members
                            @else
                                Active now
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @if(isset($group) && $group->creator_id === auth()->id())
                        <button @click="showAddMember = true" class="p-2 text-slate-400 hover:text-blue-400 hover:bg-slate-800 rounded-lg transition-colors flex items-center gap-1 text-xs" title="Add members to group">
                            <i data-lucide="user-plus" class="w-4 h-4"></i> Add Member
                        </button>
                    @endif
                    <div class="w-px h-6 bg-slate-800"></div>
                    <button id="info-toggle" class="text-slate-400 hover:text-slate-200 transition-colors" title="Information">
                        <i data-lucide="info" class="w-5 h-5 stroke-[1.5]"></i>
                    </button>
                </div>
            </header>

            {{-- Messages Area --}}
            <div id="messages-container" class="flex-1 overflow-y-auto p-6 flex flex-col gap-6 scrollbar-thin">
                @forelse ($messages as $chat)
                    @php
                        $isOutgoing = $chat->sender_id === auth()->id();
                    @endphp
                    <div class="flex {{ $isOutgoing ? 'justify-end' : 'justify-start' }} message-row group relative" data-id="{{ $chat->id }}">
                        <div class="flex flex-col {{ $isOutgoing ? 'items-end' : 'items-start' }} max-w-[70%] relative">
                            
                            {{-- Sender Name tag for Group Chats --}}
                            @if(isset($group) && !$isOutgoing)
                                <span class="text-xs text-indigo-400 font-semibold mb-1 ml-1">{{ $chat->sender->name ?? 'Unknown Sender' }}</span>
                            @endif

                            {{-- Forwarded tag --}}
                            @if($chat->is_forwarded)
                                <span class="text-[10px] text-slate-500 italic mb-1 flex items-center gap-1">
                                    <i data-lucide="forward" class="w-3 h-3"></i> Forwarded
                                </span>
                            @endif

                            <div class="flex items-end gap-2 {{ $isOutgoing ? 'flex-row-reverse' : 'flex-row' }} relative">
                                
                                {{-- HOVER ACTIONS BUTTON --}}
                                <div class="absolute {{ $isOutgoing ? '-left-16' : '-right-16' }} top-1/2 -translate-y-1/2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity z-20">
                                    <button @click="replyingTo = { id: {{ $chat->id }}, name: '{{ $chat->sender->name }}', text: '{{ addslashes($chat->message) }}' }" class="p-1.5 bg-slate-800 text-slate-400 hover:text-white rounded-md border border-slate-700" title="Reply">
                                        <i data-lucide="reply" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button @click="forwardingMessage = { id: {{ $chat->id }}, text: '{{ addslashes($chat->message) }}' }" class="p-1.5 bg-slate-800 text-slate-400 hover:text-white rounded-md border border-slate-700" title="Forward">
                                        <i data-lucide="forward" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>

                                {{-- MESSAGE BUBBLE --}}
                                <div class="shadow-sm relative {{ $isOutgoing ? 'bg-blue-600 text-white rounded-2xl rounded-br-sm' : 'bg-slate-900 text-slate-50 rounded-2xl rounded-bl-sm border border-slate-800/80' }}">
                                    
                                    {{-- QUOTE/REPLY PREVIEW IN BUBBLE --}}
                                    @if($chat->reply_to_id && $chat->replyTo)
                                        <div class="border-l-2 border-blue-500 bg-slate-950/40 p-2 rounded-lg mx-2.5 mt-2.5 text-xs text-slate-400">
                                            <strong class="text-[11px] text-slate-300">{{ $chat->replyTo->sender->name ?? 'Unknown' }}</strong>
                                            <p class="truncate">{{ $chat->replyTo->message }}</p>
                                        </div>
                                    @endif

                                    <div class="px-4 py-2.5">
                                        {{-- Image / GIF attachment rendering --}}
                                        @if($chat->attachment_path)
                                            <div class="mb-2">
                                                <img src="{{ $chat->attachment_path }}" class="max-w-xs rounded-lg border border-slate-800/50 shadow-md">
                                            </div>
                                        @endif

                                        @if($chat->message)
                                            <p class="text-sm leading-relaxed whitespace-pre-wrap emoji-render">{{ $chat->message }}</p>
                                        @endif
                                    </div>
                                    
                                    {{-- HOVER EMOJI REACTION POPUP (shows on hover of the bubble) --}}
                                    <div class="absolute left-0 bottom-full mb-1 bg-slate-900 border border-slate-700/80 rounded-full px-2 py-1 shadow-2xl opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 transition-all duration-150 flex gap-1 z-30 pointer-events-auto">
                                        @foreach([
                                            '👍' => '1f44d.svg',
                                            '❤️' => '2764.svg',
                                            '😂' => '1f602.svg',
                                            '😮' => '1f62e.svg',
                                            '🙏' => '1f64f.svg',
                                            '😢' => '1f622.svg',
                                            '🎉' => '1f389.svg'
                                        ] as $emoji => $svg)
                                            <button type="button" class="quick-react-btn p-1 rounded-full hover:bg-slate-800 hover:scale-125 transition-transform" data-emoji="{{ $emoji }}">
                                                <img src="https://cdn.jsdelivr.net/npm/@twemoji/api@14.1.0/assets/svg/{{ $svg }}" class="w-5 h-5">
                                            </button>
                                        @endforeach
                                        
                                        {{-- Action dropdown trigger --}}
                                        <div class="w-px h-5 bg-slate-700 self-center mx-1"></div>
                                        
                                        {{-- Delete for me --}}
                                        <form action="{{ route('chat.hide', $chat) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-1 rounded text-slate-400 hover:text-slate-100 hover:bg-slate-800" title="Delete for me">
                                                <i data-lucide="eye-off" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>

                                        {{-- Delete for everyone (if sender) --}}
                                        @if($isOutgoing)
                                            <form action="{{ route('chat.destroy', $chat) }}" method="POST" class="inline" onsubmit="return confirm('Delete this message for everyone?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 rounded text-rose-400 hover:text-rose-300 hover:bg-slate-800" title="Delete for everyone">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Reactions Badge list --}}
                            <div class="reactions-list flex flex-wrap gap-1 mt-1.5 {{ $isOutgoing ? 'self-end mr-2' : 'self-start ml-2' }}">
                                @php
                                    $grouped = $chat->reactions->groupBy('emoji');
                                    $emojiSvgs = [
                                        '👍' => '1f44d.svg',
                                        '❤️' => '2764.svg',
                                        '😂' => '1f602.svg',
                                        '😮' => '1f62e.svg',
                                        '🙏' => '1f64f.svg',
                                        '😢' => '1f622.svg',
                                        '🎉' => '1f389.svg'
                                    ];
                                @endphp
                                @foreach($grouped as $emoji => $reactions)
                                    <button type="button" data-reaction="{{ $emoji }}" class="flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] bg-slate-900 border border-slate-800 text-slate-300 hover:border-blue-500/50 hover:bg-slate-850 transition-all select-none cursor-pointer">
                                        <img src="https://cdn.jsdelivr.net/npm/@twemoji/api@14.1.0/assets/svg/{{ $emojiSvgs[$emoji] ?? '1f600.svg' }}" class="w-3.5 h-3.5">
                                        <span class="font-semibold text-slate-400">{{ $reactions->count() }}</span>
                                    </button>
                                @endforeach
                            </div>

                            <span class="text-[10px] text-slate-500 mt-1 {{ $isOutgoing ? 'mr-2' : 'ml-2' }}">{{ $chat->created_at->format('h:i A') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="flex-1 flex flex-col items-center justify-center text-slate-600">
                        <p class="text-sm">No messages yet. Send a wave! 👋</p>
                    </div>
                @endforelse
            </div>

            {{-- Input Area --}}
            <div class="p-4 border-t border-slate-800 bg-slate-900/50 flex-shrink-0 relative">
                
                {{-- Reply Bar Preview --}}
                <div x-show="replyingTo" class="flex items-center justify-between bg-slate-900 border-l-4 border-blue-500 rounded-r-lg p-3 mb-3 text-xs" style="display: none;">
                    <div class="flex-1 min-w-0">
                        <span class="font-bold text-blue-400 block">Replying to <span x-text="replyingTo ? replyingTo.name : ''"></span></span>
                        <p class="text-slate-400 truncate mt-0.5" x-text="replyingTo ? replyingTo.text : ''"></p>
                    </div>
                    <button @click="replyingTo = null" class="p-1 text-slate-500 hover:text-slate-200">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                {{-- Attachment preview --}}
                <div id="attachment-preview" class="hidden flex items-center gap-2 mb-3 bg-slate-900 border border-slate-800 rounded-lg p-2.5 text-xs text-slate-300">
                    <i data-lucide="paperclip" class="w-4 h-4 text-blue-500"></i>
                    <span id="attachment-name" class="flex-1 truncate">file.txt</span>
                    <button type="button" id="remove-attachment" class="text-slate-400 hover:text-slate-200">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <form id="chat-form" method="POST" action="{{ isset($group) ? route('group.chat', $group->id) : route('chat.store', $receiver->id) }}" class="flex items-end gap-3" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- File attachment input (hidden) --}}
                    <input type="file" name="attachment" id="file-input" class="hidden" accept="image/jpeg,image/png,image/jpg,image/gif">
                    
                    {{-- Reply context inputs --}}
                    <input type="hidden" name="reply_to_id" :value="replyingTo ? replyingTo.id : ''">

                    <div class="flex-1 flex items-end gap-2 bg-slate-950 border border-slate-800 rounded-xl px-3 py-2.5 focus-within:border-blue-500/50 transition-colors">
                        <button type="button" id="attach-file-btn" class="p-1.5 text-slate-400 hover:text-blue-500 transition-colors flex-shrink-0" title="Attach Image or GIF">
                            <i data-lucide="paperclip" class="w-5 h-5 stroke-[1.5]"></i>
                        </button>
                        
                        <textarea 
                            id="message-textarea"
                            name="message" 
                            rows="1" 
                            placeholder="Type your message..." 
                            class="flex-1 bg-transparent border-0 text-sm text-slate-50 placeholder-slate-500 focus:ring-0 resize-none py-1.5 px-1 max-h-32 scrollbar-thin outline-none"
                            oninput="this.style.height = ''; this.style.height = Math.min(this.scrollHeight, 128) + 'px';"
                        ></textarea>
                        
                        {{-- Quick Emoji drawer trigger (standard unicode emojis inside bubble input) --}}
                        <div x-data="{ open: false }" class="relative flex-shrink-0">
                            <button type="button" @click="open = !open" class="p-1.5 text-slate-400 hover:text-blue-500 transition-colors" title="Emoji">
                                <i data-lucide="smile" class="w-5 h-5 stroke-[1.5]"></i>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute bg-slate-950 border border-slate-800 rounded-xl p-3 shadow-2xl z-40" style="bottom: 100%; right: 0; width: 280px; display: none;">
                                <div class="grid grid-cols-7 gap-1">
                                    @foreach(['😊','😂','❤️','👍','🔥','✅','🚀','🎉','😎','🤔','👋','💡','⚡','🌟','😍','😭','👀','✨','👏','💯','🙌'] as $emo)
                                        <button type="button" @click="document.getElementById('message-textarea').value += '{{ $emo }}'; open = false;" class="text-xl hover:bg-slate-800 rounded p-1 flex items-center justify-center">{{ $emo }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="p-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition-colors flex-shrink-0 flex items-center justify-center shadow-lg shadow-blue-600/20" title="Send message">
                        <i data-lucide="send-horizonal" class="w-5 h-5 stroke-[1.5]"></i>
                    </button>
                </form>
            </div>
        @endif
    </main>

    {{-- MODAL: FORWARD MESSAGE --}}
    <div x-show="forwardingMessage" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="forwardingMessage = null"></div>
        <div class="relative w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl">
            <h3 class="text-base font-bold text-slate-100 mb-4 flex items-center gap-2">
                <i data-lucide="forward" class="text-blue-400"></i> Forward Message
            </h3>
            <p class="text-xs text-slate-400 mb-4 bg-slate-950 p-2.5 rounded border border-slate-800 italic" x-text="forwardingMessage ? forwardingMessage.text : ''"></p>
            
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-2">Select Recipient</span>
            <div class="space-y-4 max-h-60 overflow-y-auto pr-1">
                {{-- Friends --}}
                <div class="space-y-1.5">
                    @foreach($users as $friend)
                        <button type="button" @click="forwardMessage({{ $friend->id }}, 'user')" class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-slate-800 text-left transition-colors text-sm">
                            <span class="font-medium text-slate-200">{{ $friend->name }}</span>
                            <span class="text-xs text-slate-500">Friend</span>
                        </button>
                    @endforeach
                    @foreach($groups ?? [] as $gp)
                        <button type="button" @click="forwardMessage({{ $gp->id }}, 'group')" class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-slate-800 text-left transition-colors text-sm">
                            <span class="font-medium text-indigo-400">{{ $gp->name }}</span>
                            <span class="text-xs text-slate-500">Group</span>
                        </button>
                    @endforeach
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 border-t border-slate-800 pt-4">
                <button type="button" @click="forwardingMessage = null" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL: ADD FRIEND --}}
    <div x-show="showAddFriend" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showAddFriend = false"></div>
        <form action="{{ route('friend.request') }}" method="POST" class="relative w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl">
            @csrf
            <h3 class="text-base font-bold text-slate-100 mb-4 flex items-center gap-2">
                <i data-lucide="user-plus" class="text-blue-400"></i> Add New Friend
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                    <input type="email" name="email" placeholder="Enter friend's email address..." required 
                           class="w-full bg-slate-950 border border-slate-800 rounded-lg py-2.5 px-4 text-sm text-slate-50 placeholder-slate-600 focus:outline-none focus:border-blue-500 transition-colors">
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 border-t border-slate-800 pt-4">
                <button type="button" @click="showAddFriend = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-sm font-medium transition-colors">
                    Send Request
                </button>
            </div>
        </form>
    </div>

    {{-- MODAL: CREATE GROUP CHAT --}}
    <div x-show="showCreateGroup" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showCreateGroup = false"></div>
        <form action="{{ route('group.store') }}" method="POST" class="relative w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl">
            @csrf
            <h3 class="text-base font-bold text-slate-100 mb-4 flex items-center gap-2">
                <i data-lucide="plus-circle" class="text-blue-400"></i> Create Group Chat
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Group Name</label>
                    <input type="text" name="name" placeholder="Enter group name..." required 
                           class="w-full bg-slate-950 border border-slate-800 rounded-lg py-2.5 px-4 text-sm text-slate-50 placeholder-slate-600 focus:outline-none focus:border-blue-500 transition-colors">
                </div>
                
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Select Members (Friends)</label>
                    <div class="space-y-2 max-h-40 overflow-y-auto border border-slate-850 p-2 rounded-lg bg-slate-950/40">
                        @forelse($users as $friend)
                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800 cursor-pointer transition-colors text-sm">
                                <input type="checkbox" name="members[]" value="{{ $friend->id }}" class="rounded bg-slate-900 border-slate-800 text-blue-600 focus:ring-0">
                                <span class="font-medium text-slate-200">{{ $friend->name }}</span>
                            </label>
                        @empty
                            <p class="text-xs text-slate-600 p-2">Add friends to add them to groups.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 border-t border-slate-800 pt-4">
                <button type="button" @click="showCreateGroup = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-sm font-medium transition-colors">
                    Create Group
                </button>
            </div>
        </form>
    </div>

    {{-- MODAL: ADD GROUP MEMBER (CREATOR ONLY) --}}
    @if(isset($group))
    <div x-show="showAddMember" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showAddMember = false"></div>
        <form action="{{ route('group.member', $group->id) }}" method="POST" class="relative w-full max-w-md bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-2xl">
            @csrf
            <h3 class="text-base font-bold text-slate-100 mb-4 flex items-center gap-2">
                <i data-lucide="user-plus" class="text-blue-400"></i> Add Member
            </h3>
            
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Select Friend</label>
                <div class="space-y-2 max-h-40 overflow-y-auto border border-slate-850 p-2 rounded-lg bg-slate-950/40">
                    @forelse($users as $friend)
                        @if(!$group->members->contains($friend->id))
                            <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-800 cursor-pointer transition-colors text-sm">
                                <input type="radio" name="user_id" value="{{ $friend->id }}" class="bg-slate-900 border-slate-800 text-blue-600 focus:ring-0">
                                <span class="font-medium text-slate-200">{{ $friend->name }}</span>
                            </label>
                        @endif
                    @empty
                        <p class="text-xs text-slate-600 p-2">No friends left to add.</p>
                    @endforelse
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 border-t border-slate-800 pt-4">
                <button type="button" @click="showAddMember = false" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-sm font-medium transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-sm font-medium transition-colors">
                    Add Friend
                </button>
            </div>
        </form>
    </div>
    @endif

</div>

{{-- Toast alerts handler --}}
<div class="fixed bottom-6 right-6 z-50 flex flex-col gap-3" x-data="{ show: true }">
    @if(session('success'))
        <div x-show="show" x-init="setTimeout(() => show = false, 5000)" 
             x-transition:leave="transition ease-in duration-200 opacity-0 transform translate-y-2"
             class="bg-slate-900 border border-green-500/30 shadow-2xl rounded-xl p-4 flex items-start gap-3 w-80">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-green-400 mt-0.5"></i>
            <div class="flex-1">
                <h4 class="text-xs font-semibold text-slate-200">Success</h4>
                <p class="text-[11px] text-slate-400 mt-1">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-slate-500 hover:text-slate-300"><i data-lucide="x" class="w-3.5 h-3.5"></i></button>
        </div>
    @endif
    @if(session('error'))
        <div x-show="show" x-init="setTimeout(() => show = false, 5000)" 
             x-transition:leave="transition ease-in duration-200 opacity-0 transform translate-y-2"
             class="bg-slate-900 border border-rose-500/30 shadow-2xl rounded-xl p-4 flex items-start gap-3 w-80">
            <i data-lucide="alert-circle" class="w-5 h-5 text-rose-400 mt-0.5"></i>
            <div class="flex-1">
                <h4 class="text-xs font-semibold text-slate-200">Error</h4>
                <p class="text-[11px] text-slate-400 mt-1">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="text-slate-500 hover:text-slate-300"><i data-lucide="x" class="w-3.5 h-3.5"></i></button>
        </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const textarea = document.getElementById('message-textarea');
        const form = document.getElementById('chat-form');
        const container = document.getElementById('messages-container');
        const fileInput = document.getElementById('file-input');
        const attachFileBtn = document.getElementById('attach-file-btn');
        const attachmentPreview = document.getElementById('attachment-preview');
        const attachmentName = document.getElementById('attachment-name');
        const removeAttachmentBtn = document.getElementById('remove-attachment');

        function scrollToBottom() {
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }

        // Auto Scroll
        scrollToBottom();

        // Sidebar Profile dropdown toggle
        const profileTrigger = document.getElementById('profile-trigger');
        const profileMenu = document.getElementById('profile-menu');
        if (profileTrigger && profileMenu) {
            profileTrigger.addEventListener('click', (e) => {
                e.stopPropagation();
                profileMenu.classList.toggle('hidden');
            });
            document.addEventListener('click', (e) => {
                if (!profileMenu.contains(e.target) && e.target !== profileTrigger) {
                    profileMenu.classList.add('hidden');
                }
            });
        }

        // Attachment file listener
        if (attachFileBtn && fileInput) {
            attachFileBtn.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    attachmentName.textContent = fileInput.files[0].name;
                    attachmentPreview.classList.remove('hidden');
                }
            });
            if (removeAttachmentBtn) {
                removeAttachmentBtn.addEventListener('click', () => {
                    fileInput.value = '';
                    attachmentPreview.classList.add('hidden');
                });
            }
        }

        // Context search in sidebar
        const searchSidebarInput = document.getElementById('search-sidebar');
        if (searchSidebarInput) {
            searchSidebarInput.addEventListener('input', () => {
                const query = searchSidebarInput.value.toLowerCase().trim();
                document.querySelectorAll('.contact-item, .group-item').forEach(item => {
                    const name = item.getAttribute('data-name').toLowerCase();
                    if (name.includes(query)) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });
        }

        // Emoji list builder helper for dynamic rendering
        const emojiSvgs = {
            '👍': '1f44d.svg',
            '❤️': '2764.svg',
            '😂': '1f602.svg',
            '😮': '1f62e.svg',
            '🙏': '1f64f.svg',
            '😢': '1f622.svg',
            '🎉': '1f389.svg'
        };

        function renderReactions(listElement, reactionsData) {
            listElement.innerHTML = '';
            reactionsData.forEach(r => {
                const badge = document.createElement('button');
                badge.type = 'button';
                badge.dataset.reaction = r.emoji;
                badge.className = 'flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] bg-slate-900 border border-slate-800 text-slate-300 hover:border-blue-500/50 hover:bg-slate-850 transition-all select-none cursor-pointer';
                const svgName = emojiSvgs[r.emoji] || '1f600.svg';
                badge.innerHTML = `<img src="https://cdn.jsdelivr.net/npm/@twemoji/api@14.1.0/assets/svg/${svgName}" class="w-3.5 h-3.5">
                                   <span class="font-semibold text-slate-400">${r.count}</span>`;
                
                badge.addEventListener('click', () => applyReaction(r.emoji, listElement.closest('.message-row')));
                listElement.appendChild(badge);
            });
        }

        async function applyReaction(emoji, messageRow) {
            if (!messageRow) return;
            const chatId = messageRow.dataset.id;
            try {
                const response = await fetch(`/chat/${chatId}/react`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ emoji })
                });
                if (response.ok) {
                    const data = await response.json();
                    const list = messageRow.querySelector('.reactions-list');
                    if (list) {
                        renderReactions(list, data.reactions);
                    }
                }
            } catch (err) {
                console.error("Failed to apply reaction", err);
            }
        }

        // Delegate hover emoji reaction clicks
        document.addEventListener('click', (e) => {
            const quickBtn = e.target.closest('.quick-react-btn');
            if (quickBtn) {
                const emoji = quickBtn.dataset.emoji;
                const row = quickBtn.closest('.message-row');
                applyReaction(emoji, row);
            }
        });

        // Forward message handler
        window.forwardMessage = async function(id, type) {
            const forwardModalScope = Alpine.find(document.querySelector('[x-data]'));
            if (!forwardModalScope) return;
            
            const messageText = forwardModalScope.forwardingMessage.text;
            const url = (type === 'group') ? `/group/${id}/chat` : `/chat/${id}`;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: messageText, is_forwarded: true })
                });

                if (response.ok) {
                    forwardModalScope.forwardingMessage = null;
                    // Trigger dynamic notification redirect
                    window.location.href = url;
                }
            } catch (err) {
                console.error(err);
            }
        }

        // Append message to DOM locally
        function appendMessageToDOM(chatData, isOutgoing = true) {
            if (!container) return;
            const timeStr = new Date(chatData.created_at || Date.now()).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            const alignClass = isOutgoing ? 'justify-end' : 'justify-start';
            const itemsClass = isOutgoing ? 'items-end' : 'items-start';
            const rowDirectionClass = isOutgoing ? 'flex-row-reverse' : 'flex-row';
            const bubbleClass = isOutgoing 
                ? 'bg-blue-600 text-white rounded-2xl rounded-br-sm' 
                : 'bg-slate-900 text-slate-50 rounded-2xl rounded-bl-sm border border-slate-800/80';
            const marginClass = isOutgoing ? 'self-end mr-2' : 'self-start ml-2';

            let replyHtml = '';
            if (chatData.reply_to_id && chatData.reply_to) {
                replyHtml = `
                    <div class="border-l-2 border-blue-500 bg-slate-950/40 p-2 rounded-lg mx-2.5 mt-2.5 text-xs text-slate-400">
                        <strong class="text-[11px] text-slate-300">${chatData.reply_to.sender?.name ?? 'Unknown'}</strong>
                        <p class="truncate">${chatData.reply_to.message}</p>
                    </div>
                `;
            }

            let attachmentHtml = '';
            if (chatData.attachment_path) {
                attachmentHtml = `
                    <div class="mb-2">
                        <img src="${chatData.attachment_path}" class="max-w-xs rounded-lg border border-slate-800/50 shadow-md">
                    </div>
                `;
            }

            let forwardHtml = '';
            if (chatData.is_forwarded) {
                forwardHtml = `
                    <span class="text-[10px] text-slate-500 italic mb-1 flex items-center gap-1">
                        <i data-lucide="forward" class="w-3 h-3"></i> Forwarded
                    </span>
                `;
            }

            const deleteForEveryoneBtn = isOutgoing ? `
                <form action="/chat/${chatData.id}" method="POST" class="inline" onsubmit="return confirm('Delete this message for everyone?');">
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="p-1 rounded text-rose-400 hover:text-rose-300 hover:bg-slate-800" title="Delete for everyone">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    </button>
                </form>
            ` : '';

            const escMessage = chatData.message ? `<p class="text-sm leading-relaxed whitespace-pre-wrap emoji-render">${chatData.message}</p>` : '';

            const messageHtml = `
                <div class="flex ${alignClass} message-row group relative" data-id="${chatData.id}">
                    <div class="flex flex-col ${itemsClass} max-w-[70%] relative">
                        
                        ${forwardHtml}

                        <div class="flex items-end gap-2 ${rowDirectionClass} relative">
                            
                            <div class="absolute ${isOutgoing ? '-left-16' : '-right-16'} top-1/2 -translate-y-1/2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity z-20">
                                <button onclick="window.Alpine.find(document.querySelector('[x-data]')).replyingTo = { id: ${chatData.id}, name: '${chatData.sender?.name}', text: '${chatData.message?.replace(/'/g, '\\\'')}' }" class="p-1.5 bg-slate-800 text-slate-400 hover:text-white rounded-md border border-slate-700" title="Reply">
                                    <i data-lucide="reply" class="w-3.5 h-3.5"></i>
                                </button>
                                <button onclick="window.Alpine.find(document.querySelector('[x-data]')).forwardingMessage = { id: ${chatData.id}, text: '${chatData.message?.replace(/'/g, '\\\'')}' }" class="p-1.5 bg-slate-800 text-slate-400 hover:text-white rounded-md border border-slate-700" title="Forward">
                                    <i data-lucide="forward" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>

                            <div class="shadow-sm relative ${bubbleClass}">
                                ${replyHtml}
                                <div class="px-4 py-2.5">
                                    ${attachmentHtml}
                                    ${escMessage}
                                </div>
                                
                                <div class="absolute left-0 bottom-full mb-1 bg-slate-900 border border-slate-700/80 rounded-full px-2 py-1 shadow-2xl opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 transition-all duration-150 flex gap-1 z-30 pointer-events-auto">
                                    <button type="button" class="quick-react-btn p-1 rounded-full hover:bg-slate-800 hover:scale-125 transition-transform" data-emoji="👍">
                                        <img src="https://cdn.jsdelivr.net/npm/@twemoji/api@14.1.0/assets/svg/1f44d.svg" class="w-5 h-5">
                                    </button>
                                    <button type="button" class="quick-react-btn p-1 rounded-full hover:bg-slate-800 hover:scale-125 transition-transform" data-emoji="❤️">
                                        <img src="https://cdn.jsdelivr.net/npm/@twemoji/api@14.1.0/assets/svg/2764.svg" class="w-5 h-5">
                                    </button>
                                    <button type="button" class="quick-react-btn p-1 rounded-full hover:bg-slate-800 hover:scale-125 transition-transform" data-emoji="😂">
                                        <img src="https://cdn.jsdelivr.net/npm/@twemoji/api@14.1.0/assets/svg/1f602.svg" class="w-5 h-5">
                                    </button>
                                    <button type="button" class="quick-react-btn p-1 rounded-full hover:bg-slate-800 hover:scale-125 transition-transform" data-emoji="😮">
                                        <img src="https://cdn.jsdelivr.net/npm/@twemoji/api@14.1.0/assets/svg/1f62e.svg" class="w-5 h-5">
                                    </button>
                                    <button type="button" class="quick-react-btn p-1 rounded-full hover:bg-slate-800 hover:scale-125 transition-transform" data-emoji="🙏">
                                        <img src="https://cdn.jsdelivr.net/npm/@twemoji/api@14.1.0/assets/svg/1f64f.svg" class="w-5 h-5">
                                    </button>
                                    <button type="button" class="quick-react-btn p-1 rounded-full hover:bg-slate-800 hover:scale-125 transition-transform" data-emoji="😢">
                                        <img src="https://cdn.jsdelivr.net/npm/@twemoji/api@14.1.0/assets/svg/1f622.svg" class="w-5 h-5">
                                    </button>
                                    <button type="button" class="quick-react-btn p-1 rounded-full hover:bg-slate-800 hover:scale-125 transition-transform" data-emoji="🎉">
                                        <img src="https://cdn.jsdelivr.net/npm/@twemoji/api@14.1.0/assets/svg/1f389.svg" class="w-5 h-5">
                                    </button>
                                    
                                    <div class="w-px h-5 bg-slate-700 self-center mx-1"></div>
                                    
                                    <form action="/chat/${chatData.id}/hide" method="POST" class="inline">
                                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                        <button type="submit" class="p-1 rounded text-slate-400 hover:text-slate-100 hover:bg-slate-800" title="Delete for me">
                                            <i data-lucide="eye-off" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                    ${deleteForEveryoneBtn}
                                </div>
                            </div>
                        </div>

                        <div class="reactions-list flex flex-wrap gap-1 mt-1.5 ${marginClass}"></div>
                        <span class="text-[10px] text-slate-500 mt-1 ${marginClass}">${timeStr}</span>
                    </div>
                </div>
            `;
            
            const wrapper = document.createElement('div');
            wrapper.innerHTML = messageHtml.trim();
            const newMsgNode = wrapper.firstChild;
            container.appendChild(newMsgNode);
            
            if (window.lucide) {
                window.lucide.createIcons({ node: newMsgNode });
            }
            scrollToBottom();
        }

        // AJax Submit Form
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                
                const message = textarea.value.trim();
                const hasFile = fileInput.files.length > 0;

                if (!message && !hasFile) return;

                textarea.value = '';
                textarea.style.height = '';
                
                if (attachmentPreview) {
                    attachmentPreview.classList.add('hidden');
                }

                const formData = new FormData(form);
                if (message) formData.set('message', message);

                // Reset file input for next message
                fileInput.value = '';

                // Get Alpine.js scope to clear reply
                const alpineScope = Alpine.find(document.querySelector('[x-data]'));
                if (alpineScope) {
                    alpineScope.replyingTo = null;
                }

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Failed to send message');
                })
                .then(data => {
                    if (data.success && data.chat) {
                        appendMessageToDOM(data.chat, true);
                    }
                })
                .catch(error => console.error(error));
            });

            textarea.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    form.dispatchEvent(new Event('submit'));
                }
            });
        }

        // Live Reverb Channels
        if (window.Echo) {
            const currentUserId = {{ auth()->id() ?? 'null' }};
            const currentReceiverId = {{ isset($receiver) ? $receiver->id : 'null' }};
            const currentGroupId = {{ isset($group) ? $group->id : 'null' }};
            
            // Listen on user's private channel (for DMs)
            if (currentUserId) {
                window.Echo.private(`chat.${currentUserId}`)
                    .listen('MessageSent', (e) => {
                        // Append if this is a DM from the current receiver
                        if (currentReceiverId && e.chat.sender_id === currentReceiverId && !e.chat.group_id) {
                            appendMessageToDOM(e.chat, false);
                        }
                    })
                    .listen('ReactionUpdated', (e) => {
                        const messageRow = document.querySelector(`.message-row[data-id="${e.chat_id}"]`);
                        if (messageRow) {
                            const list = messageRow.querySelector('.reactions-list');
                            if (list) renderReactions(list, e.reactions);
                        }
                    });
            }

            // Listen on group channel if active
            if (currentGroupId) {
                window.Echo.private(`chat-group.${currentGroupId}`)
                    .listen('MessageSent', (e) => {
                        if (e.chat.sender_id !== currentUserId) {
                            appendMessageToDOM(e.chat, false);
                        }
                    })
                    .listen('ReactionUpdated', (e) => {
                        const messageRow = document.querySelector(`.message-row[data-id="${e.chat_id}"]`);
                        if (messageRow) {
                            const list = messageRow.querySelector('.reactions-list');
                            if (list) renderReactions(list, e.reactions);
                        }
                    });
            }
        }
    });
</script>
</x-layout>
