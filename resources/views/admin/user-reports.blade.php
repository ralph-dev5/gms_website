<x-layout>
<div class="min-h-screen flex bg-gray-100">

    @include('admin.partials.sidebar')

    <main class="flex-1 p-4 md:p-10 min-w-0">
        <div class="mb-4">
            <a href="{{ route('admin.users') }}" class="text-green-600 hover:underline text-sm">&larr; Back to Users</a>
        </div>

        <h1 class="text-2xl md:text-3xl font-bold mb-1">{{ $user->name }}'s Reports</h1>
        <p class="text-gray-500 mb-6">{{ $user->email }}</p>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[500px]">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Image</th>
                        <th class="p-3">Street</th>
                        <th class="p-3">Location</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">
                            @if($report->image)
                                <img src="{{ asset('storage/' . $report->image) }}" class="w-16 h-16 object-cover rounded">
                            @else
                                <span class="text-gray-400 text-sm">No Image</span>
                            @endif
                        </td>
                        <td class="p-3 font-semibold">{{ $report->title }}</td>
                        <td class="p-3 text-gray-600">
                            @if($report->location)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $report->location }}"
                                   target="_blank" class="text-blue-600 hover:underline text-sm">View Map</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="p-3">
                            <span class="px-2 py-1 text-sm rounded
                                {{ $report->status == 'pending' ? 'bg-yellow-100 text-yellow-800'
                                    : ($report->status == 'in_progress' ? 'bg-blue-100 text-blue-800'
                                    : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </td>
                        <td class="p-3 text-gray-500">{{ $report->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">This user has no reports.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </main>

</div>
</x-layout>
