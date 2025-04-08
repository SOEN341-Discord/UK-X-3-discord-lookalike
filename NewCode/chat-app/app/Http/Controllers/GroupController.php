<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    // List groups the current user belongs to
    public function index()
    {
        $groups = Auth::user()->groups;
        return view('groups.index', compact('groups'));
    }

    // Show the form for creating a new group
    public function create()
    {
        // Optionally, pass all users so that the creator can invite others
        $users = User::all();
        return view('groups.create', compact('users'));
    }

    // Store a new group and attach members (including the creator)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_ids' => 'sometimes|array',           // Optional invited users
            'user_ids.*' => 'exists:users,id',
        ]);

        // Create the group
        $group = Group::create(['name' => $request->name]);

        // Attach the current user
        $group->users()->attach(Auth::id());

        // Attach invited users if provided
        if ($request->has('user_ids')) {
            $group->users()->attach($request->user_ids);
        }

        if ($request->expectsJson()) {
            return response()->json($group, 201);
        }
    

        return redirect()->route('groups.show', $group->id)
                         ->with('success', 'Group created successfully!');
    }

    // Show the group chat with its messages
    public function show($id)
    {
        $group = Group::with('messages.user')->findOrFail($id);
        // Check if the authenticated user is a member of the group
        if (!$group->users->contains(Auth::id())) {
            abort(403, 'Unauthorized access');
        }

        return view('groups.show', compact('group'));
    }

    public function destroy($id)
    {
        $group = Group::findOrFail($id);
        // For this demo, allow deletion if the user is a member.
        // In a real app, consider restricting deletion to the group's owner.
        if (!$group->users->contains(Auth::id())) {
            abort(403, 'Unauthorized access');
        }

        $group->delete();

        return redirect()->route('groups.index')->with('success', 'Group deleted successfully.');
    }
    
    public function join(Group $group)
{
    $group->users()->syncWithoutDetaching(Auth::id());

    if (request()->expectsJson()) {
        return response()->json(['message' => 'Joined group'], 200);
    }

    return redirect()->route('groups.show', $group)->with('success', 'You joined the group!');
}
}
