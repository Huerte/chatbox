<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <h2 class="text-xl font-semibold mb-4">Chat with {{ $receiver->name }}</h2>

        <div class="bg-white shadow rounded-lg p-4 mb-4 h-96 overflow-y-auto">
            @foreach ($messages as $chat)
                <div class="mb-4 {{ $chat->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                    <span class="inline-block px-4 py-2 rounded-lg {{ $chat->sender_id === auth()->id() ? 'bg-blue-500 text-black' : 'bg-gray-200 text-gray-900' }}">
                        {{ $chat->message }}
                    </span>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ $chat->created_at->diffForHumans() }}
                    </div>
                </div>
            @endforeach
        </div>

        <form method="POST" action="{{ route('chat.store', $receiver->id) }}">
            @csrf
            <div class="flex gap-2">
                <input type="text" name="message" class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" placeholder="Type a message..." required autofocus>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Send
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
