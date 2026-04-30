<x-layout>
<div class="min-h-screen flex bg-gray-100">

    @include('admin.partials.sidebar')

    <main class="flex-1 p-4 md:p-10 min-w-0 pt-16 md:pt-10">
        <h1 class="text-2xl md:text-3xl font-bold mb-1">Deleted Reports</h1>
        <p class="text-gray-600 mb-6">Reports that have been soft-deleted by users.</p>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[500px]">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">User</th>
                        <th class="p-3">Title</th>
                        <th class="p-3">Location</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Deleted At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 font-semibold">{{ $report->user->name }}</td>
                        <td class="p-3">{{ $report->title }}</td>
                        <td class="p-3 text-gray-600">
                            @if($report->location)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ $report->location }}"
                                   target="_blank" class="text-blue-600 hover:underline">
                                    {{ $report->location }}
                                </a>
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="p-3">
                            <span class="px-2 py-1 text-sm rounded bg-gray-200 text-gray-700">
                                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </td>
                        <td class="p-3 text-gray-500">{{ $report->deleted_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500">No deleted reports.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </main>

</div>
</x-layout>