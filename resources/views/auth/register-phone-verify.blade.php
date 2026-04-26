<x-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-6 px-4">
        <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8">

            <div class="text-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="GMS Logo"
                    class="w-16 h-16 rounded-full object-cover mx-auto mb-4">
                <h1 class="text-3xl font-bold text-gray-800">Verify Your Phone</h1>
                <p class="text-gray-500 text-sm mt-1">Enter the 6-digit code sent to
                    <strong>{{ session('register_phone') }}</strong></p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 mb-5 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('debug_otp'))
                <div
                    class="bg-yellow-100 border border-yellow-300 text-yellow-800 rounded-xl px-4 py-3 mb-5 text-sm text-center font-bold">
                    🔑 TEST OTP: {{ session('debug_otp') }}
                </div>
            @endif

            <form method="POST" action="{{ route('otp.register.verify') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">OTP Code</label>
                    <input type="text" name="otp" placeholder="Enter 6-digit code" maxlength="6" required autofocus
                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition text-sm tracking-widest text-center text-lg">
                </div>

                <button type="submit"
                    class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-3 rounded-xl font-semibold text-sm transition shadow-md">
                    Verify & Create Account
                </button>
            </form>

            <div class="text-center mt-6">
                <a href="{{ route('otp.register') }}" class="text-sm text-green-600 hover:underline">← Resend Code</a>
            </div>

        </div>
    </div>
</x-layout>