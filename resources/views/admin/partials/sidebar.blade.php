<!-- Mobile toggle button -->
<div class="md:hidden fixed top-4 left-4 z-50">
    <button onclick="document.getElementById('admin-sidebar').classList.toggle('-translate-x-full')"
        class="bg-white shadow p-2 rounded-lg">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
</div>

<aside id="admin-sidebar"
    class="fixed md:static z-40 inset-y-0 left-0 w-64 bg-white shadow-lg flex flex-col shrink-0
           transform -translate-x-full md:translate-x-0 transition-transform duration-200">

    <div class="p-6 border-b">
        <h2 class="text-xl font-bold text-gray-800 pl-8 md:pl-0">Admin Panel</h2>
    </div>

    <nav class="flex-1 p-4 space-y-2">
        <a href="{{ route('admin.dashboard') }}"
           class="block px-4 py-2 rounded-lg font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-green-100 text-green-700' : 'hover:bg-gray-200 text-gray-700' }}">
            Dashboard
        </a>
        <a href="{{ route('admin.users') }}"
           class="block px-4 py-2 rounded-lg font-semibold {{ request()->routeIs('admin.users*') ? 'bg-green-100 text-green-700' : 'hover:bg-gray-200 text-gray-700' }}">
            Users
        </a>
        <a href="{{ route('analytics') }}"
           class="block px-4 py-2 rounded-lg font-semibold {{ request()->routeIs('analytics') ? 'bg-green-100 text-green-700' : 'hover:bg-gray-200 text-gray-700' }}">
            Analytics
        </a>
        <a href="{{ route('deleted-reports') }}"
           class="block px-4 py-2 rounded-lg font-semibold {{ request()->routeIs('deleted-reports') ? 'bg-green-100 text-green-700' : 'hover:bg-gray-200 text-gray-700' }}">
            Deleted Reports
        </a>
        <a href="{{ route('admin.settings') }}"
           class="block px-4 py-2 rounded-lg font-semibold {{ request()->routeIs('admin.settings') ? 'bg-green-100 text-green-700' : 'hover:bg-gray-200 text-gray-700' }}">
            Settings
        </a>
    </nav>

    <div class="p-4 border-t"></div>
</aside>