<x-layout>
<div class="flex h-screen overflow-hidden bg-slate-900 font-sans">
    
    {{-- SIDEBAR --}}
    <aside class="w-80 flex-shrink-0 flex flex-col bg-slate-950 border-r border-slate-800">
        {{-- Header --}}
        <div class="p-4 border-b border-slate-800 flex items-center justify-between">
            <h1 class="text-lg font-bold text-slate-50 flex items-center gap-2">
                <i data-lucide="message-square" class="w-5 h-5 text-blue-500 stroke-[1.5]"></i>
                ChatBox
            </h1>
            <button id="new-chat-btn" class="p-2 text-slate-400 hover:text-blue-400 hover:bg-blue-500/10 rounded-lg transition-colors" title="New message">
                <i data-lucide="edit" class="w-4 h-4 stroke-[1.5]"></i>
            </button>
        </div>

        {{-- Search --}}
        <div class="p-4">
            <div class="relative flex items-center">
                <i data-lucide="search" class="absolute left-3 w-4 h-4 text-slate-500 stroke-[1.5]"></i>
                <input type="text" id="search-contacts" placeholder="Search contacts..." class="w-full bg-slate-900 border border-slate-800 rounded-md py-2 pl-9 pr-4 text-sm text-slate-50 placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
            </div>
        </div>

        {{-- Contacts --}}
        <div class="flex-1 overflow-y-auto p-2 scrollbar-thin">
            <div class="px-2 pb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">Messages</div>
            <ul class="space-y-1">
                @forelse ($users as $user)
                    @php
                        $isActive = isset($receiver) && $receiver->id === $user->id;
                    @endphp
                    <li class="contact-item" data-name="{{ $user->name }}">
                        <a href="{{ route('chat.show', $user->id) }}" class="flex items-center gap-3 p-2 rounded-md transition-colors {{ $isActive ? 'bg-blue-600/10 border-l-2 border-blue-500' : 'hover:bg-slate-950 hover:bg-slate-900/50 border-l-2 border-transparent' }}">
                            <div class="relative flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-sm font-semibold text-slate-300">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-slate-950"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-50 truncate">{{ $user->name }}</span>
                                </div>
                                <p class="text-[12px] text-slate-500 truncate">Tap to view chat</p>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="p-4 text-center text-sm text-slate-500">No contacts available</li>
                @endforelse
            </ul>
        </div>
        
        {{-- Profile Bottom --}}
        <div class="border-t border-slate-800 bg-slate-950">
            {{-- Profile Trigger --}}
            <button id="profile-trigger" type="button" class="w-full p-4 flex items-center gap-3 hover:bg-white/5 transition-colors">
                <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white flex-shrink-0 shadow-lg">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="text-sm flex-1 text-left min-w-0">
                    <p class="font-semibold text-slate-50 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-[11px] text-green-500 flex items-center gap-1 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block animate-pulse"></span>
                        Online
                    </p>
                </div>
                <i data-lucide="chevrons-up-down" class="w-4 h-4 text-slate-500 stroke-[1.5] flex-shrink-0"></i>
            </button>
        </div>

        {{-- Profile Dropdown — fixed to sidebar bottom, never clipped --}}
        <div id="profile-menu" class="hidden fixed z-[9999] w-72 bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl overflow-hidden" style="bottom: 80px; left: 12px;">
            {{-- Header --}}
            <div class="flex items-center gap-3 px-4 py-3.5 border-b border-slate-800 bg-slate-950">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-sm font-bold text-white shadow">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-50 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </div>
            {{-- Menu Items --}}
            <div class="py-1.5">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-800 hover:text-slate-50 transition-colors">
                    <div class="w-7 h-7 rounded-lg bg-slate-800 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="user-pen" class="w-3.5 h-3.5 stroke-[1.5] text-blue-400"></i>
                    </div>
                    Change Profile
                </a>
                <button type="button" id="status-toggle-btn" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-800 hover:text-slate-50 transition-colors text-left">
                    <div class="w-7 h-7 rounded-lg bg-slate-800 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="activity" class="w-3.5 h-3.5 stroke-[1.5] text-green-400"></i>
                    </div>
                    Set Status
                </button>
                <button type="button" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-800 hover:text-slate-50 transition-colors text-left">
                    <div class="w-7 h-7 rounded-lg bg-slate-800 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="bell" class="w-3.5 h-3.5 stroke-[1.5] text-yellow-400"></i>
                    </div>
                    Notifications
                </button>
                <button type="button" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-300 hover:bg-slate-800 hover:text-slate-50 transition-colors text-left">
                    <div class="w-7 h-7 rounded-lg bg-slate-800 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="settings" class="w-3.5 h-3.5 stroke-[1.5] text-slate-400"></i>
                    </div>
                    Settings
                </button>
                @if(auth()->user()->is_admin)
                <a href="{{ route('admin.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-blue-400 hover:bg-blue-500/10 hover:text-blue-300 transition-colors">
                    <div class="w-7 h-7 rounded-lg bg-blue-500/10 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="shield" class="w-3.5 h-3.5 stroke-[1.5] text-blue-400"></i>
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
                            <i data-lucide="log-out" class="w-3.5 h-3.5 stroke-[1.5] text-rose-400"></i>
                        </div>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- MAIN CHAT --}}
    <main class="flex-1 flex flex-col min-w-0 bg-slate-900 relative">
        @if(!$receiver)
            <div class="flex-1 flex flex-col items-center justify-center text-slate-500">
                <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center mb-4">
                    <i data-lucide="message-square" class="w-8 h-8 text-slate-400 stroke-[1.5]"></i>
                </div>
                <p class="text-sm">Select a contact to start messaging</p>
            </div>
        @else
            {{-- Chat Header --}}
            <header class="h-16 px-6 border-b border-slate-800 flex items-center justify-between flex-shrink-0 bg-slate-900">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-sm font-semibold text-slate-300">
                            {{ strtoupper(substr($receiver->name, 0, 1)) }}
                        </div>
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-slate-900"></span>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-50">{{ $receiver->name }}</h2>
                        <p class="text-[12px] text-slate-500">Online</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button id="voice-call-btn" class="text-slate-400 hover:text-blue-500 transition-colors" title="Voice Call">
                        <i data-lucide="phone" class="w-5 h-5 stroke-[1.5]"></i>
                    </button>
                    <button id="video-call-btn" class="text-slate-400 hover:text-blue-500 transition-colors" title="Video Call">
                        <i data-lucide="video" class="w-5 h-5 stroke-[1.5]"></i>
                    </button>
                    <div class="w-px h-6 bg-slate-800 mx-1"></div>
                    <button id="info-toggle" class="text-slate-400 hover:text-slate-200 transition-colors" title="Information">
                        <i data-lucide="info" class="w-5 h-5 stroke-[1.5]"></i>
                    </button>
                </div>
            </header>

            {{-- Messages Area --}}
            <div id="messages-container" class="flex-1 overflow-y-auto p-6 flex flex-col gap-6 scrollbar-thin">
                @foreach ($messages as $chat)
                    @php
                        $isOutgoing = $chat->sender_id === auth()->id();
                    @endphp
                    <div class="flex {{ $isOutgoing ? 'justify-end' : 'justify-start' }} message-row group" data-id="{{ $chat->id }}">
                        <div class="flex flex-col {{ $isOutgoing ? 'items-end' : 'items-start' }} max-w-[70%]">

                            {{-- Bubble + trigger side by side --}}
                            <div class="flex items-end gap-2 {{ $isOutgoing ? 'flex-row-reverse' : 'flex-row' }}">
                                {{-- Reaction trigger button --}}
                                <button type="button" class="reaction-trigger-btn flex-shrink-0 w-7 h-7 rounded-full bg-slate-800 border border-slate-700/60 flex items-center justify-center text-slate-500 hover:text-slate-200 hover:bg-slate-700 opacity-0 group-hover:opacity-100 transition-all duration-150 self-end mb-0.5">
                                    <i data-lucide="smile-plus" class="w-3.5 h-3.5 stroke-[1.5]"></i>
                                </button>

                                {{-- Message Bubble --}}
                                <div class="px-4 py-2.5 shadow-sm {{ $isOutgoing ? 'bg-blue-600 text-white rounded-2xl rounded-br-sm' : 'bg-slate-800 text-slate-50 rounded-2xl rounded-bl-sm border border-slate-700/50' }}">
                                    <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $chat->message }}</p>
                                </div>
                            </div>

                            {{-- Reactions Badge list --}}
                            <div class="reactions-list flex flex-wrap gap-1 mt-1.5 {{ $isOutgoing ? 'self-end mr-9' : 'self-start ml-9' }}">
                                @php
                                    $grouped = $chat->reactions->groupBy('emoji');
                                @endphp
                                @foreach($grouped as $emoji => $reactions)
                                    <button type="button" data-reaction="{{ $emoji }}" class="flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] bg-slate-800 border border-slate-700 text-slate-300 hover:border-blue-500/50 hover:bg-slate-700 transition-all select-none cursor-pointer">
                                        <span>{{ $emoji }}</span><span class="font-semibold text-slate-400">{{ $reactions->count() }}</span>
                                    </button>
                                @endforeach
                            </div>

                            <span class="text-[11px] text-slate-500 mt-1 {{ $isOutgoing ? 'mr-9' : 'ml-9' }}">{{ $chat->created_at->format('h:i A') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Input Area --}}
            <div class="p-4 border-t border-slate-800 bg-slate-900 flex-shrink-0 relative">
                
                {{-- Emoji picker: positions above the emoji toggle button --}}
                <div id="emoji-picker" class="hidden absolute bg-slate-950 border border-slate-800 rounded-xl p-3 shadow-2xl z-40" style="bottom: calc(100% + 8px); right: 4px; width: 320px;">
                    <div class="text-[10px] font-semibold text-slate-500 uppercase tracking-widest mb-2 px-1">Emoji</div>
                    <div class="grid grid-cols-8 gap-1">
                        @foreach(['😊','😂','❤️','👍','🔥','✅','🚀','💬','🎉','😎','🤔','👋','💡','⚡','🌟','😍','😭','👀','✨','👏','💯','🙌','🥺','🤣','🥳','😜','🤩','🫡','😇','🙃','😏','🤗'] as $emoji)
                        <button type="button" class="emoji-btn text-xl hover:bg-slate-800 rounded-lg p-1.5 transition-colors flex items-center justify-center">{{ $emoji }}</button>
                        @endforeach
                    </div>
                </div>

                {{-- Attachment preview --}}
                <div id="attachment-preview" class="hidden flex items-center gap-2 mb-3 bg-slate-800 border border-slate-700 rounded-lg p-2 text-xs text-slate-300">
                    <i data-lucide="paperclip" class="w-4 h-4 text-blue-500"></i>
                    <span id="attachment-name" class="flex-1 truncate">file.txt</span>
                    <button type="button" id="remove-attachment" class="text-slate-400 hover:text-slate-200">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <form id="chat-form" method="POST" action="{{ route('chat.store', $receiver->id) }}" class="flex items-end gap-3">
                    @csrf
                    <input type="file" id="file-input" class="hidden" multiple>
                    <div class="flex-1 flex items-end gap-2 bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 focus-within:border-blue-500/50 transition-colors">
                        <button type="button" id="attach-file-btn" class="p-1.5 text-slate-400 hover:text-slate-200 transition-colors flex-shrink-0" title="Attach file">
                            <i data-lucide="paperclip" class="w-5 h-5 stroke-[1.5]"></i>
                        </button>
                        <button type="button" id="add-image-btn" class="p-1.5 text-slate-400 hover:text-slate-200 transition-colors flex-shrink-0" title="Add image">
                            <i data-lucide="image" class="w-5 h-5 stroke-[1.5]"></i>
                        </button>
                        
                        <textarea 
                            id="message-textarea"
                            name="message" 
                            rows="1" 
                            placeholder="Type your message..." 
                            required
                            class="flex-1 bg-transparent border-0 text-sm text-slate-50 placeholder-slate-400 focus:ring-0 resize-none py-1.5 px-1 max-h-32 scrollbar-thin outline-none"
                            oninput="this.style.height = ''; this.style.height = Math.min(this.scrollHeight, 128) + 'px';"
                        ></textarea>
                        
                        <button type="button" id="emoji-toggle" class="p-1.5 text-slate-400 hover:text-slate-200 transition-colors flex-shrink-0" title="Emoji">
                            <i data-lucide="smile" class="w-5 h-5 stroke-[1.5]"></i>
                        </button>
                    </div>
                    
                    <button type="submit" class="p-3 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition-colors flex-shrink-0 flex items-center justify-center" title="Send message">
                        <i data-lucide="send-horizonal" class="w-5 h-5 stroke-[1.5]"></i>
                    </button>
                </form>
            </div>
        @endif
    </main>

    {{-- INFO PANEL --}}
    @if($receiver)
    <aside id="info-panel" class="hidden w-80 flex-shrink-0 border-l border-slate-800 bg-slate-950 flex flex-col overflow-y-auto scrollbar-thin">
        {{-- Profile card --}}
        <div class="flex flex-col items-center p-6 border-b border-slate-800">
            <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center text-xl font-bold text-slate-300 shadow-xl mb-3">
                {{ strtoupper(substr($receiver->name, 0, 1)) }}
            </div>
            <p class="text-sm font-semibold text-slate-50 mb-0.5">{{ $receiver->name }}</p>
            <p class="text-xs text-green-500 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse inline-block"></span>
                Active now
            </p>
        </div>

        {{-- Section: Information --}}
        <div class="p-6 border-b border-slate-800 space-y-4">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Contact Info</div>
            <div>
                <div class="text-[11px] text-slate-500">Email Address</div>
                <div class="text-sm text-slate-300 font-medium truncate">{{ $receiver->email }}</div>
            </div>
            <div>
                <div class="text-[11px] text-slate-500">Joined</div>
                <div class="text-sm text-slate-300 font-medium">July 13, 2026</div>
            </div>
        </div>

        {{-- Section: Shared media / links --}}
        <div class="p-6 border-b border-slate-800">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Shared Media</div>
            <div class="grid grid-cols-3 gap-2">
                <div class="aspect-square rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-600 hover:text-slate-400 cursor-pointer transition-colors">
                    <i data-lucide="image" class="w-5 h-5 stroke-[1.5]"></i>
                </div>
                <div class="aspect-square rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-600 hover:text-slate-400 cursor-pointer transition-colors">
                    <i data-lucide="file-text" class="w-5 h-5 stroke-[1.5]"></i>
                </div>
                <div class="aspect-square rounded-lg bg-slate-900 border border-slate-800 flex items-center justify-center text-slate-600 hover:text-slate-400 cursor-pointer transition-colors">
                    <i data-lucide="link" class="w-5 h-5 stroke-[1.5]"></i>
                </div>
            </div>
        </div>

        {{-- Section: Options --}}
        <div class="p-4 space-y-1">
            <button class="w-full flex items-center gap-2 px-3 py-2 rounded-md text-left text-xs text-slate-400 hover:bg-slate-900 hover:text-slate-200 transition-colors">
                <i data-lucide="bell-off" class="w-4 h-4 stroke-[1.5]"></i>
                Mute Notifications
            </button>
            <button class="w-full flex items-center gap-2 px-3 py-2 rounded-md text-left text-xs text-rose-500 hover:bg-rose-500/10 transition-colors">
                <i data-lucide="ban" class="w-4 h-4 stroke-[1.5]"></i>
                Block Contact
            </button>
        </div>
    </aside>
    @endif
</div>

{{-- ===================== CALL OVERLAY MODAL ===================== --}}
@if($receiver)
<div id="call-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    {{-- Backdrop --}}
    <div id="call-backdrop" class="absolute inset-0 bg-black/75 backdrop-blur-md"></div>
    
    {{-- Panel --}}
    <div class="relative w-full max-w-sm bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-2xl text-center backdrop-blur-xl transition-all duration-300 transform scale-95" id="call-panel">
        <div class="relative w-24 h-24 mx-auto mb-6">
            {{-- Pulsing ring animation --}}
            <div class="absolute inset-0 rounded-full bg-blue-500/20 animate-ping"></div>
            <div class="absolute -inset-2 rounded-full border border-blue-500/10 animate-pulse"></div>
            <div id="call-avatar" class="relative w-full h-full rounded-full flex items-center justify-center text-3xl font-bold bg-slate-800 text-white shadow-xl"></div>
        </div>
        
        <h2 id="call-contact-name" class="text-lg font-semibold text-white mb-1"></h2>
        <p id="call-status" class="text-xs text-slate-400 mb-8 tracking-wide">Ringing...</p>
        
        <div class="flex justify-center gap-4">
            <button id="call-decline" class="w-12 h-12 rounded-full bg-rose-600 hover:bg-rose-500 flex items-center justify-center text-white shadow-lg shadow-rose-600/30 transition-all duration-200 hover:scale-105 active:scale-95">
                <i data-lucide="phone-off" class="w-5 h-5 stroke-[2]"></i>
            </button>
            <button id="call-accept" class="w-12 h-12 rounded-full bg-emerald-600 hover:bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-600/30 transition-all duration-200 hover:scale-105 active:scale-95">
                <i data-lucide="phone" class="w-5 h-5 stroke-[2]"></i>
            </button>
        </div>
    </div>
</div>
@endif

{{-- ===================== GLOBAL REACTION POPOVER ===================== --}}
{{-- Fixed to viewport — never clipped by overflow containers --}}
<div id="global-reaction-popover" class="hidden fixed z-[99999]" style="pointer-events: auto;">
    <div class="inline-flex items-center gap-0.5 bg-[#1a2744] border border-slate-600 rounded-full px-2.5 py-2 shadow-2xl" style="backdrop-filter: blur(16px);">
        <button type="button" data-emoji="👍" class="global-react-btn text-[22px] px-1.5 py-0.5 rounded-full hover:scale-[1.35] transition-all duration-150 hover:bg-white/10">👍</button>
        <button type="button" data-emoji="❤️" class="global-react-btn text-[22px] px-1.5 py-0.5 rounded-full hover:scale-[1.35] transition-all duration-150 hover:bg-white/10">❤️</button>
        <button type="button" data-emoji="😂" class="global-react-btn text-[22px] px-1.5 py-0.5 rounded-full hover:scale-[1.35] transition-all duration-150 hover:bg-white/10">😂</button>
        <button type="button" data-emoji="😮" class="global-react-btn text-[22px] px-1.5 py-0.5 rounded-full hover:scale-[1.35] transition-all duration-150 hover:bg-white/10">😮</button>
        <button type="button" data-emoji="🙏" class="global-react-btn text-[22px] px-1.5 py-0.5 rounded-full hover:scale-[1.35] transition-all duration-150 hover:bg-white/10">🙏</button>
        <button type="button" data-emoji="😢" class="global-react-btn text-[22px] px-1.5 py-0.5 rounded-full hover:scale-[1.35] transition-all duration-150 hover:bg-white/10">😢</button>
        <button type="button" data-emoji="🎉" class="global-react-btn text-[22px] px-1.5 py-0.5 rounded-full hover:scale-[1.35] transition-all duration-150 hover:bg-white/10">🎉</button>
    </div>
</div>

{{-- ===================== NEW MESSAGE MODAL ===================== --}}
<div id="new-chat-modal" class="hidden fixed inset-0 z-[9998] flex items-start justify-center pt-20 px-4">
    <div id="new-chat-backdrop" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
    <div class="relative w-full max-w-md bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl overflow-hidden animate-in">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-800">
            <h3 class="text-sm font-semibold text-slate-50">New Message</h3>
            <button id="new-chat-close" class="p-1.5 text-slate-400 hover:text-slate-50 hover:bg-slate-800 rounded-lg transition-colors">
                <i data-lucide="x" class="w-4 h-4 stroke-[1.5]"></i>
            </button>
        </div>
        <div class="px-4 py-3 border-b border-slate-800">
            <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500 stroke-[1.5]"></i>
                <input id="new-chat-search" type="text" placeholder="Search people..." autocomplete="off"
                    class="w-full bg-slate-800 border border-slate-700 rounded-lg py-2 pl-9 pr-4 text-sm text-slate-50 placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
            </div>
        </div>
        <ul id="new-chat-list" class="max-h-72 overflow-y-auto py-2">
            @forelse ($users as $user)
                <li class="new-chat-item" data-name="{{ strtolower($user->name) }}">
                    <a href="{{ route('chat.show', $user->id) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 transition-colors">
                        <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-sm font-semibold text-slate-200 flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-50 truncate">{{ $user->name }}</p>
                            <p class="text-[11px] text-slate-500 truncate">{{ $user->email }}</p>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-600 flex-shrink-0 stroke-[1.5]"></i>
                    </a>
                </li>
            @empty
                <li class="px-4 py-6 text-center text-sm text-slate-500">No contacts found</li>
            @endforelse
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const textarea = document.getElementById('message-textarea');
        const form = document.getElementById('chat-form');
        const container = document.getElementById('messages-container');
        const attachmentPreview = document.getElementById('attachment-preview');
        
        function scrollToBottom() {
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }
        
        function escapeHtml(str) {
            const d = document.createElement('div');
            d.appendChild(document.createTextNode(str));
            return d.innerHTML;
        }

        // ===================== GLOBAL REACTION SYSTEM =====================
        const globalPopover = document.getElementById('global-reaction-popover');
        let activeMessageRow = null;

        function showGlobalPopover(triggerBtn, messageRow) {
            activeMessageRow = messageRow;
            globalPopover.classList.remove('hidden');

            // Measure trigger position
            const rect = triggerBtn.getBoundingClientRect();
            const popW = globalPopover.offsetWidth || 290;
            const popH = globalPopover.offsetHeight || 56;

            // Default: appear above the trigger
            let top = rect.top - popH - 8;
            // If not enough space above, flip below
            if (top < 8) top = rect.bottom + 8;

            // Horizontal: try to center on trigger, clamp to viewport
            let left = rect.left + (rect.width / 2) - (popW / 2);
            if (left < 8) left = 8;
            if (left + popW > window.innerWidth - 8) left = window.innerWidth - popW - 8;

            globalPopover.style.top = top + 'px';
            globalPopover.style.left = left + 'px';
        }

        function hideGlobalPopover() {
            globalPopover.classList.add('hidden');
            activeMessageRow = null;
        }

        function renderReactions(listElement, reactionsData) {
            listElement.innerHTML = '';
            reactionsData.forEach(r => {
                const badge = document.createElement('button');
                badge.type = 'button';
                badge.dataset.reaction = r.emoji;
                badge.className = 'flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] bg-slate-800 border border-slate-700 text-slate-300 hover:border-blue-500/50 hover:bg-slate-700 transition-all select-none cursor-pointer';
                badge.innerHTML = `<span>${r.emoji}</span><span class="font-semibold text-slate-400">${r.count}</span>`;
                // Add click event for self-toggle
                badge.addEventListener('click', () => applyReaction(r.emoji, listElement.closest('.message-row')));
                listElement.appendChild(badge);
            });
        }

        async function applyReaction(emoji, messageRow) {
            if (!messageRow) return;
            const chatId = messageRow.dataset.id;
            if (chatId === 'temp-id') return; // Cannot react before message is saved

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

        // Delegate all reaction trigger clicks and emoji clicks
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('.reaction-trigger-btn');
            if (trigger) {
                e.stopPropagation();
                const row = trigger.closest('.message-row');
                if (!globalPopover.classList.contains('hidden') && activeMessageRow === row) {
                    hideGlobalPopover();
                } else {
                    showGlobalPopover(trigger, row);
                }
                return;
            }

            const reactBtn = e.target.closest('.global-react-btn');
            if (reactBtn) {
                e.stopPropagation();
                const emoji = reactBtn.dataset.emoji || reactBtn.textContent.trim();
                applyReaction(emoji, activeMessageRow);
                hideGlobalPopover();
                return;
            }

            // Click outside: close popover
            if (!globalPopover.contains(e.target)) {
                hideGlobalPopover();
            }
        });

        // Close popover on scroll
        const msgContainer = document.getElementById('messages-container');
        if (msgContainer) {
            msgContainer.addEventListener('scroll', hideGlobalPopover, { passive: true });
        }

        function appendMessageToDOM(text, isOutgoing = true, chatId = 'temp-id') {
            if (!container) return;
            const timeStr = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            
            const alignClass = isOutgoing ? 'justify-end' : 'justify-start';
            const itemsClass = isOutgoing ? 'items-end' : 'items-start';
            const rowDirectionClass = isOutgoing ? 'flex-row-reverse' : 'flex-row';
            const bubbleClass = isOutgoing 
                ? 'bg-blue-600 text-white rounded-2xl rounded-br-sm' 
                : 'bg-slate-800 text-slate-50 rounded-2xl rounded-bl-sm border border-slate-700/50';
            const marginClass = isOutgoing ? 'self-end mr-9' : 'self-start ml-9';

            const messageHtml = `
                <div class="flex ${alignClass} message-row group" data-id="${chatId}">
                    <div class="flex flex-col ${itemsClass} max-w-[70%]">

                        <div class="flex items-end gap-2 ${rowDirectionClass}">
                            <button type="button" class="reaction-trigger-btn flex-shrink-0 w-7 h-7 rounded-full bg-slate-800 border border-slate-700/60 flex items-center justify-center text-slate-500 hover:text-slate-200 hover:bg-slate-700 opacity-0 group-hover:opacity-100 transition-all duration-150 self-end mb-0.5">
                                <i data-lucide="smile-plus" class="w-3.5 h-3.5 stroke-[1.5]"></i>
                            </button>
                            <div class="px-4 py-2.5 shadow-sm ${bubbleClass}">
                                <p class="text-sm leading-relaxed whitespace-pre-wrap">${escapeHtml(text)}</p>
                            </div>
                        </div>

                        <div class="reactions-list flex flex-wrap gap-1 mt-1.5 ${marginClass}"></div>

                        <span class="text-[11px] text-slate-500 mt-1 ${marginClass}">${timeStr}</span>
                    </div>
                </div>
            `;
            
            const wrapper = document.createElement('div');
            wrapper.innerHTML = messageHtml.trim();
            const newMsgNode = wrapper.firstChild;
            container.appendChild(newMsgNode);
            
            // Render Lucide icons for the new message
            if (window.lucide) {
                window.lucide.createIcons({
                    node: newMsgNode
                });
            }
            scrollToBottom();
        }
        
        scrollToBottom();
        
        // Search Contacts filtering
        const searchInput = document.getElementById('search-contacts');
        const contactItems = document.querySelectorAll('.contact-item');
        if (searchInput) {
            searchInput.addEventListener('input', () => {
                const query = searchInput.value.toLowerCase().trim();
                contactItems.forEach(item => {
                    const name = item.getAttribute('data-name').toLowerCase();
                    if (name.includes(query)) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });
        }
        
        // Toggle Right Info Panel
        const infoToggle = document.getElementById('info-toggle');
        const infoPanel = document.getElementById('info-panel');
        if (infoToggle && infoPanel) {
            infoToggle.addEventListener('click', () => {
                infoPanel.classList.toggle('hidden');
            });
        }

        // Profile Dropdown
        const profileTrigger = document.getElementById('profile-trigger');
        const profileMenu = document.getElementById('profile-menu');
        if (profileTrigger && profileMenu) {
            profileTrigger.addEventListener('click', (e) => {
                e.stopPropagation();
                profileMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!profileMenu.contains(e.target) && e.target !== profileTrigger && !profileTrigger.contains(e.target)) {
                    profileMenu.classList.add('hidden');
                }
            });
        }

        // New Message Modal
        const newChatBtn = document.getElementById('new-chat-btn');
        const newChatModal = document.getElementById('new-chat-modal');
        const newChatClose = document.getElementById('new-chat-close');
        const newChatBackdrop = document.getElementById('new-chat-backdrop');
        const newChatSearch = document.getElementById('new-chat-search');
        const newChatItems = document.querySelectorAll('.new-chat-item');

        if (newChatBtn && newChatModal) {
            newChatBtn.addEventListener('click', () => {
                newChatModal.classList.remove('hidden');
                if (newChatSearch) {
                    newChatSearch.value = '';
                    newChatSearch.focus();
                }
                newChatItems.forEach(item => item.classList.remove('hidden'));
            });

            const closeModal = () => {
                newChatModal.classList.add('hidden');
            };

            if (newChatClose) newChatClose.addEventListener('click', closeModal);
            if (newChatBackdrop) newChatBackdrop.addEventListener('click', closeModal);

            if (newChatSearch) {
                newChatSearch.addEventListener('input', () => {
                    const query = newChatSearch.value.toLowerCase().trim();
                    newChatItems.forEach(item => {
                        const name = item.getAttribute('data-name');
                        if (name && name.includes(query)) {
                            item.classList.remove('hidden');
                        } else {
                            item.classList.add('hidden');
                        }
                    });
                });
            }
        }
        
        // Emoji Picker
        const emojiToggle = document.getElementById('emoji-toggle');
        const emojiPicker = document.getElementById('emoji-picker');
        if (emojiToggle && emojiPicker) {
            emojiToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                emojiPicker.classList.toggle('hidden');
            });
            
            document.addEventListener('click', (e) => {
                if (!emojiPicker.contains(e.target) && e.target !== emojiToggle) {
                    emojiPicker.classList.add('hidden');
                }
            });
            
            const emojiButtons = emojiPicker.querySelectorAll('.emoji-btn');
            emojiButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (textarea) {
                        textarea.value += btn.textContent;
                        textarea.focus();
                    }
                    emojiPicker.classList.add('hidden');
                });
            });
        }
        
        // File selection simulation
        const fileInput = document.getElementById('file-input');
        const attachFileBtn = document.getElementById('attach-file-btn');
        const addImageBtn = document.getElementById('add-image-btn');
        const attachmentName = document.getElementById('attachment-name');
        const removeAttachmentBtn = document.getElementById('remove-attachment');
        
        if (fileInput && (attachFileBtn || addImageBtn)) {
            const triggerFile = () => fileInput.click();
            if (attachFileBtn) attachFileBtn.addEventListener('click', triggerFile);
            if (addImageBtn) addImageBtn.addEventListener('click', triggerFile);
            
            fileInput.addEventListener('change', () => {
                if (fileInput.files.length > 0) {
                    const names = Array.from(fileInput.files).map(f => f.name).join(', ');
                    attachmentName.textContent = names;
                    attachmentPreview.classList.remove('hidden');
                } else {
                    attachmentPreview.classList.add('hidden');
                }
            });
            
            if (removeAttachmentBtn) {
                removeAttachmentBtn.addEventListener('click', () => {
                    fileInput.value = '';
                    attachmentPreview.classList.add('hidden');
                });
            }
        }
        
        // Voice / Video Call Modal Simulation
        const callModal = document.getElementById('call-modal');
        const callPanel = document.getElementById('call-panel');
        const callDecline = document.getElementById('call-decline');
        const callAccept = document.getElementById('call-accept');
        const callAvatar = document.getElementById('call-avatar');
        const callContactName = document.getElementById('call-contact-name');
        const callStatus = document.getElementById('call-status');
        
        const voiceBtn = document.getElementById('voice-call-btn');
        const videoBtn = document.getElementById('video-call-btn');
        
        function startCall(video = false) {
            if (!callModal) return;
            const receiverName = "{{ $receiver ? $receiver->name : '' }}";
            callContactName.textContent = receiverName;
            callAvatar.textContent = receiverName.charAt(0).toUpperCase();
            callStatus.textContent = video ? 'Incoming Video Call...' : 'Ringing...';
            
            callModal.classList.remove('hidden');
            setTimeout(() => {
                callPanel.classList.remove('scale-95');
                callPanel.classList.add('scale-100');
            }, 50);
        }
        
        if (voiceBtn) voiceBtn.addEventListener('click', () => startCall(false));
        if (videoBtn) videoBtn.addEventListener('click', () => startCall(true));
        
        if (callDecline) {
            callDecline.addEventListener('click', () => {
                callPanel.classList.remove('scale-100');
                callPanel.classList.add('scale-95');
                setTimeout(() => {
                    callModal.classList.add('hidden');
                }, 200);
            });
        }
        
        if (callAccept) {
            callAccept.addEventListener('click', () => {
                callStatus.textContent = 'Connected';
                setTimeout(() => {
                    callDecline.click();
                }, 1500);
            });
        }
        
        if (textarea) {
            textarea.focus();
            
            document.addEventListener('keydown', (e) => {
                const activeEl = document.activeElement;
                if (activeEl && (activeEl.tagName === 'INPUT' || activeEl.tagName === 'TEXTAREA' || activeEl.isContentEditable)) {
                    return;
                }
                
                if (e.ctrlKey || e.altKey || e.metaKey || e.key.length > 1) {
                    return;
                }
                
                textarea.focus();
            });
            
            if (form) {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    
                    const message = textarea.value.trim();
                    if (!message) return;
                    
                    // Clear textarea immediately
                    textarea.value = '';
                    textarea.style.height = ''; // Reset height after send
                    textarea.rows = 1;
                    
                    // Hide file preview
                    if (attachmentPreview) {
                        attachmentPreview.classList.add('hidden');
                        fileInput.value = '';
                    }
                    
                    const formData = new FormData(form);
                    formData.set('message', message);
                    
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
                        } else {
                            throw new Error('Failed to send message');
                        }
                    })
                    .then(data => {
                        if (data.success && data.chat) {
                            appendMessageToDOM(message, true, data.chat.id);
                        }
                    })
                    .catch(error => {
                        console.error('Error sending message:', error);
                        textarea.value = message;
                    });
                });

                textarea.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        form.dispatchEvent(new Event('submit'));
                    }
                });
            }
        }
        
        // Listen for incoming messages and reactions
        if (window.Echo) {
            const currentUserId = {{ auth()->id() ?? 'null' }};
            const currentReceiverId = {{ $receiver ? $receiver->id : 'null' }};
            
            if (currentUserId) {
                window.Echo.private(`chat.${currentUserId}`)
                    .listen('MessageSent', (e) => {
                        if (currentReceiverId && e.chat.sender_id === currentReceiverId) {
                            appendMessageToDOM(e.chat.message, false, e.chat.id);
                        }
                    })
                    .listen('ReactionUpdated', (e) => {
                        const messageRow = document.querySelector(`.message-row[data-id="${e.chat_id}"]`);
                        if (messageRow) {
                            const list = messageRow.querySelector('.reactions-list');
                            if (list) {
                                renderReactions(list, e.reactions);
                            }
                        }
                    });
            }
        }
    });
</script>
</x-layout>
