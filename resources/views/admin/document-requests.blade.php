<x-layout>
    <div class="min-h-screen flex bg-gray-100">

        @include('admin.partials.sidebar')

        <main class="flex-1 p-4 md:p-10 min-w-0 pt-16 md:pt-10">

            <h1 class="text-2xl md:text-3xl font-bold mb-1">Document Requests</h1>
            <p class="text-gray-500 mb-6">Review and process barangay document requests from residents.</p>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
            @endif

            {{-- Status Filter --}}
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $key => $label)
                    <a href="{{ route('admin.documents.index', ['status' => $key]) }}"
                        class="px-4 py-2 rounded-full text-sm font-semibold border transition
                       {{ $status === $key ? 'bg-gray-900 text-white border-gray-900' : 'bg-white text-gray-600 border-gray-300 hover:border-gray-500' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[600px]">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Resident</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Document Type</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Purpose</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Date</th>
                                <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                                                    <tr class="border-b hover:bg-gray-50">
                                                        <td class="px-5 py-3">
                                                            <p class="font-semibold text-gray-800 text-sm">{{ $req->user->name }}</p>
                                                            <p class="text-xs text-gray-400">{{ $req->user->email }}</p>
                                                        </td>
                                                        <td class="px-5 py-3 text-sm font-medium text-gray-700">{{ $req->document_type }}</td>
                                                        <td class="px-5 py-3 text-sm text-gray-500 max-w-[180px] truncate">
                                                            {{ $req->purpose ?? '—' }}</td>
                                                        <td class="px-5 py-3">
                                                            <span class="px-2 py-1 text-xs rounded-full font-semibold whitespace-nowrap
                                                            {{ $req->status === 'pending' ? 'bg-yellow-100 text-yellow-700'
                                : ($req->status === 'approved' ? 'bg-green-100 text-green-700'
                                    : 'bg-red-100 text-red-700') }}">
                                                                {{ ucfirst($req->status) }}
                                                            </span>
                                                        </td>
                                                        <td class="px-5 py-3 text-xs text-gray-400 whitespace-nowrap">
                                                            {{ $req->created_at->format('M d, Y') }}</td>
                                                        <td class="px-5 py-3">
                                                            @if($req->status === 'pending')
                                                                <form method="POST" action="{{ route('admin.documents.update', $req->id) }}"
                                                                    enctype="multipart/form-data" class="space-y-2 min-w-[200px]">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <input type="text" name="admin_note" placeholder="Note (optional)"
                                                                        class="w-full border rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-green-500">
                                                                    <div>
                                                                        <label class="text-xs text-gray-500 font-medium">Attach file
                                                                            (optional)</label>
                                                                        <input type="file" name="document_file"
                                                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                                            class="w-full text-xs mt-1 border rounded px-2 py-1">
                                                                    </div>
                                                                    <div class="flex gap-1">
                                                                        <button type="submit" name="status" value="approved"
                                                                            class="flex-1 bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600 font-semibold">
                                                                            Approve
                                                                        </button>
                                                                        <button type="submit" name="status" value="rejected"
                                                                            class="flex-1 bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600 font-semibold">
                                                                            Reject
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            @else
                                                                <p class="text-xs text-gray-400 italic mb-1">{{ $req->admin_note ?? 'No note' }}</p>
                                                                @if($req->file_path)
                                                                    <a href="{{ route('admin.documents.download', $req->id) }}"
                                                                        class="inline-flex items-center gap-1 text-xs text-green-600 hover:underline font-semibold">
                                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                                        </svg>
                                                                        View File
                                                                    </a>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-8 text-center text-gray-500">No document requests found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</x-layout>