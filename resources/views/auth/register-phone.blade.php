<x-layout>
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-6 px-4">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8">

        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="GMS Logo" class="w-16 h-16 rounded-full object-cover mx-auto mb-4">
            <h1 class="text-3xl font-bold text-gray-800">Register with Phone</h1>
            <p class="text-gray-500 text-sm mt-1">We'll send a verification code to your phone</p>
        </div>

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 mb-5 text-sm">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('otp.register.send') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    placeholder="Juan Dela Cruz" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    placeholder="e.g. 09171234567" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition text-sm">
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-3 rounded-xl font-semibold text-sm transition shadow-md">
                Send Verification Code
            </button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('register') }}" class="text-sm text-green-600 hover:underline">← Register with Email instead</a>
        </div>

    </div>
</div>
</x-layout>