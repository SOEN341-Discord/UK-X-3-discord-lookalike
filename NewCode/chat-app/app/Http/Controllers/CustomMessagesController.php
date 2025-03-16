<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Chatify\Http\Controllers\MessagesController as ChatifyMessagesController;
use App\Models\Message;
use App\Events\PrivateMessageSent;
use App\Events\GroupMessageSent;

class CustomMessagesController extends ChatifyMessagesController
{
    public function send(Request $request)
{
    $message = new Message();
    $message->from_id = auth()->id();
    $message->to_id = $request->to_id ?? null;  // Private chat
    $message->group_id = $request->group_id ?? null;  // Group chat
    $message->message = $request->message;
    $message->save();

    // Broadcast to the appropriate channel
    if ($message->group_id) {
        broadcast(new GroupMessageSent($message))->toOthers();
    } else {
        broadcast(new PrivateMessageSent($message))->toOthers();
    }

    return response()->json(['status' => 'Message Sent']);
}
}