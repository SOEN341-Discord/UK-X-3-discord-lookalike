@extends('layouts.app')

@section('content')
<div class="flex-grow p-6 ml-64">
<head>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>

</head>
    <!-- Chat -->
    <div class="chat">

        <!-- Header (Navigation bar) -->
        <div class="top">
            <img src="https://assets.edlin.app/images/rossedlin/03/rossedlin-03-100.jpg" alt="Avatar">
            <div>
                <p>Test User</p>
                <small>Online</small>
            </div>
        </div>

        <!-- Chat Messages Area -->
        <div class="messages">
            @include('message.receive', ['message' => "Hey! What's up! 👋"])
            @include('message.receive', ['message' => "Ask a friend to open this link and you can chat with them!"])
        </div>

        <!-- Footer -->
        <div class="">
            <form>
                <input type="text" id="message" name="message" placeholder="Enter message..." autocomplete="off">
                <button type="submit">Send</button>
            </form>
        </div>
    </div>
</div>

<script>
  const pusher  = new Pusher('{{config('broadcasting.connections.pusher.key')}}', {cluster: 'us2'});
  const channel = pusher.subscribe('public');

  //Receive messages
  channel.bind('chat', function (data) {
    $.post("/receive", {
      _token:  '{{csrf_token()}}',
      message: data.message,
    })
     .done(function (res) {
       $(".messages > .message").last().after(res);
       $(document).scrollTop($(document).height());
     });
  });

  //Broadcast messages
  $("form").submit(function (event) {
    event.preventDefault();
    
    let conversation_id = 1;
    
    $.ajax({
      url:     "/broadcast",
      method:  'POST',
      headers: {
        'X-Socket-Id': pusher.connection.socket_id
      },
      data:    {
        _token:  '{{csrf_token()}}',
        message: $("form #message").val(),
      }
    }).done(function (res) {
      $(".messages > .message").last().after(res);
      $("form #message").val('');
      $(document).scrollTop($(document).height());
    });
  });

</script>


<!-- Inline CSS -->
<style>

/* Chat Container */
.chat {
    display: flex;
    flex-direction: column;
    height: 100vh; /* Full height of the viewport */
    padding-right: 20px; /* Ensure the right side has space for the message input */
}

/* Header (Navigation bar) */
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
    flex-grow: 1; /* Makes the messages section grow to fill available space */
    overflow-y: auto;
    padding: 10px;
    background-color: #f9f9f9;
    margin-bottom: 70px; /* Prevents footer from overlapping messages */
}

/* Footer (Message Input Section) */
.bottom {
    width: 100%;
    padding: 10px;
    background-color: #f9f9f9;
    border-top: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
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

/* Input Field Focus Effect */
.bottom input:focus {
    border-color: #007bff; /* Blue border on focus */
}

/* Send Button */
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

/* Button Hover Effect */
.bottom button:hover {
    background-color: #0056b3;
}

/* Ensure there is no overlap with the navigation bar */
.flex-grow {
    margin-left: 256px; /* Adjust based on the width of your navigation bar */
}

</style>

@endsection
