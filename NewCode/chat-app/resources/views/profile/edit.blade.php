@extends('layouts.app')
 
 @section('content')
     <div class="flex-grow p-6 ml-64">
         <div class="container">
                 <h1 class="text-2xl pb-5">{{ __('Edit Profile') }}</h1>
 
                 <form method="POST" action="{{ route('profile.update') }}">
                     @csrf
                     @method('PATCH')
 
                     <!-- Name -->
                     <div>
                         <x-input-label for="name" :value="__('Name')" />
                         <x-text-input id="name" class="block mt-1 w-1/3" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                         <x-input-error :messages="$errors->get('name')" class="mt-2" />
                     </div>
 
                     <!-- Email Address -->
                     <div class="mt-4">
                         <x-input-label for="email" :value="__('Email')" />
                         <x-text-input id="email" class="block mt-1 w-1/2" type="email" name="email" :value="old('email', $user->email)" required />
                         <x-input-error :messages="$errors->get('email')" class="mt-2" />
                     </div>
 
                     <!-- User Role -->
                     <div class="mt-4">
                        <x-input-label for="role" :value="__('Role Status')" />

                        <p class="mt-2 text-m {{ $user->is_admin ? 'text-green-600' : 'text-gray-600' }}">
                            {{ $user->is_admin ? __('Admin') : __('Member') }}
                        </p>

                        @if ($user->is_member)
                            <a class="underline text-sm text-red-300 hover:text-gray-500 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('solve.riddle') }}">
                                {{ __('Want to become an admin? Come solve this problem') }}
                            </a>
                        @endif

                    </div>

 
                     <!-- Submit Button -->
                     <div class="mt-4">
                         <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
                     </div>
                 </form>
             </div>
     </div>
 @endsection

<!-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout> -->
