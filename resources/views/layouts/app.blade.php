<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'ElectroIntiDinamika') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ snackbar: { show: false, message: '', type: 'success' } }"
          @snackbar.window="snackbar = { show: true, message: $event.detail.message, type: $event.detail.type || 'success' }; setTimeout(() => snackbar.show = false, 4000)"
          x-init="@if(session('snackbar')) snackbar = { show: true, message: '{{ session('snackbar.message') }}', type: '{{ session('snackbar.type', 'success') }}' }; setTimeout(() => snackbar.show = false, 4000) @endif">
        <div class="min-h-screen">
            <livewire:layout.navigation />

            @if (isset($header))
                <header class="bg-white border-b shadow-sm">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <main>
                {{ $slot }}
            </main>
        </div>

        <div x-show="snackbar.show" x-transition:enter="transform ease-out duration-300 transition" x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed bottom-6 right-6 z-50 px-5 py-3 rounded-lg shadow-lg text-sm font-medium text-white"
             :class="snackbar.type === 'success' ? 'bg-green-600' : snackbar.type === 'error' ? 'bg-red-600' : 'bg-blue-600'">
            <div class="flex items-center gap-2">
                <svg x-show="snackbar.type === 'success'" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <svg x-show="snackbar.type === 'error'" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <svg x-show="snackbar.type === 'info'" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span x-text="snackbar.message"></span>
            </div>
        </div>

        @livewireScripts
    </body>
</html>
