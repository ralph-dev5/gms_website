<x-layout>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-500 via-emerald-500 to-teal-600 p-6">
    <div class="w-full max-w-md backdrop-blur-xl bg-white/90 border border-white/40 rounded-3xl shadow-2xl p-8">

        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="GMS Logo" class="w-16 h-16 rounded-full object-cover mx-auto mb-4">
            <h1 class="text-3xl font-bold text-gray-800">Reset Password</h1>
            <p class="text-gray-500 text-sm mt-1">Enter your new password below</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 mb-5 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="you@example.com" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" name="password"
                    placeholder="Min. 8 characters" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation"
                    placeholder="Repeat your password" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition text-sm">
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-3 rounded-xl font-semibold text-sm transition shadow-md">
                Reset Password
            </button>
        </form>

    </div>
</div>
</x-layout>