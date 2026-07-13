<x-layout>
<div class="relative flex h-screen overflow-hidden bg-[#0d0f14]" id="chatbox-app">
    {{-- Glowing Gradient Mesh Background --}}
    <div class="pointer-events-none absolute -top-40 -left-40 w-96 h-96 rounded-full bg-violet-600/10 blur-[120px]"></div>
    <div class="pointer-events-none absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-indigo-600/10 blur-[120px]"></div>

    {{-- ===================== SIDEBAR ===================== --}}
    <aside class="w-72 flex-shrink-0 flex flex-col bg-[#12151c] border-r border-white/5">

        {{-- Brand --}}
        <div class="px-5 py-4 flex items-center gap-3 border-b border-white/5">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-violet-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>
            <span class="text-lg font-semibold tracking-tight bg-gradient-to-r from-violet-400 to-indigo-400 bg-clip-text text-transparent">ChatBox</span>
            <span class="ml-auto text-[10px] font-semibold bg-violet-500/20 text-violet-400 px-2 py-0.5 rounded-full border border-violet-500/20">Beta</span>
        </div>

        {{-- Search --}}
        <div class="px-4 pt-4 pb-2">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                </svg>
                <input id="search-input" type="text" placeholder="Search contacts..." class="w-full bg-white/5 border border-white/8 rounded-xl pl-9 pr-4 py-2 text-sm text-white/80 placeholder-white/25 focus:outline-none focus:border-violet-500/50 focus:bg-white/8 transition-all duration-200">
            </div>
        </div>

        {{-- Section Label --}}
        <div class="px-5 pt-3 pb-1 flex items-center justify-between">
            <span class="text-[10px] font-semibold uppercase tracking-widest text-white/25">Contacts</span>
            <span class="text-[10px] text-white/20" id="contacts-count">0 online</span>
        </div>

        {{-- Contact List --}}
        <nav class="flex-1 overflow-y-auto px-2 py-1 space-y-0.5 scrollbar-thin" id="contact-list">
            {{-- Populated by JS --}}
        </nav>

        {{-- Profile Info Panel (hidden by default) --}}
        <div id="profile-panel" class="hidden border-t border-white/5 bg-[#0e1017] px-4 py-4 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-sm font-bold text-white shadow-lg shadow-violet-500/25">Me</div>
                <div>
                    <p class="text-sm font-semibold text-white">You</p>
                    <p class="text-xs text-white/40">your@email.com</p>
                </div>
            </div>
            <div class="border-t border-white/5 pt-3 space-y-1.5">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-white/25 mb-2">User Settings</p>
                <button class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-left text-xs text-white/60 hover:bg-white/5 hover:text-white transition-all duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Account Profile
                </button>
                <button class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-left text-xs text-white/60 hover:bg-white/5 hover:text-white transition-all duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white/30" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Settings & Preferences
                </button>
                <button class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-left text-xs text-red-400/70 hover:bg-red-500/5 hover:text-red-400 transition-all duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Log Out
                </button>
            </div>
        </div>

        {{-- Current User (clickable) --}}
        <button id="profile-btn" class="px-4 py-3 border-t border-white/5 flex items-center gap-3 hover:bg-white/3 transition-colors duration-200 w-full text-left">
            <div class="relative">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-xs font-bold text-white">Me</div>
                <span id="my-status-dot" class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-[#12151c]"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">You</p>
                <p id="my-status-text" class="text-xs text-white/35 truncate">Active now</p>
            </div>
            <svg id="profile-chevron" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white/20 flex-shrink-0 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
            </svg>
        </button>
    </aside>

    {{-- ===================== MAIN CHAT AREA ===================== --}}
    <main class="flex-1 flex flex-col min-w-0 bg-[#0d0f14]">

        {{-- Empty State --}}
        <div id="no-chat-state" class="flex-1 flex flex-col items-center justify-center gap-3 text-center px-8">
            <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-violet-500/10 to-indigo-600/10 border border-violet-500/10 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-violet-500/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
            </div>
            <p class="text-white/50 text-sm font-semibold">Select a contact to start chatting</p>
            <p class="text-white/20 text-xs">Your messages will appear here</p>
        </div>

        {{-- Active Chat --}}
        <div id="chat-area" class="flex-1 flex min-h-0 hidden">

            {{-- Chat Column --}}
            <div class="flex-1 flex flex-col min-w-0 relative">
                
                {{-- Blocked State Overlay --}}
                <div id="blocked-overlay" class="absolute inset-0 bg-[#0d0f14]/90 backdrop-blur-sm z-40 flex flex-col items-center justify-center gap-3 hidden">
                    <div class="w-14 h-14 rounded-full bg-red-500/10 border border-red-500/20 flex items-center justify-center text-red-500 shadow-lg shadow-red-500/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <p class="text-white/60 text-sm font-semibold">You have blocked this contact</p>
                    <button id="unblock-btn" class="px-4 py-1.5 rounded-xl bg-violet-600 hover:bg-violet-500 text-xs text-white font-semibold transition-all duration-150 shadow-md">Unblock</button>
                </div>

                {{-- Chat Header --}}
                <header class="flex items-center gap-4 px-6 py-3.5 border-b border-white/5 bg-[#0d0f14]/80 backdrop-blur-xl flex-shrink-0">
                    <div class="relative flex-shrink-0">
                        <div id="header-avatar" class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold text-white shadow-lg"></div>
                        <span id="header-status-dot" class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 rounded-full border-2 border-[#0d0f14]"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 id="header-name" class="text-base font-semibold text-white truncate leading-tight"></h1>
                        <div class="flex items-center gap-2 mt-0.5" id="header-status-wrapper">
                            <p class="text-xs text-emerald-400 flex items-center gap-1.5" id="header-status-text">
                                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse inline-block"></span>
                                Active now
                            </p>
                            <input id="chat-search-input" type="text" placeholder="Search in chat..." class="hidden w-40 bg-white/5 border border-white/8 rounded-lg px-2 py-0.5 text-xs text-white/80 placeholder-white/20 focus:outline-none focus:border-violet-500/50">
                        </div>
                    </div>

                    {{-- Header Action Buttons --}}
                    <div class="flex items-center gap-1 flex-shrink-0">
                        {{-- Voice call --}}
                        <button title="Voice Call" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-emerald-500/15 hover:text-emerald-400 text-white/35 flex items-center justify-center transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </button>
                        {{-- Video call --}}
                        <button title="Video Call" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-violet-500/15 hover:text-violet-400 text-white/35 flex items-center justify-center transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                        {{-- Search in chat --}}
                        <button id="chat-search-btn" title="Search in chat" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 text-white/35 hover:text-white/70 flex items-center justify-center transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                            </svg>
                        </button>
                        {{-- Info panel toggle --}}
                        <button id="info-toggle" title="Contact Info" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-white/10 text-white/35 hover:text-white/70 flex items-center justify-center transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20A10 10 0 0112 2z"/>
                            </svg>
                        </button>
                    </div>
                </header>

                {{-- Messages --}}
                <div class="flex-1 overflow-y-auto px-6 py-4 scrollbar-thin" id="messages-container">
                    <div id="scroll-anchor"></div>
                </div>

                {{-- Input Bar --}}
                <div class="px-5 py-4 border-t border-white/5 flex-shrink-0">

                    {{-- Emoji picker --}}
                    <div id="emoji-picker" class="hidden mb-3 bg-[#1a1d26] border border-white/8 rounded-2xl p-3 shadow-2xl">
                        <div class="grid grid-cols-8 gap-1">
                            @foreach(['😊','😂','❤️','👍','🔥','✅','🚀','💬','🎉','😎','🤔','👋','💡','⚡','🌟','😍'] as $emoji)
                            <button class="emoji-btn text-xl hover:bg-white/10 rounded-lg p-1.5 transition-colors duration-150">{{ $emoji }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-end gap-2 bg-[#1a1d26] border border-white/8 rounded-2xl px-3 py-3 focus-within:border-violet-500/40 focus-within:bg-[#1e2133] transition-all duration-200 shadow-lg">

                        {{-- Attachment (decorative) --}}
                        <button title="Attach file" class="flex-shrink-0 w-8 h-8 flex items-center justify-center text-white/25 hover:text-violet-400 hover:bg-violet-500/10 rounded-xl transition-all duration-200 mb-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                        </button>

                        {{-- Image (decorative) --}}
                        <button title="Send image" class="flex-shrink-0 w-8 h-8 flex items-center justify-center text-white/25 hover:text-sky-400 hover:bg-sky-500/10 rounded-xl transition-all duration-200 mb-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </button>

                        {{-- Textarea --}}
                        <textarea id="message-input" rows="1"
                            placeholder="Type a message..."
                            class="flex-1 bg-transparent text-sm text-white/90 placeholder-white/25 resize-none border-0 focus:ring-0 focus:outline-none leading-relaxed max-h-32 scrollbar-thin mx-1"></textarea>

                        {{-- Gif (decorative) --}}
                        <button title="GIF" class="flex-shrink-0 w-8 h-8 flex items-center justify-center text-white/25 hover:text-amber-400 hover:bg-amber-500/10 rounded-xl transition-all duration-200 mb-0.5 text-[10px] font-bold">
                            GIF
                        </button>

                        {{-- Emoji --}}
                        <button id="emoji-btn" title="Emoji" class="flex-shrink-0 w-8 h-8 flex items-center justify-center text-white/25 hover:text-amber-400 hover:bg-amber-500/10 rounded-xl transition-all duration-200 mb-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </button>

                        {{-- Send --}}
                        <button id="send-btn" title="Send" class="flex-shrink-0 w-8 h-8 rounded-xl bg-gradient-to-br from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 flex items-center justify-center shadow-lg shadow-violet-500/30 transition-all duration-200 hover:scale-105 active:scale-95 mb-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>

                    <p class="text-center text-[10px] text-white/10 mt-2 tracking-wide">End-to-end encrypted</p>
                </div>
            </div>

            {{-- ===================== INFO PANEL ===================== --}}
            <aside id="info-panel" class="hidden w-64 flex-shrink-0 border-l border-white/5 bg-[#12151c] flex flex-col overflow-y-auto scrollbar-thin">

                {{-- Contact card --}}
                <div class="flex flex-col items-center px-5 pt-6 pb-4 border-b border-white/5">
                    <div id="info-avatar" class="w-16 h-16 rounded-2xl flex items-center justify-center text-xl font-bold text-white shadow-xl mb-3"></div>
                    <p id="info-name" class="text-sm font-semibold text-white mb-0.5"></p>
                    <p class="text-xs text-emerald-400 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse inline-block"></span>
                        Active now
                    </p>

                    {{-- Quick actions --}}
                    <div class="flex gap-3 mt-4">
                        <button title="Voice" class="flex flex-col items-center gap-1 group">
                            <div class="w-10 h-10 rounded-xl bg-white/5 group-hover:bg-emerald-500/15 flex items-center justify-center text-white/35 group-hover:text-emerald-400 transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] text-white/25">Call</span>
                        </button>
                        <button title="Video" class="flex flex-col items-center gap-1 group">
                            <div class="w-10 h-10 rounded-xl bg-white/5 group-hover:bg-violet-500/15 flex items-center justify-center text-white/35 group-hover:text-violet-400 transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="text-[10px] text-white/25">Video</span>
                        </button>
                        <button title="Mute" class="flex flex-col items-center gap-1 group">
                            <div class="w-10 h-10 rounded-xl bg-white/5 group-hover:bg-red-500/15 flex items-center justify-center text-white/35 group-hover:text-red-400 transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15zM17 14l-5-5m0 5l5-5"/>
                                </svg>
                            </div>
                            <span class="text-[10px] text-white/25">Mute</span>
                        </button>
                    </div>
                </div>

                {{-- Shared media (decorative) --}}
                <div class="px-4 py-3 border-b border-white/5">
                    <p class="text-[10px] font-semibold uppercase tracking-widest text-white/25 mb-2">Shared Media</p>
                    <div class="grid grid-cols-3 gap-1">
                        <div class="aspect-square rounded-lg bg-gradient-to-br from-violet-500/20 to-indigo-600/20 border border-white/5 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01"/>
                            </svg>
                        </div>
                        <div class="aspect-square rounded-lg bg-gradient-to-br from-pink-500/20 to-rose-500/20 border border-white/5 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.723v6.554a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="aspect-square rounded-lg bg-white/5 border border-white/5 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <button class="w-full mt-2 text-[11px] text-white/25 hover:text-white/50 transition-colors duration-150">See all media →</button>
                </div>

                {{-- Notifications (decorative) --}}
                <div class="px-4 py-3 border-b border-white/5">
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-white/50">Notifications</p>
                        <div class="w-9 h-5 bg-emerald-500/20 rounded-full relative border border-emerald-500/30 cursor-pointer">
                            <div class="absolute right-0.5 top-0.5 w-4 h-4 rounded-full bg-emerald-400 shadow-sm"></div>
                        </div>
                    </div>
                </div>

                {{-- Block / Report (decorative) --}}
                <div class="px-4 py-3 space-y-1">
                    <button id="block-contact-btn" class="w-full flex items-center gap-2 px-2 py-2 rounded-xl text-left text-xs text-white/30 hover:bg-white/5 hover:text-white/60 transition-all duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Block Contact
                    </button>
                    <button class="w-full flex items-center gap-2 px-2 py-2 rounded-xl text-left text-xs text-red-400/50 hover:bg-red-500/5 hover:text-red-400 transition-all duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                        Report
                    </button>
                </div>
            </aside>
        </div>
    </main>
</div>

{{-- ===================== CALL OVERLAY MODAL ===================== --}}
<div id="call-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 hidden">
    {{-- Backdrop --}}
    <div id="call-backdrop" class="absolute inset-0 bg-black/75 backdrop-blur-md"></div>
    
    {{-- Panel --}}
    <div class="relative w-full max-w-sm bg-[#16181f]/90 border border-white/10 rounded-3xl p-6 shadow-2xl text-center backdrop-blur-xl transition-all duration-300 transform scale-95" id="call-panel">
        <div class="relative w-24 h-24 mx-auto mb-6">
            {{-- Pulsing ring animation --}}
            <div class="absolute inset-0 rounded-full bg-violet-500/20 animate-ping"></div>
            <div class="absolute -inset-2 rounded-full border border-violet-500/10 animate-pulse"></div>
            <div id="call-avatar" class="relative w-full h-full rounded-full flex items-center justify-center text-3xl font-bold text-white shadow-xl">JJ</div>
        </div>
        
        <h2 id="call-contact-name" class="text-lg font-semibold text-white mb-1">Jan Joshua Cubelo Bading</h2>
        <p id="call-status" class="text-xs text-white/40 mb-8 tracking-wide">Ringing...</p>
        
        <div class="flex justify-center gap-4">
            <button id="call-decline" class="w-12 h-12 rounded-full bg-rose-500 hover:bg-rose-600 flex items-center justify-center text-white shadow-lg shadow-rose-500/30 transition-all duration-200 hover:scale-105 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 rotate-[135deg]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </button>
            <button id="call-accept" class="w-12 h-12 rounded-full bg-emerald-500 hover:bg-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 transition-all duration-200 hover:scale-105 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </button>
        </div>
    </div>
</div>
</x-layout>
