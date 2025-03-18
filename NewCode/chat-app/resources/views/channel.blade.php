@extends('layouts.app')

@section('content')
    <div class="flex-grow ml-64">
        <!-- Simple Content Test -->
        <div class="bg-gray-800 text-white shadow w-full h-15 text-xl">
            <h1 class="font-semibold p-4">Welcome to {{ $channel->name }} Channel !</h1>
        </div>
    </div>
@endsection