<x-layout>
<div class="min-h-screen flex items-center justify-center bg-gray-100">

    <div class="bg-white p-8 rounded-lg shadow-lg w-96">

        <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

        <!-- Login Form -->
        <form method="POST" action="/login">
            @csrf

            <input 
                type="email" 
                name="email" 
                placeholder="Email"
                class="w-full border rounded p-2 mb-3 focus:outline-none focus:ring-2 focus:ring-green-500">

            <input 
                type="password" 
                name="password" 
                placeholder="Password"
                class="w-full border rounded p-2 mb-4 focus:outline-none focus:ring-2 focus:ring-green-500">

            <button 
                class="bg-green-600 text-white px-4 py-2 w-full rounded hover:bg-green-700 transition">
                Login
            </button>
        </form>

        <!-- Divider -->
        <div class="flex items-center my-4">
            <hr class="flex-grow border-gray-300">
            <span class="px-2 text-gray-500 text-sm">OR</span>
            <hr class="flex-grow border-gray-300">
        </div>

        <!-- Google Login -->
        <a href="/auth/google/redirect"
           class="flex items-center justify-center border rounded py-2 hover:bg-gray-100 transition">

            <img src="https://developers.google.com/identity/images/g-logo.png" 
                 class="w-5 h-5 mr-2">

            <span>Sign in with Google</span>
        </a>

        <!-- Register -->
        <p class="text-center text-sm mt-6 text-gray-600">
            Don't have an account?
            <a href="/register" class="text-green-600 font-semibold hover:underline">
                Register
            </a>
        </p>

    </div>

</div>
</x-layout>