@extends('layouts.app')
 
@section('content')
     <div class="flex-grow p-6 ml-64">
         <div class="container">
         <h1>{{ __('Solve the Riddle') }}</h1>
                <form method="POST" action="{{ route('solve.riddle') }}">
                    @csrf

                    <!-- Riddle Question -->
                    <p>{{ __('What is 2 + 2?') }}</p>

                    <!-- Riddle Answer -->
                    <div>
                        <x-input-label for="riddle_answer" :value="__('Answer')" />
                        <x-text-input id="riddle_answer" class="block mt-1 w-1/2" type="text" name="riddle_answer" required autofocus />
                        <x-input-error :messages="$errors->get('riddle_answer')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-4">
                        <x-primary-button>{{ __('Submit Answer') }}</x-primary-button>
                    </div>
                </form>
     </div>
@endsection



