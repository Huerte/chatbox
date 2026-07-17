<x-layout>
<div class="flex h-screen bg-slate-950 font-sans overflow-hidden" x-data="{ 
    activeTab: '{{ request()->has('search_user') ? 'users' : (request()->has('search_chat') ? 'messages' : 'overview') }}',
    showToast: true
}">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col hidden md:flex flex-shrink-0">
        <div class="h-16 flex items-center px-6 border-b border-slate-800">
            <h1 class="text-xl font-bold text-slate-50 flex items-center gap-3">
                <i data-lucide="shield-check" class="w-6 h-6 text-blue-500"></i>
                Admin Area
            </h1>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <button @click="activeTab = 'overview'" 
                    :class="activeTab === 'overview' ? 'bg-blue-500/10 text-blue-400' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200'"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-left">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                Overview
            </button>
            <button @click="activeTab = 'users'" 
                    :class="activeTab === 'users' ? 'bg-blue-500/10 text-blue-400' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200'"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-left">
                <i data-lucide="users" class="w-5 h-5"></i>
                Users Management
            </button>
            <button @click="activeTab = 'messages'" 
                    :class="activeTab === 'messages' ? 'bg-blue-500/10 text-blue-400' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200'"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors text-left">
                <i data-lucide="message-square" class="w-5 h-5"></i>
                Chat Activity
            </button>
        </nav>

        <div class="p-4 border-t border-slate-800">
            <a href="{{ route('chat.index') }}" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800/50 hover:text-slate-200 transition-colors">
                <i data-lucide="log-out" class="w-5 h-5 rotate-180"></i>
                Back to App
            </a>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-opacity-5">
        
        {{-- Header --}}
        <header class="h-16 border-b border-slate-800/50 bg-slate-900/80 backdrop-blur-md flex items-center justify-between px-8 flex-shrink-0">
            <h2 class="text-lg font-semibold text-slate-200 capitalize" x-text="activeTab.replace('-', ' ')"></h2>
            
            <div class="flex items-center gap-4">
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white shadow-lg shadow-blue-500/20">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- Scrollable Content Area --}}
        <div class="flex-1 overflow-y-auto p-8 scrollbar-thin">
            <div class="max-w-6xl mx-auto">
                
                {{-- TAB: OVERVIEW --}}
                <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        {{-- Metric Card 1 --}}
                        <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-6 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i data-lucide="users" class="w-16 h-16 text-blue-500"></i>
                            </div>
                            <h3 class="text-slate-400 text-sm font-medium mb-1 relative z-10">Total Users</h3>
                            <div class="text-3xl font-bold text-slate-50 relative z-10">{{ number_format($totalUsers) }}</div>
                            <div class="mt-4 text-xs text-blue-400 font-medium relative z-10 flex items-center gap-1">
                                <i data-lucide="trending-up" class="w-3 h-3"></i> System wide
                            </div>
                        </div>

                        {{-- Metric Card 2 --}}
                        <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-6 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i data-lucide="message-circle" class="w-16 h-16 text-indigo-500"></i>
                            </div>
                            <h3 class="text-slate-400 text-sm font-medium mb-1 relative z-10">Total Messages</h3>
                            <div class="text-3xl font-bold text-slate-50 relative z-10">{{ number_format($totalChats) }}</div>
                            <div class="mt-4 text-xs text-indigo-400 font-medium relative z-10 flex items-center gap-1">
                                <i data-lucide="activity" class="w-3 h-3"></i> All time history
                            </div>
                        </div>

                        {{-- Metric Card 3 --}}
                        <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-6 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i data-lucide="zap" class="w-16 h-16 text-green-500"></i>
                            </div>
                            <h3 class="text-slate-400 text-sm font-medium mb-1 relative z-10">Chats Today</h3>
                            <div class="text-3xl font-bold text-slate-50 relative z-10">{{ number_format($chatsToday) }}</div>
                            <div class="mt-4 text-xs text-green-400 font-medium relative z-10 flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3"></i> Last 24 hours
                            </div>
                        </div>

                        {{-- Metric Card 4 --}}
                        <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-6 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <i data-lucide="shield" class="w-16 h-16 text-rose-500"></i>
                            </div>
                            <h3 class="text-slate-400 text-sm font-medium mb-1 relative z-10">Super Admins</h3>
                            <div class="text-3xl font-bold text-slate-50 relative z-10">{{ number_format($totalAdmins) }}</div>
                            <div class="mt-4 text-xs text-rose-400 font-medium relative z-10 flex items-center gap-1">
                                <i data-lucide="lock" class="w-3 h-3"></i> Highly privileged
                            </div>
                        </div>
                    </div>

                    {{-- Mini Activity Feed --}}
                    <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-slate-200 mb-6 flex items-center gap-2">
                            <i data-lucide="history" class="w-5 h-5 text-slate-400"></i> Recent Platform Activity
                        </h2>
                        
                        <div class="space-y-4">
                            @forelse($chats->take(5) as $chat)
                                <div class="flex items-center gap-4 p-3 rounded-xl bg-slate-800/20 border border-slate-800/50">
                                    <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 flex-shrink-0">
                                        <i data-lucide="message-square-dashed" class="w-5 h-5"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm text-slate-300 truncate">
                                            <span class="font-medium text-slate-200">{{ $chat->sender->name ?? 'Unknown' }}</span> sent a message to <span class="font-medium text-slate-200">{{ $chat->receiver->name ?? 'Unknown' }}</span>
                                        </p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $chat->created_at->diffForHumans() }}</p>
                                    </div>
                                    <button @click="activeTab = 'messages'" class="text-xs font-medium text-blue-400 hover:text-blue-300">View details</button>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-500 text-sm">No recent activity found.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- TAB: USERS --}}
                <div x-show="activeTab === 'users'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="bg-slate-900/50 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                        {{-- Toolbar --}}
                        <div class="p-4 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <form action="{{ route('admin.index') }}" method="GET" class="relative w-full sm:w-96">
                                <input type="hidden" name="tab" value="users">
                                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-500"></i>
                                <input type="text" name="search_user" value="{{ request('search_user') }}" placeholder="Search users by name or email..." 
                                       class="w-full bg-slate-950 border border-slate-800 text-slate-200 text-sm rounded-lg pl-10 pr-4 py-2.5 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors placeholder:text-slate-600">
                                @if(request('search_user'))
                                    <a href="{{ route('admin.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </a>
                                @endif
                            </form>
                            <span class="text-xs font-medium bg-slate-800 text-slate-400 px-3 py-1.5 rounded-full flex-shrink-0">
                                {{ $users->total() }} total users
                            </span>
                        </div>

                        {{-- Table --}}
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-400">
                                <thead class="bg-slate-950/50 text-xs uppercase text-slate-500 border-b border-slate-800">
                                    <tr>
                                        <th class="px-6 py-4 font-semibold tracking-wider">User</th>
                                        <th class="px-6 py-4 font-semibold tracking-wider">Email</th>
                                        <th class="px-6 py-4 font-semibold tracking-wider">Joined</th>
                                        <th class="px-6 py-4 font-semibold tracking-wider text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/50">
                                    @forelse($users as $user)
                                        <tr class="hover:bg-slate-800/30 transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-slate-700 to-slate-800 flex items-center justify-center text-slate-200 font-bold shadow-inner">
                                                        {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-slate-200 flex items-center gap-2">
                                                            {{ $user->name }}
                                                            @if($user->is_admin)
                                                                <span class="flex items-center gap-1 text-[10px] bg-blue-500/10 text-blue-400 px-1.5 py-0.5 rounded border border-blue-500/20 uppercase tracking-wider font-bold">
                                                                    <i data-lucide="shield-check" class="w-3 h-3"></i> Admin
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="text-xs text-slate-500 mt-0.5">ID: #{{ $user->id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-slate-300">{{ $user->email }}</td>
                                            <td class="px-6 py-4">{{ $user->created_at->format('M d, Y') }}</td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    
                                                    @if(auth()->id() !== $user->id)
                                                        {{-- Promote/Demote Form --}}
                                                        <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="p-2 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-slate-400 {{ $user->is_admin ? 'text-amber-400 hover:bg-amber-500/10 hover:text-amber-300' : 'text-blue-400 hover:bg-blue-500/10 hover:text-blue-300' }}"
                                                                    title="{{ $user->is_admin ? 'Demote to User' : 'Promote to Admin' }}">
                                                                <i data-lucide="{{ $user->is_admin ? 'shield-off' : 'shield-plus' }}" class="w-4 h-4"></i>
                                                            </button>
                                                        </form>

                                                        {{-- Delete Form --}}
                                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to completely delete {{ $user->name }}? All their messages will also be permanently deleted. This cannot be undone.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="p-2 text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-rose-400" title="Delete User">
                                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-xs text-slate-500 px-2">It's you</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center mb-3">
                                                        <i data-lucide="search-x" class="w-6 h-6 text-slate-600"></i>
                                                    </div>
                                                    <p class="text-sm">No users found matching your search.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 border-t border-slate-800 bg-slate-900/30">
                            {{ $users->appends(['search_user' => request('search_user'), 'search_chat' => request('search_chat')])->links() }}
                        </div>
                    </div>
                </div>

                {{-- TAB: MESSAGES --}}
                <div x-show="activeTab === 'messages'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <div class="bg-slate-900/50 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
                        {{-- Toolbar --}}
                        <div class="p-4 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <form action="{{ route('admin.index') }}" method="GET" class="relative w-full sm:w-96">
                                <input type="hidden" name="tab" value="messages">
                                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-500"></i>
                                <input type="text" name="search_chat" value="{{ request('search_chat') }}" placeholder="Search messages or usernames..." 
                                       class="w-full bg-slate-950 border border-slate-800 text-slate-200 text-sm rounded-lg pl-10 pr-4 py-2.5 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors placeholder:text-slate-600">
                                @if(request('search_chat'))
                                    <a href="{{ route('admin.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-300">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </a>
                                @endif
                            </form>
                            <span class="text-xs font-medium bg-slate-800 text-slate-400 px-3 py-1.5 rounded-full flex-shrink-0">
                                {{ $chats->total() }} total messages
                            </span>
                        </div>

                        {{-- Table --}}
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-400">
                                <thead class="bg-slate-950/50 text-xs uppercase text-slate-500 border-b border-slate-800">
                                    <tr>
                                        <th class="px-6 py-4 font-semibold tracking-wider">Conversation Route</th>
                                        <th class="px-6 py-4 font-semibold tracking-wider w-1/2">Message Excerpt</th>
                                        <th class="px-6 py-4 font-semibold tracking-wider">Date</th>
                                        <th class="px-6 py-4 font-semibold tracking-wider text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800/50">
                                    @forelse($chats as $chat)
                                        <tr class="hover:bg-slate-800/30 transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-medium text-slate-300">{{ $chat->sender->name ?? 'Deleted User' }}</span>
                                                    <i data-lucide="arrow-right" class="w-3 h-3 text-slate-600"></i>
                                                    <span class="font-medium text-slate-300">{{ $chat->receiver->name ?? 'Deleted User' }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="bg-slate-950 px-3 py-2 rounded-lg border border-slate-800/80 text-slate-300 truncate max-w-sm cursor-help" title="{{ $chat->message }}">
                                                    {{ Str::limit($chat->message, 60) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-xs">
                                                <div class="text-slate-300">{{ $chat->created_at->format('M d, Y') }}</div>
                                                <div class="text-slate-500 mt-0.5">{{ $chat->created_at->format('H:i A') }}</div>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <form action="{{ route('admin.chats.destroy', $chat) }}" method="POST" class="inline-block opacity-0 group-hover:opacity-100 transition-opacity" onsubmit="return confirm('Delete this message forever?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-rose-400 hover:bg-rose-500/10 hover:text-rose-300 rounded-lg transition-colors focus:outline-none" title="Delete Message">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center mb-3">
                                                        <i data-lucide="message-square-off" class="w-6 h-6 text-slate-600"></i>
                                                    </div>
                                                    <p class="text-sm">No messages found matching your search.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 border-t border-slate-800 bg-slate-900/30">
                            {{ $chats->appends(['search_user' => request('search_user'), 'search_chat' => request('search_chat')])->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- TOAST NOTIFICATIONS --}}
    <div class="fixed bottom-6 right-6 z-50 flex flex-col gap-3">
        @if(session('success'))
            <div x-show="showToast" x-init="setTimeout(() => showToast = false, 5000)" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="bg-slate-900 border border-green-500/30 shadow-2xl rounded-xl p-4 flex items-start gap-3 w-80">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5"></i>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-slate-200">Success</h4>
                    <p class="text-xs text-slate-400 mt-1">{{ session('success') }}</p>
                </div>
                <button @click="showToast = false" class="text-slate-500 hover:text-slate-300"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
        @endif

        @if(session('error'))
            <div x-show="showToast" x-init="setTimeout(() => showToast = false, 7000)" 
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 class="bg-slate-900 border border-rose-500/30 shadow-2xl rounded-xl p-4 flex items-start gap-3 w-80">
                <i data-lucide="alert-circle" class="w-5 h-5 text-rose-400 flex-shrink-0 mt-0.5"></i>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-slate-200">Error</h4>
                    <p class="text-xs text-slate-400 mt-1">{{ session('error') }}</p>
                </div>
                <button @click="showToast = false" class="text-slate-500 hover:text-slate-300"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>
        @endif
    </div>

</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
</x-layout>
