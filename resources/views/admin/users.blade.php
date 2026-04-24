<x-layout>
<div class="min-h-screen flex bg-gray-100">

    @include('admin.partials.sidebar')

    <main class="flex-1 p-4 md:p-10 min-w-0">
        <h1 class="text-2xl md:text-3xl font-bold mb-1">Registered Users</h1>
        <p class="text-gray-600 mb-6">Manage all registered users.</p>

        <form method="GET" class="mb-6">
            <div class="flex gap-3 max-w-lg">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search users..."
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <button class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                    Search
                </button>
            </div>
        </form>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[500px]">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Name</th>
                        <th class="p-3">Email</th>
                        <th class="p-3">Role</th>
                        <th class="p-3">Registered</th>
                        <th class="p-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 font-semibold">
                            <a href="{{ route('admin.users.reports', $user->id) }}"
                               class="text-green-600 hover:underline">
                                {{ $user->name }}
                            </a>
                        </td>
                        <td class="p-3 text-gray-600">{{ $user->email }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 text-xs rounded {{ $user->is_admin ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $user->is_admin ? 'Admin' : 'User' }}
                            </span>
                        </td>
                        <td class="p-3 text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="p-3 text-center">
                            @if(!$user->is_admin)
                            <form method="POST" action="{{ route('admin.users.delete', $user->id) }}"
                                onsubmit="return confirm('Delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        <div class="mt-6">{{ $users->links() }}</div>
    </main>

</div>
</x-layout>
