<x-layout>
<div class="min-h-screen flex bg-gray-100">

    @include('admin.partials.sidebar')

    <main class="flex-1 p-4 md:p-10 min-w-0 pt-16 md:pt-10">

        <div class="mb-4">
            <a href="{{ route('analytics') }}" class="text-green-600 hover:underline text-sm">&larr; Back to Analytics</a>
        </div>

        <h1 class="text-2xl md:text-3xl font-bold mb-1">{{ $street }}</h1>
        <p class="text-gray-500 mb-6">
            Reports from {{ $startDate->format('M d, Y') }} — {{ $endDate->format('M d, Y') }}
        </p>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="bg-green-600 px-6 py-3">
                <h3 class="text-white font-semibold text-lg">{{ $reports->count() }} Report(s)</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[500px]">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Image</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">User</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Location</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">
                                @if($report->image)
                                    <img src="{{ asset('storage/' . $report->image) }}" class="w-14 h-14 object-cover rounded">
                                @else
                                    <span class="text-gray-400 text-sm">No Image</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-800 text-sm">{{ $report->user->name }}</p>
                                <p class="text-xs text-gray-400">{{ $report->user->email }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($report->location)
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $report->location }}"
                                       target="_blank" class="text-blue-600 hover:underline">View Map</a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded whitespace-nowrap
                                    {{ $report->status == 'pending' ? 'bg-yellow-100 text-yellow-800'
                                        : ($report->status == 'in_progress' ? 'bg-blue-100 text-blue-800'
                                        : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                </span>
                                @if($report->deleted_at)
                                    <span class="ml-1 px-2 py-1 text-xs rounded bg-gray-100 text-gray-500">Deleted</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                                {{ $report->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">No reports found for this street.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>
</x-layout>
