<x-layout>
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-6 sm:py-12 md:py-16 lg:py-20 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl bg-white rounded-xl sm:rounded-2xl lg:rounded-3xl shadow-md sm:shadow-lg md:shadow-xl p-6 sm:p-8 md:p-10 lg:p-12">

        <!-- Header -->
        <div class="text-center mb-6 sm:mb-8 md:mb-10 lg:mb-12">
            <img src="{{ asset('images/logo.png') }}" alt="GMS Logo" class="w-12 h-12 sm:w-16 sm:h-16 md:w-20 md:h-20 rounded-full object-cover mx-auto mb-3 sm:mb-4 md:mb-6">
            <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800">Create an account</h1>
            <p class="text-gray-500 text-xs sm:text-sm md:text-base lg:text-lg mt-1 sm:mt-2 md:mt-3">Join your community waste management platform</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 rounded-lg px-4 sm:px-5 md:px-6 py-3 md:py-4 mb-5 md:mb-6 text-xs sm:text-sm md:text-base">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Register Form -->
        <form method="POST" action="/register" class="space-y-3 sm:space-y-4 md:space-y-5 lg:space-y-6">
            @csrf

            <div>
                <label class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-2 md:mb-3">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    placeholder="Juan Dela Cruz" required
                    class="w-full px-3 sm:px-4 md:px-5 py-2 sm:py-2.5 md:py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition text-xs sm:text-sm md:text-base">
            </div>

            <div>
                <label class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-2 md:mb-3">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="you@example.com" required
                    class="w-full px-3 sm:px-4 md:px-5 py-2 sm:py-2.5 md:py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition text-xs sm:text-sm md:text-base">
            </div>

            <div>
                <label class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-2 md:mb-3">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password"
                        placeholder="Min. 8 characters" required
                        class="w-full px-3 sm:px-4 md:px-5 py-2 sm:py-2.5 md:py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition text-xs sm:text-sm md:text-base pr-9 sm:pr-10 md:pr-12">
                    <button type="button" onclick="togglePassword('password')"
                        class="absolute right-2 sm:right-3 md:right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-xs sm:text-sm md:text-base font-medium text-gray-700 mb-1 sm:mb-2 md:mb-3">Confirm Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        placeholder="Repeat your password" required
                        class="w-full px-3 sm:px-4 md:px-5 py-2 sm:py-2.5 md:py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition text-xs sm:text-sm md:text-base pr-9 sm:pr-10 md:pr-12">
                    <button type="button" onclick="togglePassword('password_confirmation')"
                        class="absolute right-2 sm:right-3 md:right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 sm:py-2.5 md:py-3 lg:py-3.5 rounded-lg font-semibold text-xs sm:text-sm md:text-base transition shadow-sm mt-2 sm:mt-4 md:mt-6 lg:mt-8">
                Create Account
            </button>
        </form>

        <!-- Divider -->
        <div class="flex items-center gap-3 my-4 sm:my-5 md:my-6 lg:my-8">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="text-xs md:text-sm text-gray-400">or sign up with</span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        <!-- Google -->
        <a href="{{ route('social.redirect', 'google') }}"
            class="flex items-center justify-center gap-2 sm:gap-3 w-full border border-gray-300 rounded-lg py-2 sm:py-2.5 md:py-3 hover:bg-gray-50 transition text-xs sm:text-sm md:text-base font-medium text-gray-700">
            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-4 h-4 md:w-5 md:h-5">
            Sign up with Google
        </a>

        <p class="text-center text-xs sm:text-sm md:text-base text-gray-500 mt-4 sm:mt-6 md:mt-8 lg:mt-10">
            Already have an account?
            <a href="{{ route('login') }}" class="text-green-600 font-semibold hover:underline">Sign in</a>
        </p>

    </div>
</div>

<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
</x-layout>
