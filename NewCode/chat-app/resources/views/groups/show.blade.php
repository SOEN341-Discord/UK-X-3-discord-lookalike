@extends('layouts.app')

@section('content')
<div class="flex-grow p-6 ml-64">
<div class="flex flex-col min-h-screen bg-gray-100 text-gray-800">
    <!-- Page Header -->
    <header class="bg-blue-900 text-white px-6 py-4 shadow">
        <h1 class="text-2xl font-bold">{{ $group->name }}</h1>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow p-6 md:p-10">
        <div class="max-w-3xl mx-auto bg-white rounded shadow-md p-6 flex flex-col h-full">
            <div id="chat-box"
                class="flex-grow border border-gray-300 rounded p-4 mb-4 overflow-y-auto"
                style="max-height: 400px;">
                <ul id="messages" class="space-y-2">
                    @foreach($group->messages as $message)
                        <li class="flex items-center justify-between">
                            <div>
                                <strong class="text-blue-900">{{ $message->user->name }}:</strong>
                                <span class="text-gray-700">{{ $message->message }}</span>
                            </div>
                            @if(Auth::user()->is_admin)
                            <div class="relative inline-block">
                                <!-- Trigger Button (three dots) -->
                                <button type="button"
                                        onclick="document.getElementById('dropdown-{{ $message->id }}').classList.toggle('hidden')"
                                        class="inline-flex items-center px-2 py-1 text-gray-500 hover:text-gray-700 rounded focus:outline-none transition ease-in-out duration-150">
                                    <span class="text-xl font-bold">...</span>
                                </button>

                                <!-- Dropdown (hidden by default) -->
                                <div id="dropdown-{{ $message->id }}"
                                    class="hidden absolute right-0 mt-2 w-24 bg-white border border-gray-200 rounded shadow-md py-1 z-10">
                                    <!-- Delete Form -->
                                    <form action="{{ route('groups.messages.destroy', [$group->id, $message->id]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this message?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- Message Form -->
            <form id="message-form" method="POST" class="flex items-center space-x-2">
                @csrf
                <input
                    type="text"
                    name="message"
                    id="message-input"
                    placeholder="Type your message..."
                    required
                    class="flex-grow border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                >
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500 transition"
                >
                    Send
                </button>
            </form>
        </div>
    </main>
</div>
</div>
@endsection

@section('scripts')
<!-- Include Axios for AJAX requests -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('message-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        const messageInput = document.getElementById('message-input');
        const message = messageInput.value;
        
        axios.post("{{ route('groups.messages.store', $group->id) }}", {
            message: message
        })
        .then(function(response) {
            // Get the new message from the response
            const newMessage = response.data;
            // Append the new message to the chat list
            const messagesList = document.getElementById('messages');
            const li = document.createElement('li');
            li.innerHTML = '<strong>' + newMessage.user.name + ':</strong> ' + newMessage.message;
            messagesList.appendChild(li);
            
            // Clear the input field and scroll the chat box to the bottom
            messageInput.value = '';
            const chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(function(error) {
            console.error('Error sending message:', error);
        });
    });

    // Listen on the channel for this group (for real-time updates via Laravel Echo)
    window.Echo.channel('group.{{ $group->id }}')
        .listen('.GroupMessageSent', (e) => {
            const li = document.createElement('li');
            li.innerHTML = '<strong>' + e.message.user.name + ':</strong> ' + e.message.message;
            document.getElementById('messages').appendChild(li);

            // Auto-scroll the chat box to the bottom
            const chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;
        });
});
</script>
@endsection
