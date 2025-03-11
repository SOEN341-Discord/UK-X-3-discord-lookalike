<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Channel;


class ServerController extends Controller
{
    public function index()
    {
        $channels = Channel::all(); // Fetch all channels
        return view('server', compact('channels'));
    }

    public function showChannel($channelId)
    {
        $channel = Channel::findOrFail($channelId);
        return view('channel', compact('channel'));
    }
}
