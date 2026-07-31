<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();

        session()->flash('logout_snackbar', 'You have been logged out.');

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5 shrink-0">
                    <div class="w-8 h-8 rounded bg-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-gray-900">EID<span class="text-blue-600">Monitor</span></span>
                </a>

                <div class="hidden sm:flex sm:ml-10 space-x-1">
                    <a href="{{ route('dashboard') }}" wire:navigate
                        class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md
                        {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        Dashboard
                    </a>
                    @can('admin')
                    <a href="{{ route('machines') }}" wire:navigate
                        class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md
                        {{ request()->routeIs('machines') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        Machines
                    </a>
                    @endcan
                    <a href="{{ route('reports') }}" wire:navigate
                        class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md
                        {{ request()->routeIs('reports') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        Reports
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-3">
                <span class="px-2 py-0.5 text-xs font-semibold rounded-full border
                    {{ auth()->user()->role === 'admin' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-gray-100 text-gray-600 border-gray-200' }}">
                    {{ strtoupper(auth()->user()->role) }}
                </span>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:text-gray-900 rounded-md hover:bg-gray-50">
                            <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-xs font-bold text-white">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b">
                            <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ auth()->user()->email }}</div>
                        </div>
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            Profile
                        </x-dropdown-link>
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>Log Out</x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100">
                    <svg :class="{'hidden': open, 'block': !open}" class="h-6 w-6 block" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg :class="{'block': open, 'hidden': !open}" class="h-6 w-6 hidden" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-200">
        <div class="px-4 py-3 space-y-1">
            <a href="{{ route('dashboard') }}" wire:navigate class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Dashboard</a>
            @can('admin')
            <a href="{{ route('machines') }}" wire:navigate class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('machines') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Machines</a>
            @endcan
            <a href="{{ route('reports') }}" wire:navigate class="block px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('reports') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' }}">Reports</a>
        </div>
        <div class="px-4 py-3 border-t border-gray-200">
            <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
            <div class="text-xs text-gray-500 mb-2">{{ auth()->user()->email }}</div>
            <a href="{{ route('profile') }}" wire:navigate class="block px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-50">Profile</a>
            <button wire:click="logout" class="w-full text-start block px-3 py-2 rounded-md text-sm font-medium text-red-600 hover:bg-gray-50">Log Out</button>
        </div>
    </div>
</nav>
