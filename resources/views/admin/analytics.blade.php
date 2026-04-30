<x-layout>
<div class="min-h-screen flex bg-gray-100">

    @include('admin.partials.sidebar')

    <main class="flex-1 p-4 md:p-10 min-w-0 pt-16 md:pt-10">
        <h1 class="text-2xl md:text-3xl font-bold mb-1">System Analytics</h1>
        <p class="text-gray-600 mb-8">Overview of platform activity.</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-gray-500 text-sm mb-1">Total Users</h2>
                <p class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-gray-500 text-sm mb-1">Total Reports</h2>
                <p class="text-3xl font-bold text-gray-800">{{ $totalReports }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-gray-500 text-sm mb-1">Pending</h2>
                <p class="text-3xl font-bold text-yellow-500">{{ $pendingReports }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-gray-500 text-sm mb-1">Resolved</h2>
                <p class="text-3xl font-bold text-green-500">{{ $resolvedReports }}</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <p class="text-gray-500">
                Showing: {{ $startDate->format('M d, Y') }} — {{ $endDate->format('M d, Y') }}
            </p>

            @if($monthlyStreetReports->isEmpty())
                <p class="text-gray-500 mt-4">No street report data available.</p>
            @else
                @foreach($monthlyStreetReports as $key => $streets)
                    @php
                        $parts = explode('-', $key);
                        $year = $parts[0];
                        $month = $parts[1];
                    @endphp
                    <h3 class="font-bold mt-4">{{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</h3>
                    @foreach($streets as $street)
                        <p>{{ $street->street }} — {{ $street->total }}</p>
                    @endforeach
                @endforeach
            @endif
        </div>

    </main>
</div>
</x-layout>