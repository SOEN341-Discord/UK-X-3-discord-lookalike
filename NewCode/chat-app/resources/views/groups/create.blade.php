@extends('layouts.app')

@section('content')
<div class="flex-grow p-6 ml-64">
<div class="container">
    <h1>Create a New Group</h1>
    <form action="{{ route('groups.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Group Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <!-- Optional: List of users to invite -->
        <div class="mb-3">
            <label for="user_ids" class="form-label">Invite Users</label>
            <select name="user_ids[]" id="user_ids" class="form-select" multiple>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Create Group</button>
    </form>
</div>
</div>
@endsection
