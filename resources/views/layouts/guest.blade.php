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
    <body class="font-sans antialiased bg-gray-100 min-h-screen flex flex-col items-center justify-center px-4" x-data="{ snackbar: { show: false, message: '', type: 'success' } }"
          @snackbar.window="snackbar = { show: true, message: $event.detail.message, type: $event.detail.type || 'success' }; setTimeout(() => snackbar.show = false, 4000)"
          x-init="@if(session('logout_snackbar')) snackbar = { show: true, message: '{{ session('logout_snackbar') }}', type: 'info' }; setTimeout(() => snackbar.show = false, 4000) @endif">
        <div class="w-full max-w-sm">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 rounded-xl mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">EID<span class="text-blue-600">Monitor</span></h1>
                <p class="text-sm text-gray-500 mt-1">Smart Manufacturing Dashboard</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                {{ $slot }}
            </div>

            <p class="text-center text-xs text-gray-400 mt-6">ElectroIntiDinamika &copy; {{ date('Y') }}</p>
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
    </body>
</html>
