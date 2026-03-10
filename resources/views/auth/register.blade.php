<x-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold text-center mb-6">Register</h2>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full p-2 border border-gray-300 rounded mt-1 focus:outline-none focus:ring-2 focus:ring-green-400">
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="w-full p-2 border border-gray-300 rounded mt-1 focus:outline-none focus:ring-2 focus:ring-green-400">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full p-2 border border-gray-300 rounded mt-1 focus:outline-none focus:ring-2 focus:ring-green-400">
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full p-2 border border-gray-300 rounded mt-1 focus:outline-none focus:ring-2 focus:ring-green-400">
                </div>

                <button type="submit"
                    class="w-full bg-green-500 text-white p-2 rounded hover:bg-green-600 transition">
                    Register
                </button>
            </form>

            <p class="mt-4 text-center text-gray-600">
                Already have an account? <a href="{{ route('login') }}" class="text-red-500 hover:underline">Login</a>
            </p>
        </div>
    </div>
</x-layout>