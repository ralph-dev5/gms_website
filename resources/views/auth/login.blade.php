<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-50 via-white to-emerald-100 px-4">

        <!-- Login Card -->
        <div class="w-full max-w-md bg-white/60 backdrop-blur-md p-8 rounded-2xl shadow-md">

            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-2xl font-extrabold text-green-600">
                    ♻ Garbage Monitoring System
                </h1>
                <p class="mt-1 text-sm text-gray-600">
                    Login to your account
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-sm text-green-600 text-center" :status="session('status')" />

            <!-- Traditional Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- User Name -->
                <div>
                    <x-input-label for="email" :value="__('User Name')" class="text-sm text-gray-700" />
                    <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                        autocomplete="username"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-sm text-gray-700" />
                    <x-text-input id="password" type="password" name="password" required
                        autocomplete="current-password"
                        class="mt-1 block w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" name="remember"
                            class="rounded border-gray-300 text-green-600 focus:ring-green-500" />
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-green-600 hover:underline">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <!-- Login Button -->
                <x-primary-button
                    class="w-full justify-center rounded-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:ring-green-500">
                    {{ __('Log in') }}
                </x-primary-button>
            </form>

            <!-- OR separator -->
            <div class="flex items-center my-4">
                <hr class="flex-grow border-gray-300" />
                <span class="mx-2 text-gray-500 text-sm">OR</span>
                <hr class="flex-grow border-gray-300" />
            </div>

            <!-- Google Login -->
            <div class="social-auth-links text-center">
                <a href="{{ route('google.redirect') }}"
                   class="inline-flex items-center justify-center w-full py-2 px-4 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-green-500 transition">
                    <i class="fab fa-google mr-2"></i> {{ __('Sign in with Google') }}
                </a>
            </div>

            <!-- Register Link -->
            <p class="mt-6 text-center text-sm text-gray-600">
                No account yet?
                <a href="{{ route('register') }}" class="font-semibold text-green-600 hover:underline">
                    Register
                </a>
            </p>

        </div>
    </div>
</x-guest-layout>