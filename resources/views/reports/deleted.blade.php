<x-layout>
<div class="min-h-screen flex bg-gray-100">

    @include('partials.user-sidebar')

    <main class="flex-1 p-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Deleted Reports</h1>
        <p class="text-gray-600 mb-8">Reports you have deleted.</p>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Title</th>
                        <th class="p-3">Location</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Deleted At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $report->title }}</td>
                        <td class="p-3">
                            @if($report->location)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $report->location }}"
                                   target="_blank" class="text-blue-600 hover:underline text-sm">View Map</a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="p-3">
                            <span class="px-2 py-1 text-sm rounded bg-gray-200 text-gray-700">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </td>
                        <td class="p-3 text-gray-500">{{ $report->deleted_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-500">No deleted reports.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>
</x-layout>
