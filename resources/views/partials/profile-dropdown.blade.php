@php
    $dropdownId  = 'pd_' . uniqid();
    $user        = auth()->user();
    $isGoogle    = !empty($user->google_id);

    // Google user: show email initial | Username user: show name initial
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