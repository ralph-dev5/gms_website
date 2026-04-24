<x-layout>
<div class="min-h-screen flex bg-gray-100">

    @include('partials.user-sidebar')

    <main class="flex-1 p-10">
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
                <form method="POST" action="{{ route('settings.updateProfile') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                        @if($user->profile_photo_path)  
                        @endif
                        <input type="file" name="profile_photo" accept="image/*"
                            class="w-full border rounded-lg px-4 py-2">
                    </div>

                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                        Update Profile
                    </button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-semibold mb-4">Change Password</h2>
                <form method="POST" action="{{ route('settings.updatePassword') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                        <input type="password" name="current_password"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <input type="password" name="password"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                        <input type="password" name="password_confirmation"
                            class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>

                    <button type="submit"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-semibold">
                        Change Password
                    </button>
                </form>
            </div>

        </div>
    </main>
</div>
</x-layout>
