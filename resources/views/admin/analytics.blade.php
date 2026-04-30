<x-layout>
<div class="min-h-screen flex bg-gray-100">

    @include('admin.partials.sidebar')

    <main class="flex-1 p-4 md:p-10 min-w-0 pt-16 md:pt-10">
        <h1 class="text-2xl md:text-3xl font-bold mb-1">System Analytics</h1>
        <p class="text-gray-600 mb-8">Overview of platform activity.</p>

        {{-- Summary Cards --}}
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
                <h2 class="text-gray-500 text-sm mb-1">Pending Reports</h2>
                <p class="text-3xl font-bold text-yellow-500">{{ $pendingReports }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-gray-500 text-sm mb-1">Resolved Reports</h2>
                <p class="text-3xl font-bold text-green-500">{{ $resolvedReports }}</p>
            </div>
        </div>

        {{-- Monthly Street Reports --}}
        <h2 class="text-xl font-bold mb-4 text-gray-800">Monthly Street Report Rankings</h2>
        <p class="text-gray-500 text-sm mb-6">Streets ranked from fewest to most reports per month ({{ now()->year }}).</p>

        @if($monthlyStreetReports->isEmpty())
            <div class="bg-white shadow rounded-lg p-6 text-center text-gray-500">
                No street report data available.
            </div>
        @else
            <div class="space-y-6">
                @foreach($monthlyStreetReports as $month => $streets)
                <div class="bg-white shadow rounded-lg overflow-hidden">

                    <div class="bg-green-600 px-6 py-3">
                        <h3 class="text-white font-semibold text-lg">
                            {{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ now()->year }}
                        </h3>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[400px]">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase w-12">#</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Street</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Reports</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Volume</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $max = $streets->max('total'); $min = $streets->min('total'); @endphp
                                @foreach($streets as $i => $street)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-3 text-gray-400 text-sm">{{ $i + 1 }}</td>
                                    <td class="px-6 py-3 font-medium text-gray-800">{{ $street->street }}</td>
                                    <td class="px-6 py-3 text-right">
                                        <span class="inline-block px-2 py-1 rounded text-sm font-bold
                                            {{ $street->total >= $max ? 'bg-red-100 text-red-600' : ($street->total == $min ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-600') }}">
                                            {{ $street->total }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 w-40">
                                        <div class="w-full bg-gray-100 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $street->total >= $max ? 'bg-red-500' : 'bg-green-500' }}"
                                                 style="width: {{ $max > 0 ? ($street->total / $max) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                @endforeach
            </div>
        @endif

    </main>

</div>
</x-layout>