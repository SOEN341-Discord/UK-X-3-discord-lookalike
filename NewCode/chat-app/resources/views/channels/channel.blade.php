@extends('layouts.app')

@section('title')
    <div class="flex-grow ml-64">
        <div class="bg-gray-800 text-white shadow w-full h-15 ">
            <h1 class="text-xl font-semibold p-4">Welcome to {{ $channel->name }} Channel !</h1>

            @if(Auth::user()->is_admin)
            <button 
            @click="window.location.href = '{{ route('channels.manage', $channel) }}'"
            class="block text-m p-2 hover:bg-green-500 focus:outline-none"
            >
                Manage Channel
            </button>
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div class="flex-grow ml-64">
        <!-- this is for the group chat -->
    </div>

@endsection