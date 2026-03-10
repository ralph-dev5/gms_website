<x-layout>
    <div class="min-h-screen flex bg-gray-100">
        <aside class="w-64 bg-white shadow-md flex flex-col">
            <div class="p-6 border-b">
                <h2 class="text-2xl font-bold text-gray-800">Admin Dashboard</h2>
            </div>
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-200 font-semibold">Home</a>
                <a href="{{ route('reports.index') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-200 font-semibold">View Reports</a>
                <a href="{{ route('settings') }}" class="block px-4 py-2 rounded-lg text-gray-700 hover:bg-gray-200 font-semibold">Settings</a>
            </nav>
            <div class="p-4 border-t">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition font-semibold">Logout</button>
                </form>
            </div>
        </aside>
        <main class="flex-1 p-10">
            <h1 class="text-3xl font-bold mb-6">Welcome, <span class="text-green-500">{{ auth()->user()->name }}</span> (Admin)!</h1>
            <p class="text-gray-600 mb-8">You can manage all reports and users from here.</p>
        </main>
    </div>
</x-layout>