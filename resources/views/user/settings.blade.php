<x-layout>
@php
    $isGoogle = !empty($user->google_id);
@endphp

<div class="min-h-screen flex bg-gray-100">

    @include('partials.user-sidebar')

    <main class="flex-1 p-10 pt-16 md:pt-10">
        <h1 class="text-3xl font-bold mb-6">Settings</h1>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="max-w-2xl space-y-6">

            <!-- Update Profile -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Update Profile</h2>
                <form method="POST" action="{{ route('settings.updateProfile') }}"
                    enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Name: readonly for all users --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" value="{{ $user->name }}"
                            readonly disabled
                            class="w-full border rounded-lg px-4 py-2 bg-gray-100 text-gray-500 cursor-not-allowed">
                        <p class="text-xs text-gray-400 mt-1">Name cannot be changed.</p>
                    </div>

                    {{-- Email/Username: readonly for all users --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $isGoogle ? 'Email' : 'Username' }}
                        </label>
                        <input type="text" value="{{ $user->email }}"
                            readonly disabled
                            class="w-full border rounded-lg px-4 py-2 bg-gray-100 text-gray-500 cursor-not-allowed">
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $isGoogle ? 'Email cannot be changed.' : 'Username cannot be changed.' }}
                        </p>
                    </div>

                    {{-- Profile Photo with current photo preview --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>

                        {{-- Current / preview image --}}
                        <div class="flex items-center gap-4 mb-3">
                            <img id="photoPreview"
                                src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=16a34a&color=fff&size=80' }}"
                                alt="Profile photo"
                                class="w-16 h-16 rounded-full object-cover border border-gray-200">
                            <span class="text-sm text-gray-500">Current photo</span>
                        </div>

                        <input type="file" name="profile_photo" id="profilePhotoInput" accept=".jpg,.jpeg,.png"
                            class="w-full border rounded-lg px-4 py-2"
                            onchange="previewPhoto(event)">
                        <p class="text-xs text-gray-400 mt-1">Max 2MB. JPG, PNG accepted.</p>
                    </div>

                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                        Update Profile Photo
                    </button>
                </form>
            </div>

            <!-- Change Password (hidden for Google users) -->
            @if(!$isGoogle)
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Change Password</h2>
                <form method="POST" action="{{ route('settings.updatePassword') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <div class="relative">
                            <input type="password" name="current_password" id="currentPassword"
                                class="w-full border rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <button type="button" onclick="togglePassword('currentPassword', 'eyeCurrent')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg id="eyeCurrent" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="newPassword"
                                class="w-full border rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-green-500"
                                oninput="checkStrength(this.value); checkMatch()">
                            <button type="button" onclick="togglePassword('newPassword', 'eyeNew')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg id="eyeNew" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        {{-- Password strength bar --}}
                        <div class="flex gap-1 mt-2" id="strengthBar">
                            <div class="h-1 flex-1 rounded bg-gray-200" id="seg1"></div>
                            <div class="h-1 flex-1 rounded bg-gray-200" id="seg2"></div>
                            <div class="h-1 flex-1 rounded bg-gray-200" id="seg3"></div>
                            <div class="h-1 flex-1 rounded bg-gray-200" id="seg4"></div>
                        </div>
                        <p class="text-xs mt-1" id="strengthLabel"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="confirmPassword"
                                class="w-full border rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-green-500"
                                oninput="checkMatch()">
                            <button type="button" onclick="togglePassword('confirmPassword', 'eyeConfirm')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg id="eyeConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs mt-1" id="matchHint"></p>
                    </div>

                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                        Change Password
                    </button>
                </form>
            </div>
            @else
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-2">Change Password</h2>
                <p class="text-sm text-gray-500">
                    You signed in with Google. Password management is handled by your Google account.
                </p>
            </div>
            @endif

        </div>
    </main>
</div>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
        alert('File exceeds 2MB limit. Please choose a smaller image.');
        event.target.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('photoPreview').src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    icon.innerHTML = isHidden
        ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`
        : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
}

function checkStrength(val) {
    const segs = ['seg1','seg2','seg3','seg4'].map(id => document.getElementById(id));
    const label = document.getElementById('strengthLabel');
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const colors = ['bg-red-400','bg-orange-400','bg-yellow-400','bg-green-500'];
    const labels = [['','text-gray-400'],['Weak','text-red-500'],['Fair','text-orange-500'],['Good','text-yellow-600'],['Strong','text-green-600']];
    segs.forEach((s, i) => {
        s.className = 'h-1 flex-1 rounded ' + (i < score ? colors[score - 1] : 'bg-gray-200');
    });
    label.textContent = val.length ? labels[score][0] : '';
    label.className = 'text-xs mt-1 ' + (val.length ? labels[score][1] : '');
}

function checkMatch() {
    const newPw = document.getElementById('newPassword').value;
    const confirmPw = document.getElementById('confirmPassword').value;
    const hint = document.getElementById('matchHint');
    if (!confirmPw) { hint.textContent = ''; return; }
    if (newPw === confirmPw) {
        hint.textContent = '✓ Passwords match';
        hint.className = 'text-xs mt-1 text-green-600';
    } else {
        hint.textContent = '✗ Passwords do not match';
        hint.className = 'text-xs mt-1 text-red-500';
    }
}
</script>
</x-layout>