<x-layout>
    <div class="chat-wrapper">
        <h1>Chatbox</h1>

        <div class="messages">
            @forelse($chats as $chat)
                <div class="message {{ $chat->user_id === auth()->id() ? 'mine' : 'theirs' }}">
                    <span class="author">{{ $chat->user->name }}</span>
                    <p>{{ $chat->message }}</p>
                    <small>{{ $chat->created_at->diffForHumans() }}</small>

                    @if($chat->user_id === auth()->id())
                        <form method="POST" action="{{ route('chat.destroy', $chat) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    @endif
                </div>
            @empty
                <p>No messages yet. Say something.</p>
            @endforelse
        </div>

        <form method="POST" action="{{ route('chat.store') }}" class="message-form">
            @csrf
            <input
                type="text"
                name="message"
                placeholder="Type a message..."
                maxlength="500"
                required
                autocomplete="off"
            />
            <button type="submit">Send</button>

            @error('message')
                <span class="error">{{ $message }}</span>
            @enderror
        </form>
    </div>
</x-layout>