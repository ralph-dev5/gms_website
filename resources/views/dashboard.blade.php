<x-layout>
    <div class="min-h-screen flex bg-gray-100">

        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md flex flex-col">
            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('dashboard') }}" 
                   class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-200 font-semibold">
                   Home
                </a>
                <a href="{{ route('reports.index') }}" 
                   class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-200 font-semibold">
                   View Reports
                </a>
                <a href="{{ route('settings') }}" 
                   class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-200 font-semibold">
                   Settings
                </a>
            </nav>

            <div class="p-4 border-t">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="text-red px-4 py-2 rounded hover:bg-red-600 transition font-semibold">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-10">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">
                Welcome, <span class="text-green-500">{{ auth()->user()->name }}</span>!
            </h1>

            <p class="text-gray-600 mb-8">
                You have successfully registered and are logged in.
            </p>

            <!-- Status Boxes -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-yellow-100 text-yellow-800 rounded-lg p-6 shadow flex flex-col items-center">
                    <h2 class="text-xl font-bold mb-2">Pending</h2>
                    <p class="text-2xl font-extrabold">5</p>
                </div>
                <div class="bg-blue-100 text-blue-800 rounded-lg p-6 shadow flex flex-col items-center">
                    <h2 class="text-xl font-bold mb-2">In Progress</h2>
                    <p class="text-2xl font-extrabold">3</p>
                </div>
                <div class="bg-green-100 text-green-800 rounded-lg p-6 shadow flex flex-col items-center">
                    <h2 class="text-xl font-bold mb-2">Completed</h2>
                    <p class="text-2xl font-extrabold">12</p>
                </div>
            </div>

            <!-- Add Report Button -->
            <a href="{{ route('reports.create') }}"
               class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition font-semibold">
               + Add Report
            </a>
        </main>
    </div>
</x-layout>