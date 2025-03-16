<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <title>Group Chat</title>
    
    @vite(['resources/js/chat.js', 'resources/css/app.css'])

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-5">

    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-4">
        <h2 class="text-xl font-bold">Group Chat</h2>

        <!-- Group Selection -->
        <label for="group" class="block mt-3">Select Group:</label>
        <select id="group" class="w-full p-2 border rounded">
            @foreach ($groups as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
            @endforeach
        </select>

        <!-- Chat Messages -->
        <div id="messages" class="border rounded p-4 h-64 overflow-y-auto mt-3 bg-gray-50">
            <p class="text-gray-500">No messages yet...</p>
        </div>

        <!-- Message Input -->
        <input type="text" id="message" class="w-full p-2 border rounded mt-3" placeholder="Type a message...">
        <button id="sendBtn" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">Send</button>
    </div>

</body>
</html>