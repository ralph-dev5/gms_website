<x-layout>
    <div class="min-h-screen flex bg-gray-100">

        @include('partials.user-sidebar')

        <main class="flex-1 p-4 pt-16 md:pt-10 md:p-10 min-w-0">

            <div class="flex justify-between items-center mb-2">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 pl-2">
                    Welcome, <span class="text-green-500">{{ auth()->user()->name }}</span>!
                </h1>
                @include('partials.profile-dropdown')
            </div>

            <p class="text-gray-600 mb-6 pl-2">You have successfully logged in. Use the sidebar to navigate your dashboard.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm flex flex-col items-center group hover:border-yellow-400 transition-colors">
                    <h2 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Pending</h2>
                    <p class="text-4xl font-black text-yellow-600">{{ $pendingCount }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm flex flex-col items-center group hover:border-blue-400 transition-colors">
                    <h2 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-2">In Progress</h2>
                    <p class="text-4xl font-black text-blue-600">{{ $inProgressCount }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm flex flex-col items-center group hover:border-green-400 transition-colors">
                    <h2 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Completed</h2>
                    <p class="text-4xl font-black text-green-600">{{ $completedCount }}</p>
                </div>
            </div>

            <div class="flex flex-col gap-2 mb-6 px-2">
                <div class="flex justify-between items-center">
                    <h2 class="font-bold text-gray-800 text-xl">My Reports</h2>
                    @if($hasOpenReport)
                        <button type="button"
                            class="bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl transition-all font-bold text-sm shadow-inner"
                            disabled>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Report
                        </button>
                    @else
                        <a href="{{ route('reports.create') }}"
                            class="bg-green-600 text-white px-5 py-2.5 rounded-xl hover:bg-green-700 transition-all font-bold text-sm shadow-lg shadow-green-100 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Report
                        </a>
                    @endif
                </div>
                @if($hasOpenReport)
                    <p class="text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-3">
                        You already have an active report. You may submit a new one once your current report is completed.
                    </p>
                @endif
            </div>

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-[10px] font-black tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="p-4">Image</th>
                                <th class="p-4">Title/Street</th>
                                <th class="p-4">Map</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Submitted</th>
                                <th class="p-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($reports as $report)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="p-4">
                                        @if($report->image)
                                            <div class="relative group">
                                                <img src="{{ $report->image }}"
                                                    class="w-14 h-14 object-cover rounded-xl shadow-sm border border-gray-200 group-hover:scale-105 transition-transform cursor-pointer"
                                                    onclick="openModal(this.src)">
                                            </div>
                                        @else
                                            <div class="w-14 h-14 bg-gray-50 rounded-xl flex items-center justify-center border border-dashed border-gray-200">
                                                <span class="text-[10px] text-gray-400 font-bold uppercase">No Pic</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <div class="text-sm font-bold text-gray-800">{{ $report->title }}</div>
                                        <div class="text-[11px] text-gray-400 font-medium uppercase tracking-tight">{{ $report->street ?? 'Main Street' }}</div>
                                    </td>
                                    <td class="p-4 text-sm">
                                        @if ($report->location)
                                            <a href="https://www.google.com/maps?q={{ $report->location }}"
                                                target="_blank"
                                                class="inline-flex items-center gap-1 text-blue-600 font-bold hover:text-blue-800 transition-colors text-xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                View
                                            </a>
                                        @else
                                            <span class="text-gray-300 italic text-xs">None</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full
                                            {{ ($report->status ?? 'pending') == 'pending' ? 'bg-amber-100 text-amber-700'
                                                : (($report->status) == 'in_progress' ? 'bg-blue-100 text-blue-700'
                                                : 'bg-emerald-100 text-emerald-700') }}">
                                            {{ ucfirst(str_replace('_', ' ', $report->status ?? 'pending')) }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-gray-400 text-[11px] font-bold uppercase whitespace-nowrap">
                                        {{ $report->created_at->format('M d, Y') }}<br>
                                        <span class="text-[9px] font-medium text-gray-300">{{ $report->created_at->format('h:i A') }}</span>
                                    </td>
                                    <td class="p-4 text-center">
                                        {{-- Only show delete button when report is completed --}}
                                        @if($report->status === 'completed')
                                            <form action="{{ route('reports.destroy', $report->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this completed report?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-full transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            {{-- Show lock icon with tooltip for non-completed reports --}}
                                            <span class="text-gray-300 cursor-not-allowed" title="Can only delete completed reports">
                                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                </svg>
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-10 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                            </svg>
                                            <p class="text-gray-500 font-bold">No reports found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div id="image-modal" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center p-4" onclick="closeModal()">
        <div class="relative max-w-3xl w-full" onclick="event.stopPropagation()">
            <button onclick="closeModal()" class="absolute -top-10 right-0 text-white text-3xl font-bold">×</button>
            <img id="modal-image" src="" class="w-full rounded-2xl shadow-2xl max-h-[80vh] object-contain">
        </div>
    </div>

    <script>
        function openModal(src) {
            document.getElementById('modal-image').src = src;
            document.getElementById('image-modal').classList.remove('hidden');
            document.getElementById('image-modal').classList.add('flex');
        }
        function closeModal() {
            document.getElementById('image-modal').classList.add('hidden');
            document.getElementById('image-modal').classList.remove('flex');
        }
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
    </script>
</x-layout>