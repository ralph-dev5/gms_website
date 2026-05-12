<!-- USER SIDEBAR -->
<aside id="user-sidebar"
    class="fixed md:static z-40 inset-y-0 left-0 w-64 bg-white shadow-md flex flex-col shrink-0
           transform -translate-x-full md:translate-x-0 transition-transform duration-200">

    <!-- Mobile toggle button - inside sidebar at top right -->
    <button 
        onclick="document.getElementById('user-sidebar').classList.toggle('-translate-x-full')"
        class="md:hidden absolute top-4 right-4 z-50 bg-white shadow-lg p-2 rounded-lg">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <div class="p-6 pt-16 md:pt-6 border-b">
        <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
    </div>

    <nav class="flex-1 p-4 space-y-2">
        <a href="{{ route('dashboard') }}"
            class="block px-4 py-2 rounded-lg font-semibold {{ request()->routeIs('dashboard') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-200' }}">
            Home
        </a>
        <a href="{{ route('settings') }}"
            class="block px-4 py-2 rounded-lg font-semibold {{ request()->routeIs('settings') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-200' }}">
            Settings
        </a>
        <a href="{{ route('reports.deleted') }}"
            class="block px-4 py-2 rounded-lg font-semibold {{ request()->routeIs('reports.deleted') ? 'bg-green-100 text-green-700' : 'text-gray-700 hover:bg-gray-200' }}">
            Deleted Reports
        </a>
    </nav>
</aside>