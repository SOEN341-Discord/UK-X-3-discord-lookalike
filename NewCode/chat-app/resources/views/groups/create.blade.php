@extends('layouts.app')

@section('content')
<div class="flex-grow p-6 ml-64">
<div class="flex flex-col min-h-screen bg-gray-100 text-gray-800">
    <!-- Optional Top Header -->
    <header class="bg-blue-900 text-white px-6 py-4 shadow">
        <h1 class="text-2xl font-bold">Create a New Group</h1>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow p-6 md:p-10">
        <div class="max-w-3xl mx-auto bg-white rounded shadow-md p-6">
            <form action="{{ route('groups.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Group Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Group Name
                    </label>
                    <input 
                        type="text"
                        name="name"
                        id="name"
                        required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                    >
                </div>

                <!-- Invite Users (Checkboxes) -->
                <div>
                    <label for="user_ids" class="block text-sm font-medium text-gray-700 mb-1">
                        Invite Users
                    </label>
                    <div class="space-y-2">
                        @foreach($users as $user)
                            <div class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="user_ids[]"
                                    value="{{ $user->id }}"
                                    id="user_{{ $user->id }}"
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                >
                                <label for="user_{{ $user->id }}" class="ml-2 text-gray-700">
                                    {{ $user->name }} ({{ $user->email }})
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-500 transition"
                    >
                        Create Group
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
</div>
@endsection
