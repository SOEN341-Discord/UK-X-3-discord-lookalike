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
        <form id="message-form" method="POST">
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
        .listen('MessageSent', (e) => {
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
