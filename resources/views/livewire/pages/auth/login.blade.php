<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): mixed
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        if (auth()->user()->hasRole('admin')) {
            return redirect()->to(route('admin.dashboard') . '?t=' . time());
        }

        if (auth()->user()->hasRole('user')) {
            return redirect()->to(route('user.keluarga') . '?t=' . time());
        }

        return redirect()->intended(route('login'));
    }
}; ?>

<div class="container p-2 md:m-24 bg-[#ffffff] flex flex-col md:flex-row w-full shadow-md">
    <!-- Left Column - Image -->
    <div class="w-full md:w-3/5 bg-gray-100 hidden md:flex">
        <a href="{{ url('/') }}" class="w-full">
            <img src="https://images.unsplash.com/photo-1733039898491-b4f469c6cd1a?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                alt="Login Image" class="w-full h-full object-cover">
        </a>
    </div>

    <!-- Right Column - Form -->
    <div class="w-full md:w-2/5 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <div class="md:hidden mb-8 text-center">
                <a href="{{ url('/') }}" class="flex flex-col items-center text-decoration-none">
                    <img src="https://images.unsplash.com/photo-1733039898491-b4f469c6cd1a?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                        alt="Mobile Logo" class="rounded-lg shadow-md w-full max-w-xs h-auto">
                </a>
            </div>

            <h1 class="text-4xl font-bold mb-4 text-center md:text-left" style="font-family:Viga; background: linear-gradient(to right, #1e3a8a, #3b82f6, #93c5fd); -webkit-background-clip: text; background-clip: text; color: transparent;">SITERAH</h1>
            <h3 class="text-lg font-bold text-gray-800 mb-3 text-center md:text-left">Silahkan Masuk Ke Akun Anda</h3>

            <form wire:submit="login" class="space-y-6">
                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email"
                        name="email" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password"
                        name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input wire:model="form.remember" id="remember" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            name="remember">
                        <label for="remember" class="ms-2 text-sm text-gray-600">
                            {{ __('Ingat saya') }}
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-indigo-600 hover:text-indigo-500" href="{{ route('password.request') }}"
                            wire:navigate>
                            {{ __('Lupa Kata Sandi?') }}
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <div>
                    <x-primary-button class="w-full justify-center">
                        {{ __('Masuk') }}
                    </x-primary-button>
                </div>
            </form>

            <!-- Additional Links -->
            <div class="mt-6 text-center text-sm text-gray-500 mb-5">
                Belum Memiliki Akun?
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500"
                    wire:navigate>
                    Daftar Disini
                </a>
            </div>

            <hr>

            <div class="footer text-center">
                <small>Dikembangkan oleh:</small> <br>
                <a class="pengembang" href="/developer" target="_blank">
                    <b>
                        <small>Muhammad Rizki Dalfi</small>
                        <span style="color: black; font-weight: normal"> & </span>
                        <small>Dwi Nur Indah Sari</small>
                    </b>
                </a>
            </div>
        </div>
    </div>
</div>
