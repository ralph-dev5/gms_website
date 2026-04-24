<x-layout>
<div class="min-h-screen flex bg-gray-100">

    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <main class="flex-1 p-4 md:p-10 min-w-0">

        <div class="flex justify-between items-center mb-2">
            <h1 class="text-2xl md:text-3xl font-bold">
                Welcome, <span class="text-green-500">{{ auth()->user()->name }}</span>
            </h1>
            @include('partials.profile-dropdown')
        </div>
        <p class="text-gray-600 mb-6">Here are the latest reports submitted by users.</p>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-4 border-b font-semibold text-lg">User Reports</div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px]">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Image</th>
                            <th class="p-3 text-left">User</th>
                            <th class="p-3 text-left">Title</th>
                            <th class="p-3 text-left">Location</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Update</th>
                            <th class="p-3 text-left">Date</th>
                            <th class="p-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr class="border-b hover:bg-gray-50">

                            <td class="p-3">
                                @if($report->image)
                                    <img src="{{ asset('storage/' . $report->image) }}" class="w-14 h-14 object-cover rounded">
                                @else
                                    <span class="text-gray-400 text-sm">No Image</span>
                                @endif
                            </td>

                            <td class="p-3 font-semibold text-sm">{{ $report->user->name }}</td>
                            <td class="p-3 text-sm">{{ $report->title }}</td>

                            <td class="p-3 text-sm">
                                @if($report->location)
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $report->location }}"
                                       target="_blank" class="text-blue-600 hover:underline">View Map</a>
                                @else
                                    N/A
                                @endif
                            </td>

                            <td class="p-3">
                                <span class="px-2 py-1 text-xs rounded whitespace-nowrap
                                    {{ $report->status == 'pending' ? 'bg-yellow-200 text-yellow-800'
                                        : ($report->status == 'in_progress' ? 'bg-blue-200 text-blue-800'
                                        : 'bg-green-200 text-green-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                </span>
                            </td>

                            <td class="p-3">
                                <form action="{{ route('admin.reports.updateStatus', $report->id) }}" method="POST" class="flex items-center gap-1">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="border rounded px-2 py-1 text-xs">
                                        <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $report->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $report->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                    <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600 whitespace-nowrap">
                                        Update
                                    </button>
                                </form>
                            </td>

                            <td class="p-3 text-gray-500 text-xs whitespace-nowrap">{{ $report->created_at->format('M d, Y') }}</td>

                            <td class="p-3">
                                @if($report->status === 'completed')
                                    <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this completed report?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-6 text-center text-gray-500">No reports submitted yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>
</x-layout>
