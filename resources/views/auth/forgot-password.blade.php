<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-400 to-emerald-500 px-4">

        <!-- Forgot Password Card -->
        <div class="w-full max-w-md bg-white/80 backdrop-blur-md p-10 rounded-3xl shadow-xl">

            <!-- Logo -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-16 rounded-full object-cover shadow" />
            </div>

            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-3xl font-extrabold text-gray-800">
                    Reset Your Password
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    Enter your email address and we'll send you a link to reset your password.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-sm text-green-600 text-center" :status="session('status')" />

            <!-- Error Message -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Forgot Password Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="text-sm text-gray-700" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                        autocomplete="email"
                        placeholder="your@email.com"
                        class="mt-1 block w-full rounded-xl border-gray-200 bg-gray-100 focus:border-green-500 focus:ring-green-500 px-4 py-3" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                </div>

                <!-- Submit Button -->
                <x-primary-button
                    class="w-full justify-center rounded-full bg-green-600 hover:bg-green-700 focus:ring-green-500 py-3 text-base font-semibold tracking-wide">
                    {{ __('Send Password Reset Link') }}
                </x-primary-button>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Remember your password?
                    <a href="{{ route('login') }}" class="text-green-600 hover:underline font-semibold">
                        {{ __('Back to Login') }}
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
