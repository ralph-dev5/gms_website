<x-layout>
<div class="min-h-screen flex bg-gray-100">

    @include('partials.user-sidebar')

    <main class="flex-1 p-4 md:p-10 min-w-0 pt-16 md:pt-10">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">My Document Requests</h1>
            <a href="{{ route('documents.create') }}"
               class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 transition">
                + New Request
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left min-w-[500px]">
                    <thead class="bg-gray-100 border-b">
                        <tr>
                            <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Document Type</th>
                            <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Purpose</th>
                            <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                            <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Admin Note</th>
                            <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">File</th>
                            <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-5 py-3 font-semibold text-gray-800 text-sm">{{ $req->document_type }}</td>
                            <td class="px-5 py-3 text-gray-600 text-sm">{{ $req->purpose ?? '—' }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-1 text-xs rounded-full font-semibold whitespace-nowrap
                                    {{ $req->status === 'pending' ? 'bg-yellow-100 text-yellow-700'
                                        : ($req->status === 'approved' ? 'bg-green-100 text-green-700'
                                        : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-500 text-sm">{{ $req->admin_note ?? '—' }}</td>
                            <td class="px-5 py-3">
                                @if($req->file_path)
                                    @php
                                        $fileUrl = $req->file_path;
                                        $ext = strtolower(pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
                                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                        if (!$isImage) {
                                            $fileUrl = str_replace('/image/upload/', '/raw/upload/', $fileUrl);
                                        }
                                    @endphp
                                    <a href="{{ $fileUrl }}" target="_blank"
                                       class="inline-flex items-center gap-1 text-sm text-green-600 hover:underline font-semibold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download
                                    </a>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-400 text-xs whitespace-nowrap">{{ $req->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-gray-500">
                                No document requests yet.
                                <a href="{{ route('documents.create') }}" class="text-green-600 hover:underline ml-1">Request one now.</a>
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
