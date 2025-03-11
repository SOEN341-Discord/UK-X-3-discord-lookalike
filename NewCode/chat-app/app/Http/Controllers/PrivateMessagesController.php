<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Channel;
use App\Events\MessageSent;
use App\Events\MessageBroadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivateMessagesController extends Controller
{
    /*public function private-messages($channelId)
    {
        $channel = Channel::findOrFail($channelId);
        $messages = Message::where('channel_id', $channelId)->orderBy('created_at', 'asc')->get();

        return view('channels.show', compact('channel', 'messages'));
    }*/

    public function index()
    {
        return view('private-messages');
    }

    public function broadcast(Request $request)
    {
        $conversation_id = 1;

        $validated = $request->validate([
            'message' => 'required|string',
            //'conversation_id' => 'required|exists:conversations,id',
        ]);

        // Save the message in the database
        $message = Message::create([
            'user_id' => Auth::id(),  // Get the authenticated user's ID
            //'conversation_id' => $validated['conversation_id'],
            'conversation_id' => $conversation_id,
            'content' => $validated['message'],
        ]);

        broadcast(new MessageBroadcast($request->get('message')))->toOthers();
        return view('message.broadcast', ['message' =>$request->get('message')]);
    }

    public function receive(Request $request)
    {
        return view('message.receive', ['message' =>$request->get('message')]);
    }



    public function store(Request $request)
    {
        $request->validate([
            'channel_id' => 'required|exists:channels,id',
            'content' => 'required|string',
        ]);

        $message = Message::create([
            'user_id' => Auth::id(),
            'channel_id' => $request->channel_id,
            'content' => $request->content,
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message);
    }

    public function destroy(Message $message)
    {
        if (Auth::user()->is_admin || Auth::id() === $message->user_id) {
            $message->delete();
            return response()->json(['success' => 'Message deleted successfully.']);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}

