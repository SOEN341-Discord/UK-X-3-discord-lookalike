@extends('layouts.app')

@section('content')
<div class="flex-grow p-6 ml-64">
    <h2 class="text-2xl font-bold mb-4">Create New Channel</h2>

    <form method="POST" action="{{ route('server.create-channel') }}">
        @csrf
        
        <div class="mb-4">
            <label for="channel-name" class="block text-sm font-medium text-gray-700">Channel Name</label>
            <input type="text" id="channel-name" name="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
        </div>

        
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Create Channel</button>
        </div>
    </form>
</div>
@endsection
