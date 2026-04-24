<x-layout>
    <div class="min-h-screen bg-gray-100 p-6">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">My Reports</h1>
                <a href="{{ route('reports.create') }}"
                   class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition font-semibold">
                    Add New Report
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
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
                            <th class="p-3">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-semibold">{{ $report->title }}</td>
                            <td class="p-3">{{ $report->location }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-sm rounded
                                    {{ $report->status == 'pending' ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800' }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </td>
                            <td class="p-3 text-gray-500">{{ $report->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">
                                No reports submitted yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layout>
