@extends('layouts.app')

@section('content')
<div class="flex-grow p-6 ml-64">
<div class="container">
    <h1>My Groups</h1>
    
    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Create Group Button -->
    <a href="{{ route('groups.create') }}" class="btn btn-primary mb-3">Create Group</a>
    
    <!-- List of Groups -->
    @if($groups->isEmpty())
        <p>You are not a member of any groups yet.</p>
    @else
        <ul class="list-group">
            @foreach($groups as $group)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ route('groups.show', $group->id) }}">{{ $group->name }}</a>
                    <!-- Delete form for each group -->
                    <form action="{{ route('groups.destroy', $group->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this group?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</div>
</div>
@endsection
