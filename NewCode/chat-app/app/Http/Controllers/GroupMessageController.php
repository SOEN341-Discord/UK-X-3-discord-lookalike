<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\GroupMessageSent;


class GroupMessageController extends Controller
{
    // Return messages for a specific group (could be used via AJAX)
    public function index($groupId)
    {
        $group = Group::findOrFail($groupId);

        if (!$group->users->contains(Auth::id())) {
            abort(403, 'Unauthorized access');
        }

        $messages = $group->messages()->with('user')->orderBy('created_at')->get();
        return response()->json($messages);
    }

    // Store a new message in the group
    public function store(Request $request, $groupId)
{
    $group = Group::findOrFail($groupId);
    if (!$group->users->contains(Auth::id())) {
        abort(403, 'Unauthorized access');
    }
    $request->validate([
        'message' => 'required|string',
    ]);
    $message = GroupMessage::create([
        'group_id' => $group->id,
        'user_id'  => Auth::id(),
        'message'  => $request->message,
    ]);
    broadcast(new GroupMessageSent($message))->toOthers();

    return response()->json($message);
}

public function destroy(\App\Models\Group $group, \App\Models\GroupMessage $message)
{
    if (!auth()->user()->is_admin) {
        abort(403, 'Unauthorized');
    }

    if ($message->group_id !== $group->id) {
        abort(404, 'Message not found in this group');
    }

    $message->delete();

    // Redirect back to the group chat or wherever appropriate
    return redirect()->route('groups.show', $group->id)
                     ->with('success', 'Message deleted.');
}

}
