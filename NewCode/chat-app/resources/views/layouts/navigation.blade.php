<nav 
    x-data="{ showChannels: {{ request()->is('server*') ? 'true' : 'false' }} }"
    class="bg-white border-r border-gray-100 fixed inset-y-0 left-0 w-64 h-screen overflow-y-auto"
>
    <div class="flex flex-col bg-gray-800 text-white h-full">
        <!-- Logo -->
        <div class="flex p-4">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="h-9 w-auto fill-current text-white" />
            </a>
        </div>

        <!-- Navigation Links -->
        <div 
        class="flex flex-col justify-between">
            <div class="space-y-2">
                <!-- Server Link with Dropdown -->
                    <button 
                        @click="window.location.href = '{{ route('server') }}'"
                        class="block w-full text-left p-4 hover:bg-gray-700 focus:outline-none"
                    >
                        {{ __('Server') }}
                    </button>

                    <!-- Channels List (Visible only on /server) -->
                    @if(isset($channels) && count($channels) > 0)
                        <div x-show="showChannels" class="block pl-5 space-y-1">
                            @foreach($channels as $channel)
                            <div class="block">
                                <button 
                                @click="window.location.href = '{{ route('server.channel', $channel->id) }}'"
                                class="block w-full text-left p-1 hover:bg-gray-700 focus:outline-none"
                                >
                                    {{ $channel->name }}
                                </button>
                            </div>  
                            @endforeach
                        </div>
                        @if( Auth::user()->is_admin )
                        <!-- Button to navigate to create channel page -->
                        <button
                        @click="window.location.href = '{{ route('showCreateForm') }}'"
                        class="block w-full text-left p-4 hover:bg-gray-700 focus:outline-none"
                        >
                            Create Channel
                        </button>
                        @endif
                    @endif

                <!-- Private Messages Link -->
                <button 
                    @click="showChannels = false; window.location.href = '{{ route('chatify') }}'"
                    class="block w-full text-left p-4 hover:bg-gray-700 focus:outline-none {{ request()->routeIs('chatify') ? 'bg-gray-700' : '' }}"
                >
                    {{ __('Private Messages') }}
                </button>

                <button 
                    @click="window.location.href = '{{route('groups.index')}}'"
                    class="block w-full text-left p-4 hover:bg-gray-700 focus:outline-none {{ request()->routeIs('chatify') ? 'bg-gray-700' : '' }}"
                >
                    {{ __('Group Channels') }}
                </button>

            </div>

            <!-- Profile Dropdown -->
            <div class="p-4">
                <x-dropdown align="bottom" width="48">
                    <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-m leading-4 font-medium rounded-md text-gray-300 bg-gray-800 hover:text-white focus:outline-none transition ease-in-out duration-150">                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
