<x-layout>
    <div class="min-h-screen flex bg-gray-100">

        @include('admin.partials.sidebar')

        <main class="flex-1 p-4 pt-16 md:pt-10 md:p-10 min-w-0">

            <div class="flex justify-between items-center mb-2">
                <h1 class="text-2xl md:text-3xl font-bold pl-2">
                    Welcome, <span class="text-green-500">{{ auth()->user()->name }}</span>
                </h1>
                @include('partials.profile-dropdown')
            </div>
            <p class="text-gray-600 mb-6">Here are the latest reports submitted by users.</p>

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200">
                <div class="p-5 border-b border-gray-100 bg-white flex items-center justify-between">
                    <h2 class="font-bold text-gray-800 text-lg">Recent User Reports</h2>
                    <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">
                        {{ $reports->count() }} Total
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-black tracking-widest">
                            <tr>
                                <th class="p-4">Image</th>
                                <th class="p-4">User</th>
                                <th class="p-4">Street</th>
                                <th class="p-4">Map</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Update</th>
                                <th class="p-4">Submitted</th>
                                <th class="p-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($reports as $report)
                                <tr class="hover:bg-gray-50/50 transition-colors {{ $report->trashed() ? 'opacity-60 bg-red-50/30' : '' }}">
                                    <td class="p-4">
                                        @if($report->image)
                                            <div class="relative group">
                                                <img src="{{ Str::startsWith($report->image, 'http') ? $report->image : asset('storage/' . $report->image) }}"
                                                    class="w-14 h-14 object-cover rounded-xl shadow-sm border border-gray-200 group-hover:scale-105 transition-transform cursor-pointer"
                                                    onclick="openModal(this.src)"
                                                    onerror="this.onerror=null;this.src='https://placehold.co/100?text=No+File';">
                                            </div>
                                        @else
                                            <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center border border-dashed border-gray-300">
                                                <span class="text-[10px] text-gray-400 font-bold uppercase">No Pic</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-4 text-sm">
                                        <div class="font-bold text-gray-700">{{ $report->user->name }}</div>
                                        {{-- Show badge if user deleted this report --}}
                                        @if($report->trashed())
                                            <span class="text-[10px] text-red-500 font-bold uppercase tracking-wide">Deleted by user</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-sm text-gray-600 font-medium">{{ $report->street ?? $report->title }}</td>
                                    <td class="p-4 text-sm">
                                        @if($report->location)
                                            <a href="https://www.google.com/maps?q={{ $report->location }}" target="_blank"
                                                class="inline-flex items-center gap-1 text-blue-600 font-bold hover:text-blue-800 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                Pin
                                            </a>
                                        @else
                                            <span class="text-gray-400 italic">None</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full
                                            {{ $report->status == 'pending' ? 'bg-amber-100 text-amber-700'
                                                : ($report->status == 'in_progress' ? 'bg-blue-100 text-blue-700'
                                                : 'bg-emerald-100 text-emerald-700') }}">
                                            {{ str_replace('_', ' ', $report->status) }}
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        {{-- Disable status update for user-deleted reports --}}
                                        @if(!$report->trashed())
                                            <form action="{{ route('admin.reports.updateStatus', $report->id) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="bg-gray-50 border border-gray-200 rounded-lg px-2 py-1.5 text-xs font-bold focus:ring-2 focus:ring-green-500 transition-all outline-none">
                                                    <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="in_progress" {{ $report->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="completed" {{ $report->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                </select>
                                                <button type="submit" class="p-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors shadow-md shadow-green-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-300 text-xs italic">—</span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-gray-400 text-[11px] font-bold uppercase">{{ $report->created_at->format('M d, Y h:i A') }}</td>
                                    <td class="p-4 text-center">
                                        @if($report->status === 'completed')
                                            <form action="{{ route('admin.reports.destroy', $report->id) }}" method="POST"
                                                  onsubmit="return confirm('Delete this completed report permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-full transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-10 text-center">
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

    <!-- Image Modal -->
    <div id="image-modal" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center p-4" onclick="closeModal()">
        <div class="relative max-w-3xl w-full" onclick="event.stopPropagation()">
            <button onclick="closeModal()" class="absolute -top-10 right-0 text-white text-3xl font-bold">&times;</button>
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