<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-400 to-emerald-500 px-4">

        <!-- Reset Password Card -->
        <div class="w-full max-w-md bg-white/80 backdrop-blur-md p-10 rounded-3xl shadow-xl">

            <!-- Logo -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-16 rounded-full object-cover shadow" />
            </div>

            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-3xl font-extrabold text-gray-800">
                    Create New Password
                </h1>
                <p class="mt-2 text-sm text-gray-600">
                    Enter your new password below to reset your account.
                </p>
            </div>

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

            <!-- Reset Password Form -->
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf

                <!-- Email (Hidden) -->
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- New Password -->
                <div>
                    <x-input-label for="password" :value="__('New Password')" class="text-sm text-gray-700" />
                    <x-text-input id="password" type="password" name="password" required autofocus
                        autocomplete="new-password"
                        placeholder="••••••••••"
                        class="mt-1 block w-full rounded-xl border-gray-200 bg-gray-100 focus:border-green-500 focus:ring-green-500 px-4 py-3" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm text-gray-700" />
                    <x-text-input id="password_confirmation" type="password" name="password_confirmation" required
                        autocomplete="new-password"
                        placeholder="••••••••••"
                        class="mt-1 block w-full rounded-xl border-gray-200 bg-gray-100 focus:border-green-500 focus:ring-green-500 px-4 py-3" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs" />
                </div>

                <!-- Submit Button -->
                <x-primary-button
                    class="w-full justify-center rounded-full bg-green-600 hover:bg-green-700 focus:ring-green-500 py-3 text-base font-semibold tracking-wide">
                    {{ __('Reset Password') }}
                </x-primary-button>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    <a href="{{ route('login') }}" class="text-green-600 hover:underline font-semibold">
                        {{ __('Back to Login') }}
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
