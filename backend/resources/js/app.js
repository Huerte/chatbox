import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// ===== ChatBox — Simple Version =====

document.addEventListener('DOMContentLoaded', () => {

    // ---- Elements ----
    const contactList     = document.getElementById('contact-list');
    const noChatState     = document.getElementById('no-chat-state');
    const chatArea        = document.getElementById('chat-area');
    const messagesContainer = document.getElementById('messages-container');
    const scrollAnchor    = document.getElementById('scroll-anchor');
    const headerAvatar    = document.getElementById('header-avatar');
    const headerName      = document.getElementById('header-name');
    const messageInput    = document.getElementById('message-input');
    const sendBtn         = document.getElementById('send-btn');
    const emojiBtn        = document.getElementById('emoji-btn');
    const emojiPicker     = document.getElementById('emoji-picker');
    const searchInput     = document.getElementById('search-input');

    // ---- Per-contact message store ----
    const messageStore = {}; // { [contactId]: [{ text, sender }] }
    contacts.forEach(c => { messageStore[c.id] = []; });

    let activeId = null;
    const blockedContacts = {}; // { [contactId]: boolean }

    // =============================
    //  RENDER CONTACTS
    // =============================
    function renderContacts(filter = '') {
        contactList.innerHTML = '';
        const filtered = contacts.filter(c => c.name.toLowerCase().includes(filter.toLowerCase()));

        const contactsCountEl = document.getElementById('contacts-count');
        if (contactsCountEl) {
            contactsCountEl.textContent = `${filtered.length} online`;
        }

        if (filtered.length === 0) {
            contactList.innerHTML = `<p class="text-xs text-white/25 text-center py-6 px-4">No contacts found</p>`;
            return;
        }

        filtered.forEach(c => {
            const btn = document.createElement('button');
            btn.id = `contact-${c.id}`;
            btn.className = `conversation-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-left transition-all duration-200${c.id === activeId ? ' active-conv' : ''}`;
            const lastMsg = messageStore[c.id].at(-1);
            const preview = lastMsg ? (lastMsg.sender === 'me' ? 'You: ' : '') + lastMsg.text : 'No messages yet';

            let timeHtml = '';
            if (lastMsg && lastMsg.time) {
                const d = new Date(lastMsg.time);
                const timeStr = d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                timeHtml = `<span class="text-[10px] text-white/30 flex-shrink-0 ml-auto">${timeStr}</span>`;
            }

            const isBlocked = blockedContacts[c.id];
            const dotColor = isBlocked ? 'bg-white/20' : 'bg-emerald-400';

            btn.innerHTML = `
                <div class="relative flex-shrink-0">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br ${c.from} ${c.to} flex items-center justify-center text-xs font-bold text-white shadow-md">${c.initials}</div>
                    <span id="contact-status-dot-${c.id}" class="absolute bottom-0 right-0 w-2.5 h-2.5 ${dotColor} rounded-full border-2 border-[#12151c]"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-sm font-medium text-white/80 truncate">${escapeHtml(c.name)}</span>
                        ${timeHtml}
                    </div>
                    <p class="text-xs text-white/30 truncate">${escapeHtml(preview.slice(0, 40))}</p>
                </div>
            `;
            btn.addEventListener('click', () => setActive(c.id));
            contactList.appendChild(btn);
        });
    }

    // =============================
    //  SET ACTIVE CONTACT
    // =============================
    function setActive(id) {
        activeId = id;
        const contact = contacts.find(c => c.id === id);
        if (!contact) return;

        // Update sidebar active state
        document.querySelectorAll('.conversation-item').forEach(el => el.classList.remove('active-conv'));
        const btn = document.getElementById(`contact-${id}`);
        if (btn) btn.classList.add('active-conv');

        // Update header
        headerName.textContent = contact.name;
        headerAvatar.className = `w-10 h-10 rounded-full bg-gradient-to-br ${contact.from} ${contact.to} flex items-center justify-center text-sm font-bold text-white shadow-lg`;
        headerAvatar.textContent = contact.initials;

        // Block state elements update
        const isBlocked = blockedContacts[contact.id];
        const statusDot = document.getElementById('header-status-dot');
        const statusText = document.getElementById('header-status-text');
        const blockedOverlay = document.getElementById('blocked-overlay');
        const blockSidebarBtn = document.getElementById('block-contact-btn');

        if (isBlocked) {
            if (statusDot) statusDot.className = "absolute bottom-0 right-0 w-3 h-3 bg-white/20 rounded-full border-2 border-[#0d0f14]";
            if (statusText) statusText.innerHTML = `<span class="w-1.5 h-1.5 bg-white/20 rounded-full inline-block"></span> Blocked`;
            if (blockedOverlay) blockedOverlay.classList.remove('hidden');
            if (blockSidebarBtn) {
                blockSidebarBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Unblock Contact
                `;
                blockSidebarBtn.className = "w-full flex items-center gap-2 px-2 py-2 rounded-xl text-left text-xs text-emerald-400/80 hover:bg-emerald-500/5 hover:text-emerald-400 transition-all duration-150";
            }
        } else {
            if (statusDot) statusDot.className = "absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 rounded-full border-2 border-[#0d0f14]";
            if (statusText) statusText.innerHTML = `<span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse inline-block"></span> Active now`;
            if (blockedOverlay) blockedOverlay.classList.add('hidden');
            if (blockSidebarBtn) {
                blockSidebarBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                    Block Contact
                `;
                blockSidebarBtn.className = "w-full flex items-center gap-2 px-2 py-2 rounded-xl text-left text-xs text-white/30 hover:bg-white/5 hover:text-white/60 transition-all duration-150";
            }
        }

        // Hide search input if open when switching contacts
        const searchInputBox = document.getElementById('chat-search-input');
        if (searchInputBox) {
            searchInputBox.classList.add('hidden');
            searchInputBox.value = '';
        }
        if (statusText) statusText.classList.remove('hidden');

        // Update right info panel
        const infoName = document.getElementById('info-name');
        const infoAvatar = document.getElementById('info-avatar');
        if (infoName) infoName.textContent = contact.name;
        if (infoAvatar) {
            infoAvatar.className = `w-16 h-16 rounded-2xl bg-gradient-to-br ${contact.from} ${contact.to} flex items-center justify-center text-xl font-bold text-white shadow-xl mb-3`;
            infoAvatar.textContent = contact.initials;
        }

        // Show chat area
        noChatState.classList.add('hidden');
        chatArea.classList.remove('hidden');

        // Update input placeholder
        messageInput.placeholder = `Message ${contact.name}...`;

        // Render messages for this contact
        renderMessages(id);
        messageInput.focus();
    }

    // =============================
    //  RENDER MESSAGES
    // =============================
    function renderMessages(contactId) {
        // Clear current messages (keep scroll anchor)
        messagesContainer.querySelectorAll('.message-row, .date-divider').forEach(el => el.remove());

        const messages = messageStore[contactId] || [];

        if (messages.length === 0) {
            const divider = document.createElement('div');
            divider.className = 'date-divider flex items-center gap-3 my-6';
            divider.innerHTML = `
                <div class="flex-1 h-px bg-white/5"></div>
                <span class="text-[11px] text-white/20 font-medium px-2">Start of conversation</span>
                <div class="flex-1 h-px bg-white/5"></div>
            `;
            messagesContainer.insertBefore(divider, scrollAnchor);
            return;
        }

        // Date divider
        const divider = document.createElement('div');
        divider.className = 'date-divider flex items-center gap-3 my-4';
        divider.innerHTML = `
            <div class="flex-1 h-px bg-white/5"></div>
            <span class="text-[11px] text-white/25 font-medium px-2">Today</span>
            <div class="flex-1 h-px bg-white/5"></div>
        `;
        messagesContainer.insertBefore(divider, scrollAnchor);

        messages.forEach(msg => appendMessageDOM(contactId, msg.text, msg.sender, false));
        scrollToBottom(false);
    }

    // =============================
    //  SEND MESSAGE
    // =============================
    function sendMessage() {
        if (!activeId) return;
        const text = messageInput.value.trim();
        if (!text) return;

        messageStore[activeId].push({ text, sender: 'me', time: Date.now() });
        appendMessageDOM(activeId, text, 'me', true);

        messageInput.value = '';
        messageInput.style.height = 'auto';
        renderContacts(searchInput.value); // refresh preview
    }

    sendBtn.addEventListener('click', sendMessage);
    messageInput.addEventListener('keydown', e => {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
    });
    messageInput.addEventListener('input', () => {
        messageInput.style.height = 'auto';
        messageInput.style.height = Math.min(messageInput.scrollHeight, 128) + 'px';
    });

    // =============================
    //  APPEND MESSAGE TO DOM
    // =============================
    function appendMessageDOM(contactId, text, sender, animate = true) {
        const isMe = sender === 'me';
        const contact = contacts.find(c => c.id === contactId);
        const initials = contact?.initials ?? '?';
        const from = contact?.from ?? 'from-gray-500';
        const to   = contact?.to   ?? 'to-gray-600';

        const row = document.createElement('div');
        row.className = `message-row flex items-end gap-2.5 ${isMe ? 'justify-end' : ''} mt-4 relative group`;
        if (animate) row.style.animation = 'message-in 0.22s cubic-bezier(0.34,1.56,0.64,1) both';

        const timeStr = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        let statusHtml = '';
        if (isMe) {
            statusHtml = `
                <div class="flex items-center gap-1 mt-1 text-[9px] text-white/30 self-end">
                    <span>${timeStr}</span>
                    <span class="read-receipt text-white/20">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                </div>
            `;
        } else {
            statusHtml = `
                <div class="flex items-center gap-1 mt-1 text-[9px] text-white/30">
                    <span>${timeStr}</span>
                </div>
            `;
        }

        const bubble = isMe
            ? `<div class="bg-gradient-to-br from-violet-600 to-indigo-600 text-white rounded-2xl rounded-br-sm px-4 py-2.5 text-sm leading-relaxed shadow-lg shadow-violet-500/20 max-w-xs lg:max-w-md relative">${escapeHtml(text)}</div>`
            : `<div class="bg-[#1e2130] text-white/90 rounded-2xl rounded-bl-sm px-4 py-2.5 text-sm leading-relaxed shadow-sm max-w-xs lg:max-w-md relative">${escapeHtml(text)}</div>`;

        const avatar = `<div class="w-7 h-7 rounded-full bg-gradient-to-br ${from} ${to} flex-shrink-0 flex items-center justify-center text-[10px] font-bold text-white mb-0.5">${initials}</div>`;

        // Reactions container elements
        row.innerHTML = `
            ${!isMe ? avatar : ''}
            <div class="flex flex-col gap-0.5 ${isMe ? 'items-end' : ''} relative max-w-xs lg:max-w-md">
                
                <button class="reaction-trigger absolute ${isMe ? '-left-8' : '-right-8'} top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-all duration-150 w-6 h-6 rounded-full bg-[#1e2130] border border-white/8 flex items-center justify-center text-white/40 hover:text-white/80 hover:scale-110" title="React">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>

                <div class="message-emoji-panel hidden absolute ${isMe ? '-left-32' : '-right-32'} -top-9 bg-[#1c1f29] border border-white/10 rounded-full px-2 py-1 shadow-2xl flex gap-1 z-30">
                    <button class="quick-react-btn text-sm hover:scale-125 transition-transform duration-100">👍</button>
                    <button class="quick-react-btn text-sm hover:scale-125 transition-transform duration-100">❤️</button>
                    <button class="quick-react-btn text-sm hover:scale-125 transition-transform duration-100">😂</button>
                    <button class="quick-react-btn text-sm hover:scale-125 transition-transform duration-100">😮</button>
                    <button class="quick-react-btn text-sm hover:scale-125 transition-transform duration-100">🙏</button>
                </div>

                ${bubble}
                
                <div class="reactions-list flex flex-wrap gap-1 mt-1"></div>

                ${statusHtml}
            </div>
        `;

        // Wire reactions triggers
        const trigger = row.querySelector('.reaction-trigger');
        const panel = row.querySelector('.message-emoji-panel');
        const list = row.querySelector('.reactions-list');
        const activeReactions = {}; // { emoji: count }

        if (trigger && panel) {
            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                panel.classList.toggle('hidden');
            });

            // Close on document click
            document.addEventListener('click', () => panel.classList.add('hidden'));

            panel.querySelectorAll('.quick-react-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const emoji = btn.textContent;
                    
                    if (activeReactions[emoji]) {
                        // Toggle reaction off
                        delete activeReactions[emoji];
                    } else {
                        // Add reaction
                        activeReactions[emoji] = 1;
                    }

                    renderReactionsList();
                    panel.classList.add('hidden');
                });
            });
        }

        function renderReactionsList() {
            list.innerHTML = '';
            Object.keys(activeReactions).forEach(emoji => {
                const badge = document.createElement('div');
                badge.className = `flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] cursor-pointer transition-all duration-150 select-none bg-[#1e2130] border border-white/5 text-white/60 hover:border-violet-500/30 hover:text-white`;
                badge.innerHTML = `<span>${emoji}</span><span class="font-bold">${activeReactions[emoji]}</span>`;
                badge.addEventListener('click', () => {
                    delete activeReactions[emoji];
                    renderReactionsList();
                });
                list.appendChild(badge);
            });
        }

        messagesContainer.insertBefore(row, scrollAnchor);
        if (animate) scrollToBottom();

        // Animate checkmarks for user messages
        if (isMe && animate) {
            const receipt = row.querySelector('.read-receipt');
            
            // Deliver: Double checkmark (gray) after 800ms
            setTimeout(() => {
                if (receipt) {
                    receipt.className = "read-receipt text-white/30";
                    receipt.innerHTML = `
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7 M12 13l4 4L22 7" />
                        </svg>
                    `;
                }
            }, 800);

            // Read: Double checkmark (purple) after 1600ms
            setTimeout(() => {
                if (receipt) {
                    receipt.className = "read-receipt text-violet-400";
                }
            }, 1600);
        }
    }

    // =============================
    //  EMOJI PICKER
    // =============================
    emojiBtn.addEventListener('click', e => {
        e.stopPropagation();
        emojiPicker.classList.toggle('hidden');
    });
    document.querySelectorAll('.emoji-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            messageInput.value += btn.textContent;
            messageInput.dispatchEvent(new Event('input'));
            messageInput.focus();
            emojiPicker.classList.add('hidden');
        });
    });
    document.addEventListener('click', e => {
        if (!emojiPicker.contains(e.target) && e.target !== emojiBtn) {
            emojiPicker.classList.add('hidden');
        }
    });

    // =============================
    //  SEARCH
    // =============================
    searchInput.addEventListener('input', () => renderContacts(searchInput.value));

    // =============================
    //  HELPERS
    // =============================
    const scrollToBottom = (smooth = true) =>
        scrollAnchor.scrollIntoView({ behavior: smooth ? 'smooth' : 'instant', block: 'nearest', inline: 'nearest' });

    function escapeHtml(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str));
        return d.innerHTML;
    }

    // =============================
    //  PROFILE PANEL TOGGLE
    // =============================
    const profileBtn     = document.getElementById('profile-btn');
    const profilePanel   = document.getElementById('profile-panel');
    const profileChevron = document.getElementById('profile-chevron');
    const myStatusDot    = document.getElementById('my-status-dot');
    const myStatusText   = document.getElementById('my-status-text');

    profileBtn.addEventListener('click', () => {
        const isOpen = !profilePanel.classList.contains('hidden');
        profilePanel.classList.toggle('hidden', isOpen);
        profileChevron.style.transform = isOpen ? '' : 'rotate(180deg)';
    });

    // =============================
    //  INFO PANEL TOGGLE
    // =============================
    const infoToggle = document.getElementById('info-toggle');
    const infoPanel  = document.getElementById('info-panel');
    if (infoToggle && infoPanel) {
        infoToggle.addEventListener('click', () => {
            infoPanel.classList.toggle('hidden');
        });
    }

    // =============================
    //  CALL OVERLAY SIMULATION
    // =============================
    const callModal = document.getElementById('call-modal');
    const callDecline = document.getElementById('call-decline');
    const callAccept = document.getElementById('call-accept');
    const callAvatar = document.getElementById('call-avatar');
    const callContactName = document.getElementById('call-contact-name');
    const callStatus = document.getElementById('call-status');
    const callPanel = document.getElementById('call-panel');

    const voiceBtn = document.querySelector('button[title="Voice Call"]');
    const videoBtn = document.querySelector('button[title="Video Call"]');
    const sideVoiceBtn = document.querySelector('button[title="Voice"]');
    const sideVideoBtn = document.querySelector('button[title="Video"]');

    function startCallSimulation(video = false) {
        const contact = contacts.find(c => c.id === activeId);
        if (!contact) return;

        callContactName.textContent = contact.name;
        callAvatar.textContent = contact.initials;
        callAvatar.className = `relative w-full h-full rounded-full bg-gradient-to-br ${contact.from} ${contact.to} flex items-center justify-center text-3xl font-bold text-white shadow-xl`;
        callStatus.textContent = video ? 'Incoming Video Call...' : 'Ringing...';

        callModal.classList.remove('hidden');
        setTimeout(() => {
            callPanel.classList.remove('scale-95');
            callPanel.classList.add('scale-100');
        }, 10);
    }

    // Call simulation is disabled - buttons are purely decorative for now

    function endCallSimulation() {
        callPanel.classList.remove('scale-100');
        callPanel.classList.add('scale-95');
        setTimeout(() => {
            callModal.classList.add('hidden');
        }, 200);
    }

    if (callDecline) callDecline.addEventListener('click', endCallSimulation);
    if (callAccept) {
        callAccept.addEventListener('click', () => {
            callStatus.textContent = 'Connected';
            setTimeout(endCallSimulation, 1200);
        });
    }

    // =============================
    //  BLOCK CONTACT SIMULATION
    // =============================
    const blockContactBtn = document.getElementById('block-contact-btn');
    const blockedOverlay  = document.getElementById('blocked-overlay');
    const unblockBtn      = document.getElementById('unblock-btn');

    function toggleBlockContact() {
        if (!activeId) return;
        const currentBlocked = !blockedContacts[activeId];
        blockedContacts[activeId] = currentBlocked;

        // Re-run setActive to update the UI
        setActive(activeId);
        renderContacts(searchInput.value);
    }

    if (blockContactBtn) blockContactBtn.addEventListener('click', toggleBlockContact);
    if (unblockBtn) unblockBtn.addEventListener('click', toggleBlockContact);

    // =============================
    //  SEARCH IN CHAT HIGHLIGHTING
    // =============================
    const chatSearchBtn = document.getElementById('chat-search-btn');
    const chatSearchInput = document.getElementById('chat-search-input');
    const headerStatusText = document.getElementById('header-status-text');

    if (chatSearchBtn && chatSearchInput && headerStatusText) {
        chatSearchBtn.addEventListener('click', () => {
            const isHidden = chatSearchInput.classList.contains('hidden');
            if (isHidden) {
                headerStatusText.classList.add('hidden');
                chatSearchInput.classList.remove('hidden');
                chatSearchInput.focus();
            } else {
                chatSearchInput.classList.add('hidden');
                headerStatusText.classList.remove('hidden');
                chatSearchInput.value = '';
                highlightSearchMatches('');
            }
        });

        chatSearchInput.addEventListener('input', () => {
            highlightSearchMatches(chatSearchInput.value);
        });
    }

    function highlightSearchMatches(query) {
        const messageRows = messagesContainer.querySelectorAll('.message-row');
        messageRows.forEach(row => {
            const bubble = row.querySelector('.bg-gradient-to-br, .bg-\\[\\#1e2130\\]');
            if (!bubble) return;
            
            let text = bubble.getAttribute('data-original-text');
            if (!text) {
                text = bubble.innerText;
                bubble.setAttribute('data-original-text', text);
            }

            if (!query.trim()) {
                bubble.innerHTML = escapeHtml(text);
                return;
            }

            const regex = new RegExp(`(${escapeRegExp(query)})`, 'gi');
            const highlightedText = escapeHtml(text).replace(regex, '<mark class="bg-violet-500/40 text-white font-semibold rounded px-0.5">$1</mark>');
            bubble.innerHTML = highlightedText;
        });
    }

    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    // ---- Init ----
    renderContacts();
});
