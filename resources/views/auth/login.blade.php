<x-layout>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-500 via-emerald-500 to-teal-600 p-6">

    <div class="w-full max-w-md backdrop-blur-xl bg-white/90 border border-white/40 rounded-3xl shadow-2xl p-8">

        <!-- Header -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="GMS Logo" class="w-16 h-16 rounded-full object-cover mx-auto mb-4">
            <h1 class="text-3xl font-bold text-gray-800">Welcome Back</h1>
            <p class="text-gray-500 text-sm mt-1">Login to continue to your account</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 mb-5 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="you@example.com" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition text-sm">
            </div>

            <!-- Password -->
            <div>
                <div class="flex justify-between items-center mb-1">
                    <label class="text-sm font-medium text-gray-700">Password</label>
                    <a href="#" class="text-xs text-green-600 hover:underline">Forgot password?</a>
                </div>

                <div class="relative">
                    <input type="password" name="password" id="password"
                        placeholder="••••••••" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition text-sm pr-12">

                    <button type="button" onclick="togglePassword()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700">
                        <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5
                                c4.478 0 8.268 2.943 9.542 7
                                -1.274 4.057-5.064 7-9.542 7
                                -4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Login Button -->
            <button type="submit"
                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-3 rounded-xl font-semibold text-sm transition shadow-md hover:shadow-lg">
                Sign In
            </button>

        </form>

        <!-- Divider -->
        <div class="flex items-center gap-3 my-6">
            <div class="flex-1 h-px bg-gray-300"></div>
            <span class="text-xs text-gray-500">or continue with</span>
            <div class="flex-1 h-px bg-gray-300"></div>
        </div>

        <!-- Google Login -->
        <a href="{{ route('social.redirect', 'google') }}"
            class="flex items-center justify-center gap-3 w-full border border-gray-300 rounded-xl py-3 hover:bg-gray-50 transition text-sm font-medium text-gray-700">

            <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5">
            Sign in with Google
        </a>

        

    </div>

</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
</x-layout>