<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Group; // assuming you have a Group model
use App\Events\GroupMessageBroadcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupMessagesController extends Controller
{
    // Display the group chat for a given group
    public function index($groupId)
    {
        $group = Group::findOrFail($groupId);
        $messages = Message::where('group_id', $groupId)
                           ->orderBy('created_at', 'asc')
                           ->get();

        return view('group-messages.index', compact('group', 'messages'));
    }

    // Store a new group message and broadcast it
    public function store(Request $request, $groupId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        // Ensure the group exists
        $group = Group::findOrFail($groupId);

        $message = Message::create([
            'user_id'  => Auth::id(),
            'group_id' => $groupId,
            'content'  => $request->message,
        ]);

        broadcast(new GroupMessageBroadcast($message))->toOthers();

        return response()->json($message);
    }

    // Broadcast method for AJAX requests – creates the message then returns a rendered partial view
    public function broadcast(Request $request, $groupId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'user_id'  => Auth::id(),
            'group_id' => $groupId,
            'content'  => $request->message,
        ]);

        broadcast(new GroupMessageBroadcast($message))->toOthers();

        return view('group-messages.message', ['message' => $message]);
    }

    // Method to handle receiving broadcasted messages via AJAX
    public function receive(Request $request, $groupId)
    {
        return view('group-messages.message', ['message' => $request->get('message')]);
    }

    // Optional: Allow deletion of messages if the user is admin or the message owner
    public function destroy(Message $message)
    {
        if (Auth::user()->is_admin || Auth::id() === $message->user_id) {
            $message->delete();
            return response()->json(['success' => 'Message deleted successfully.']);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
