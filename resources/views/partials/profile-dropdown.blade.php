@php
    $dropdownId  = 'pd_' . uniqid();
    $user        = auth()->user();
    $isGoogle    = !empty($user->google_id);

    // Google user: show email initial | Username user: show username initial
    $avatarLabel = $isGoogle
        ? strtoupper(substr($user->email, 0, 1))
        : strtoupper(substr($user->name, 0, 1));
@endphp

<div class="relative inline-block" id="{{ $dropdownId }}_wrapper">
    <button onclick="toggleMenu('{{ $dropdownId }}')" class="flex items-center gap-2 focus:outline-none cursor-pointer">

        @if($user->profile_photo_path)
            <img src="{{ $user->profile_photo_url }}"
                alt="{{ $user->name }}"
                class="w-12 h-12 rounded-full border-2 border-green-500 shadow-md object-cover">
        @else
            {{-- Avatar circle: shows email initial for Google users, username initial for regular users --}}
            <div class="w-12 h-12 rounded-full border-2 border-green-500 shadow-md bg-green-600 flex items-center justify-center">
                <span class="text-white font-bold text-lg">{{ $avatarLabel }}</span>
            </div>
        @endif

        <svg id="{{ $dropdownId }}_chevron"
            class="w-5 h-5 text-gray-500 transition-transform duration-200"
            fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div id="{{ $dropdownId }}_menu"
        class="hidden absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-gray-100 z-50">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-xl font-medium">
                Logout
            </button>
        </form>
    </div>
</div>

<script>
    function toggleMenu(id) {
        const menu = document.getElementById(id + '_menu');
        const chevron = document.getElementById(id + '_chevron');
        menu.classList.toggle('hidden');
        chevron.classList.toggle('rotate-180');
    }

    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('{{ $dropdownId }}_wrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            document.getElementById('{{ $dropdownId }}_menu').classList.add('hidden');
            document.getElementById('{{ $dropdownId }}_chevron').classList.remove('rotate-180');
        }
    });
</script>

<!-- Mobile toggle button -->
<div class="md:hidden fixed top-4 left-4 z-50">
    <button onclick="document.getElementById('user-sidebar').classList.toggle('-translate-x-full')"
        class="bg-white shadow p-2 rounded-lg">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
</div>

<aside id="user-sidebar"
    class="fixed md:static z-40 inset-y-0 left-0 w-64 bg-white shadow-md flex flex-col shrink-0
           transform -translate-x-full md:translate-x-0 transition-transform duration-200">

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