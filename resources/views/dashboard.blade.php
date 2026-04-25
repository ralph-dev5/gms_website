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

            <p class="text-gray-600 mb-6">You have successfully logged in. Use the sidebar to navigate your dashboard.</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-yellow-100 text-yellow-800 rounded-lg p-6 shadow flex flex-col items-center">
                    <h2 class="text-xl font-bold mb-2">Pending</h2>
                    <p class="text-2xl font-extrabold">{{ $pendingCount }}</p>
                </div>
                <div class="bg-blue-100 text-blue-800 rounded-lg p-6 shadow flex flex-col items-center">
                    <h2 class="text-xl font-bold mb-2">In Progress</h2>
                    <p class="text-2xl font-extrabold">{{ $inProgressCount }}</p>
                </div>
                <div class="bg-green-100 text-green-800 rounded-lg p-6 shadow flex flex-col items-center">
                    <h2 class="text-xl font-bold mb-2">Completed</h2>
                    <p class="text-2xl font-extrabold">{{ $completedCount }}</p>
                </div>
            </div>

            <a href="{{ route('reports.create') }}"
                class="inline-block bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition font-semibold mb-6">
                + Add Report
            </a>

            <div class="bg-white rounded-lg shadow p-4 md:p-6">
                <h2 class="text-2xl font-bold mb-4">My Reports</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full border rounded-lg min-w-[600px]">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="py-2 px-4 text-left">Image</th>
                                <th class="py-2 px-4 text-left">Title</th>
                                <th class="py-2 px-4 text-left">Location</th>
                                <th class="py-2 px-4 text-left">Status</th>
                                <th class="py-2 px-4 text-left">Date & Time</th>
                                <th class="py-2 px-4 text-left">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reports as $report)
                                <tr class="border-b">
                                    <td class="py-2 px-4">
                                        @if($report->image)
                                            <img src="{{ $report->image }}"
                                                class="w-14 h-14 object-cover rounded cursor-pointer hover:opacity-80 transition"
                                                onclick="openModal(this.src)">
                                        @else
                                            <span class="text-gray-400 text-sm">No Image</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-4 text-sm">{{ $report->title }}</td>
                                    <td class="py-2 px-4 text-sm">
                                        @if ($report->location)
                                            <a href="https://www.google.com/maps/search/?api=1&query={{ $report->location }}"
                                                target="_blank" class="text-blue-600 hover:underline">View Map</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="py-2 px-4">
                                        <span class="px-2 py-1 text-xs rounded whitespace-nowrap
                                            {{ ($report->status ?? 'pending') == 'pending' ? 'bg-yellow-100 text-yellow-800'
                                                : (($report->status) == 'in_progress' ? 'bg-blue-100 text-blue-800'
                                                : 'bg-green-100 text-green-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $report->status ?? 'pending')) }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-4 text-xs text-gray-500 whitespace-nowrap">{{ $report->created_at->format('M d, Y h:i A') }}</td>
                                    <td class="py-2 px-4">
                                        <form action="{{ route('reports.destroy', $report->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this report?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-xs">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 px-4 text-center text-gray-500">No reports found.</td>
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