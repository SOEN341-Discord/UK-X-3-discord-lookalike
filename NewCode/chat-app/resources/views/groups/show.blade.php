@extends('layouts.app')

@section('content')
<div class="flex-grow p-6 ml-64">
<div class="container">
    <h1>{{ $group->name }}</h1>
    <div id="chat-box" style="border:1px solid #ccc; height:300px; overflow-y:scroll; padding:10px;">
        <ul id="messages">
            @foreach($group->messages as $message)
                <li><strong>{{ $message->user->name }}:</strong> {{ $message->message }}</li>
            @endforeach
        </ul>
    </div>
    <form id="message-form">
        @csrf
        <input type="text" name="message" id="message-input" placeholder="Type your message..." required>
        <button type="submit">Send</button>
    </form>
</div>
</div>
@endsection

@section('scripts')
<!-- Include Axios for AJAX requests -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.getElementById('message-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const messageInput = document.getElementById('message-input');
    
    function fetchMessages() {
    axios.get("{{ route('groups.messages.index', $group->id) }}")
        .then(function(response) {
            const messages = response.data;
            const messagesList = document.getElementById('messages');
            // Clear current messages (optional: you could also append only new ones)
            messagesList.innerHTML = '';
            messages.forEach(function(message) {
                const li = document.createElement('li');
                li.innerHTML = '<strong>' + message.user.name + ':</strong> ' + message.message;
                messagesList.appendChild(li);
            });
            // Auto-scroll the chat box to the bottom
            const chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(function(error) {
            console.error('Error fetching messages:', error);
        });
        }

        // Initial fetch when the page loads
        fetchMessages();
        // Set interval to poll for new messages every 5 seconds
        setInterval(fetchMessages, 5000);

    axios.post("{{ route('groups.messages.store', $group->id) }}", {
        message: messageInput.value
    })
    .then(function(response) {
        // Append new message to the list
        const message = response.data;
        const li = document.createElement('li');
        li.innerHTML = '<strong>{{ Auth::user()->name }}:</strong> ' + message.message;
        document.getElementById('messages').appendChild(li);
        messageInput.value = '';
        
        // Scroll chat-box to the bottom
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    })
    .catch(function(error) {
        console.error('Error sending message:', error);
    });
});
</script>
<script>
// Listen on the channel for this group
window.Echo.channel('group.{{ $group->id }}')
    .listen('MessageSent', (e) => {
        const li = document.createElement('li');
        li.innerHTML = '<strong>' + e.message.user.name + ':</strong> ' + e.message.message;
        document.getElementById('messages').appendChild(li);

        // Auto-scroll the chat box to the bottom
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    });
</script>

@endsection
