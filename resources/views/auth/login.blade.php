<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-400 to-emerald-500 px-4">

        <!-- Login Card -->
        <div class="w-full max-w-md bg-white/80 backdrop-blur-md p-10 rounded-3xl shadow-xl">

            <!-- Logo -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-16 rounded-full object-cover shadow" />
            </div>

            <!-- Header -->
            <div class="text-center mb-6">
                <h1 class="text-3xl font-extrabold text-gray-800">
                    Welcome Back
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    Login to continue to your account
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
                    {{-- Changed type="email" to type="text" to disable browser email validation --}}
                    <x-text-input id="email" type="text" name="email" :value="old('email')" required autofocus
                        autocomplete="username"
                        class="mt-1 block w-full rounded-xl border-gray-200 bg-gray-100 focus:border-green-500 focus:ring-green-500 px-4 py-3" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                </div>

                <!-- Password with show/hide toggle -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-sm text-gray-700" />
                    <div class="relative mt-1">
                        <x-text-input id="password" type="password" name="password" required
                            autocomplete="current-password"
                            placeholder="••••••••••"
                            class="block w-full rounded-xl border-gray-200 bg-gray-100 focus:border-green-500 focus:ring-green-500 px-4 py-3 pr-12" />

                        <!-- Toggle Button -->
                        <button type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none"
                            aria-label="Toggle password visibility">

                            <!-- Eye open (default — password hidden) -->
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>

                            <!-- Eye off (password visible) -->
                            <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
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
                    class="w-full justify-center rounded-full bg-green-600 hover:bg-green-700 focus:ring-green-500 py-3 text-base font-semibold tracking-wide">
                    {{ __('Sign In') }}
                </x-primary-button>
            </form>

            <!-- OR separator -->
            <div class="flex items-center my-5">
                <hr class="flex-grow border-gray-300" />
                <span class="mx-3 text-gray-400 text-sm">or continue with</span>
                <hr class="flex-grow border-gray-300" />
            </div>

            <!-- Google Login -->
            <div class="social-auth-links text-center">
                <a href="{{ route('google.redirect') }}"
                   class="inline-flex items-center justify-center w-full py-3 px-4 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-green-500 transition">
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    {{ __('Sign in with Google') }}
                </a>
            </div>

            <!-- Register Link -->
            <p class="mt-6 text-center text-sm text-gray-500">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-semibold text-green-600 hover:underline">
                    Sign up
                </a>
            </p>

        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            eyeIcon.classList.toggle('hidden', isHidden);
            eyeOffIcon.classList.toggle('hidden', !isHidden);
        }
    </script>
</x-guest-layout>