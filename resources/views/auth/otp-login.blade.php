<x-layout>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-500 via-emerald-500 to-teal-600 p-6">
    <div class="w-full max-w-md backdrop-blur-xl bg-white/90 border border-white/40 rounded-3xl shadow-2xl p-8">

        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="GMS Logo" class="w-16 h-16 rounded-full object-cover mx-auto mb-4">
            <h1 class="text-3xl font-bold text-gray-800">Login with OTP</h1>
            <p class="text-gray-500 text-sm mt-1">We'll send a code to your phone</p>
        </div>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 mb-5 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('otp.send') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="text" name="phone" placeholder="e.g. 09171234567" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition text-sm">
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-3 rounded-xl font-semibold text-sm transition shadow-md">
                Send OTP
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('login') }}" class="text-sm text-green-600 hover:underline">← Back to Email Login</a>
        </div>

    </div>
</div>
</x-layout>