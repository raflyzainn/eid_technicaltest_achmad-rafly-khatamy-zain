<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public bool $showPassword = false;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        session()->flash('snackbar', [
            'message' => 'Welcome back, ' . auth()->user()->name . '!',
            'type' => 'success',
        ]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <h2 class="text-lg font-semibold text-gray-900 mb-5">Sign in to your account</h2>

    <form wire:submit="login" class="space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username"
                placeholder="admin@eid.com"
                class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 placeholder:text-gray-300">
            @error('form.email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <div class="relative">
                <input wire:model="form.password" id="password"
                    type="{{ $showPassword ? 'text' : 'password' }}"
                    name="password" required autocomplete="current-password"
                    placeholder="••••••••"
                    class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 pr-10 placeholder:text-gray-300">
                <button type="button" wire:click="$toggle('showPassword')"
                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 p-0.5">
                    @if($showPassword)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    @endif
                </button>
            </div>
            @error('form.password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label for="remember" class="flex items-center gap-2 cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <span class="text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <button type="submit"
            class="w-full py-2.5 px-4 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Sign in
        </button>
    </form>
</div>
