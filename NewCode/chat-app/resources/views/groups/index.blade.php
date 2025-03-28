@extends('layouts.app')

@section('content')
<div class="flex-grow p-6 ml-64">
<div class="flex flex-col min-h-screen bg-gray-100 text-gray-800">
    <header class="bg-blue-900 text-white px-6 py-4 shadow">
        <h1 class="text-2xl font-bold">My Groups</h1>
    </header>

    <main class="flex-grow p-6 md:p-10">
        <div class="max-w-3xl mx-auto bg-white rounded shadow-md p-6">
            @if(session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 border-l-4 border-green-500 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-blue-900">My Groups</h2>
                <a href="{{ route('groups.create') }}"
                   class="inline-block bg-blue-900 text-white px-4 py-2 rounded hover:bg-blue-800 transition">
                   Create Group
                </a>
            </div>

            @if($groups->isEmpty())
                <p class="text-gray-600">You are not a member of any groups yet.</p>
            @else
            <ul class="divide-y divide-gray-200">
                @foreach($groups as $group)
                    <li class="py-3 flex items-center justify-between">
                        <!-- Group Name / Link -->
                        <a href="{{ route('groups.show', $group->id) }}"
                        class="text-blue-900 font-medium hover:underline">
                        {{ $group->name }}
                        </a>

                        <!-- Only show the Delete form if the user is an admin -->
                        @if(Auth::user()->is_admin)
                            <form action="{{ route('groups.destroy', $group->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this group?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-500 transition">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
            @endif
        </div>
    </main>
</div>
<div>
@endsection
