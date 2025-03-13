<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index()
    {
        $channels = Channel::where('type', 'group')->get();
        return view('channels.index', compact('channels'));
    }

    public function create()
    {
        return view('channels.create');
    }

    public function showCreateForm()
    {
        return view('create-channel');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:channels',
        ]);

        Channel::create([
            'name' => $request->name,
            'description' => $request->description,
            'type' => 'group',
        ]);

        return redirect()->route('server')->with('success', 'Channel created successfully!');
    }

    public function destroy(Channel $channel)
    {
        $channel->delete();
        return redirect()->route('channels.index')->with('success', 'Channel deleted successfully!');
    }
}