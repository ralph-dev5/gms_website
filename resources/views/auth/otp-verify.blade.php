<x-layout>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-green-500 via-emerald-500 to-teal-600 p-6">
    <div class="w-full max-w-md backdrop-blur-xl bg-white/90 border border-white/40 rounded-3xl shadow-2xl p-8">

        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="GMS Logo" class="w-16 h-16 rounded-full object-cover mx-auto mb-4">
            <h1 class="text-3xl font-bold text-gray-800">Enter OTP</h1>
            <p class="text-gray-500 text-sm mt-1">Check your phone for the 6-digit code</p>
        </div>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 mb-5 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('otp.verify') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">OTP Code</label>
                <input type="text" name="otp" placeholder="Enter 6-digit code" maxlength="6" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition text-sm tracking-widest text-center text-lg">
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-3 rounded-xl font-semibold text-sm transition shadow-md">
                Verify & Login
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('otp.login') }}" class="text-sm text-green-600 hover:underline">← Resend OTP</a>
        </div>

    </div>
</div>
</x-layout>