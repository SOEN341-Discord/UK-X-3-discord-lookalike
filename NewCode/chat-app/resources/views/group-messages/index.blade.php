@extends('layouts.app')

@section('content')
<div class="flex-grow p-6 ml-64">
    <head>
        <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    </head>
    <!-- Group Chat Container -->
    <div class="chat">
        <!-- Header (Group Information) -->
        <div class="top">
            <img src="{{ $group->avatar_url ?? 'https://via.placeholder.com/50' }}" alt="Group Avatar">
            <div>
                <p>{{ $group->name }}</p>
                <small>Group Chat</small>
            </div>
        </div>

        <!-- Chat Messages Area -->
        <div class="messages">
            @foreach($messages as $message)
                @include('group-messages.message', ['message' => $message])
            @endforeach
        </div>

        <!-- Footer (Message Input Section) -->
        <div class="bottom">
            <form id="groupMessageForm">
                <input type="text" id="message" name="message" placeholder="Enter message..." autocomplete="off">
                <button type="submit">Send</button>
            </form>
        </div>
    </div>
</div>
 
<script>
  // Initialize Pusher and subscribe to a channel named after the group ID
  const pusher = new Pusher('{{ config("broadcasting.connections.pusher.key") }}', {
    cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
    forceTLS: true,
});
  const channel = pusher.subscribe('group.{{ $group->id }}');

  // Listen for group chat events and append new messages to the chat window
  channel.bind('group-chat', function(data) {
    $.post("/group/{{ $group->id }}/receive", {
      _token: '{{ csrf_token() }}',
      message: data.message,
    })
    .done(function(res) {
      $(".messages").append(res);
      $(document).scrollTop($(document).height());
    });
  });

  // Handle form submission to send and broadcast messages
  $("#groupMessageForm").submit(function(event) {
    event.preventDefault();
    $.ajax({
      url: "/group/{{ $group->id }}/broadcast",
      method: 'POST',
      headers: { 'X-Socket-Id': pusher.connection.socket_id },
      data: {
        _token: '{{ csrf_token() }}',
        message: $("#message").val(),
      }
    }).done(function(res) {
      $(".messages").append(res);
      $("#message").val('');
      $(document).scrollTop($(document).height());
    });
  });
</script>

<!-- Inline CSS for Chat Styling -->
<style>
    /* Chat Container */
    .chat {
        display: flex;
        flex-direction: column;
        height: 100vh;
        padding-right: 20px;
    }
    /* Header (Group Information) */
    .top {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    .top img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 10px;
    }
    .top div {
        display: flex;
        flex-direction: column;
    }
    .top p {
        margin: 0;
        font-weight: bold;
    }
    .top small {
        font-size: 12px;
        color: gray;
    }
    /* Chat Messages Area */
    .messages {
        flex-grow: 1;
        overflow-y: auto;
        padding: 10px;
        background-color: #f9f9f9;
        margin-bottom: 70px;
    }
    /* Footer (Message Input) */
    .bottom {
        width: 100%;
        padding: 10px;
        background-color: #f9f9f9;
        border-top: 1px solid #ddd;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .bottom form {
        display: flex;
        width: 100%;
        justify-content: space-between;
        align-items: center;
    }
    .bottom input {
        width: 85%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 20px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s ease;
    }
    .bottom input:focus {
        border-color: #007bff;
    }
    .bottom button {
        width: 15%;
        background-color: #007bff;
        border: none;
        color: white;
        padding: 10px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 16px;
    }
    .bottom button:hover {
        background-color: #0056b3;
    }
    .flex-grow {
        margin-left: 256px;
    }
</style>
@endsection
