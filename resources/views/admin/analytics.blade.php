<x-layout>
<div class="min-h-screen flex bg-gray-100">

    @include('admin.partials.sidebar')

    <main class="flex-1 p-4 md:p-10 min-w-0 pt-16 md:pt-10">
        <h1 class="text-2xl md:text-3xl font-bold mb-1">System Analytics</h1>
        <p class="text-gray-600 mb-8">Overview of platform activity.</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
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
    </main>

</div>
</x-layout>