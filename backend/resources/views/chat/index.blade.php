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
            <button class="p-2 text-slate-400 hover:text-slate-50 transition-colors">
                <i data-lucide="edit" class="w-4 h-4 stroke-[1.5]"></i>
            </button>
        </div>

        {{-- Search --}}
        <div class="p-4">
            <div class="relative flex items-center">
                <i data-lucide="search" class="absolute left-3 w-4 h-4 text-slate-500 stroke-[1.5]"></i>
                <input type="text" placeholder="Search contacts..." class="w-full bg-slate-900 border border-slate-800 rounded-md py-2 pl-9 pr-4 text-sm text-slate-50 placeholder-slate-500 focus:outline-none focus:border-blue-500 transition-colors">
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
                    <li>
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
        <div class="p-4 border-t border-slate-800 flex items-center justify-between bg-slate-950">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                </div>
                <div class="text-sm">
                    <p class="font-medium text-slate-50">{{ auth()->user()->name ?? 'User' }}</p>
                    <p class="text-xs text-slate-500">Online</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-2 text-slate-500 hover:text-red-400 transition-colors" title="Logout">
                    <i data-lucide="log-out" class="w-4 h-4 stroke-[1.5]"></i>
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CHAT --}}
    <main class="flex-1 flex flex-col min-w-0 bg-slate-900">
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
                    <button class="text-slate-400 hover:text-blue-500 transition-colors" title="Voice Call">
                        <i data-lucide="phone" class="w-5 h-5 stroke-[1.5]"></i>
                    </button>
                    <button class="text-slate-400 hover:text-blue-500 transition-colors" title="Video Call">
                        <i data-lucide="video" class="w-5 h-5 stroke-[1.5]"></i>
                    </button>
                    <div class="w-px h-6 bg-slate-800 mx-1"></div>
                    <button class="text-slate-400 hover:text-slate-200 transition-colors" title="Information">
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
                    <div class="flex {{ $isOutgoing ? 'justify-end' : 'justify-start' }}">
                        <div class="flex flex-col {{ $isOutgoing ? 'items-end' : 'items-start' }} max-w-[70%]">
                            <div class="px-4 py-2.5 shadow-sm {{ $isOutgoing ? 'bg-blue-600 text-white rounded-xl rounded-br-sm' : 'bg-slate-800 text-slate-50 rounded-xl rounded-bl-sm border border-slate-700/50' }}">
                                <p class="text-sm leading-relaxed">{{ $chat->message }}</p>
                            </div>
                            <span class="text-[11px] text-slate-500 mt-1 mx-1">{{ $chat->created_at->format('h:i A') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Input Area --}}
            <div class="p-4 border-t border-slate-800 bg-slate-900 flex-shrink-0">
                <form id="chat-form" method="POST" action="{{ route('chat.store', $receiver->id) }}" class="flex items-end gap-3">
                    @csrf
                    <div class="flex-1 flex items-end gap-2 bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 focus-within:border-blue-500/50 transition-colors">
                        <button type="button" class="p-1.5 text-slate-400 hover:text-slate-200 transition-colors flex-shrink-0" title="Attach file">
                            <i data-lucide="paperclip" class="w-5 h-5 stroke-[1.5]"></i>
                        </button>
                        <button type="button" class="p-1.5 text-slate-400 hover:text-slate-200 transition-colors flex-shrink-0" title="Add image">
                            <i data-lucide="image" class="w-5 h-5 stroke-[1.5]"></i>
                        </button>
                        
                        <textarea 
                            id="message-textarea"
                            name="message" 
                            rows="1" 
                            placeholder="Type your message..." 
                            required
                            class="flex-1 bg-transparent border-0 text-sm text-slate-50 placeholder-slate-400 focus:ring-0 resize-none py-1.5 px-1 max-h-32 scrollbar-thin outline-none"
                        ></textarea>
                        
                        <button type="button" class="p-1.5 text-slate-400 hover:text-slate-200 transition-colors flex-shrink-0" title="Emoji">
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
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const textarea = document.getElementById('message-textarea');
        const form = document.getElementById('chat-form');
        const container = document.getElementById('messages-container');
        
        if (container) {
            container.scrollTop = container.scrollHeight;
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
                textarea.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        if (textarea.value.trim() !== '') {
                            form.submit();
                        }
                    }
                });
            }
        }
    });
</script>
</x-layout>
