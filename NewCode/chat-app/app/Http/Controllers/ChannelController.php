<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function index()
    {
        $channels = Channel::all();
        //$channel = Channel::where('type', 'group')->get();
        return view('channels.index', compact('channels'));
        //return view('channels.index', compact('channel', 'channels'));

    }

    public function create()
    {
        return view('channels.create');
    }

    public function showCreateForm()
    {
        $channels = Channel::all();
        return view('create-channel', compact('channels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:channels',
        ]);

        $channel = Channel::create([
            'name' => $request->name,
            'description' => $request->description,
            //'type' => 'group',
        ]);

        if ($request->expectsJson()) {
            return response()->json($channel, 201);
        }

        return redirect()->route('server')->with('success', 'Channel created successfully!');
    }

    public function destroy(Channel $channel)
    {
        $channel->delete();
        return redirect()->route('channels.index')->with('success', 'Channel deleted successfully!');
    }

    public function showChannel($channelId)
    {
        $channels = Channel::all();
        $channel = Channel::findOrFail($channelId);
        return view('channel', compact('channel', 'channels'));
    }
}