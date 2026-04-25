<x-layout>
<div class="min-h-screen flex flex-col md:flex-row bg-gray-100">

    @include('partials.user-sidebar')

    <main class="flex-1 p-4 md:p-10 min-w-0 w-full">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Welcome, <span class="text-green-500">{{ auth()->user()->name }}</span>!
                </h1>
                <p class="text-gray-600 text-sm mt-1">You have successfully logged in.</p>
            </div>
            <div class="self-end md:self-auto">
                @include('partials.profile-dropdown')
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-yellow-100 text-yellow-800 rounded-2xl p-6 shadow-sm border border-yellow-200 flex flex-col items-center justify-center">
                <h2 class="text-sm font-bold uppercase tracking-wider mb-1">Pending</h2>
                <p class="text-3xl font-black">{{ $pendingCount }}</p>
            </div>
            <div class="bg-blue-100 text-blue-800 rounded-2xl p-6 shadow-sm border border-blue-200 flex flex-col items-center justify-center">
                <h2 class="text-sm font-bold uppercase tracking-wider mb-1">In Progress</h2>
                <p class="text-3xl font-black">{{ $inProgressCount }}</p>
            </div>
            <div class="bg-green-100 text-green-800 rounded-2xl p-6 shadow-sm border border-green-200 flex flex-col items-center justify-center">
                <h2 class="text-sm font-bold uppercase tracking-wider mb-1">Completed</h2>
                <p class="text-3xl font-black">{{ $completedCount }}</p>
            </div>
        </div>

        <a href="{{ route('reports.create') }}"
            class="block md:inline-block text-center bg-green-500 text-white px-8 py-4 rounded-xl hover:bg-green-600 transition font-bold mb-8 shadow-lg shadow-green-200">
            + Add New Report
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50">
                <h2 class="text-xl font-bold text-gray-800">My Reports History</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase">Image</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase">Title</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase">Location</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase">Status</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase">Date</th>
                            <th class="py-4 px-6 text-xs font-bold text-gray-400 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($reports as $report)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-4 px-6">
                                    @if($report->image)
                                        <img src="{{ $report->image }}" class="w-12 h-12 object-cover rounded-lg shadow-sm">
                                    @else
                                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <span class="text-[10px] text-gray-400 font-bold uppercase">No Pic</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="py-4 px-6 font-semibold text-gray-700 text-sm">{{ $report->title }}</td>
                                <td class="py-4 px-6">
                                    @if ($report->location)
                                        <a href="https://www.google.com/maps?q={{ $report->location }}"
                                            target="_blank" class="text-blue-600 text-xs font-bold hover:underline inline-flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                            View Map
                                        </a>
                                    @else
                                        <span class="text-gray-300 text-xs italic">No Data</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 text-[10px] font-bold rounded-full uppercase tracking-tighter
                                        {{ ($report->status ?? 'pending') == 'pending' ? 'bg-yellow-100 text-yellow-700'
                                            : (($report->status) == 'in_progress' ? 'bg-blue-100 text-blue-700'
                                            : 'bg-green-100 text-green-700') }}">
                                        {{ str_replace('_', ' ', $report->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-[11px] text-gray-400 font-medium">
                                    {{ $report->created_at->format('M d, Y') }}<br>
                                    {{ $report->created_at->format('h:i A') }}
                                </td>
                                <td class="py-4 px-6">
                                    <form action="{{ route('reports.destroy', $report->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this report?');">
                                        @csrf @method('DELETE')
                                        <button class="text-red-500 hover:text-red-700 text-xs font-bold p-2 hover:bg-red-50 rounded-lg transition">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-400 italic">No reports found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>
</x-layout>