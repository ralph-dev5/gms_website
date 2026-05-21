@extends('layouts.app')

@section('content')
<div class="flex h-screen overflow-hidden">
    <!-- Include Sidebar -->
    @include('partials.user-sidebar')
    
    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden md:ml-64">
        
        <!-- Header Section -->
        <div class="bg-white shadow-sm p-6">
            <a href="{{ route('analytics') }}" class="text-green-600 hover:text-green-700 mb-4 inline-block">
                ← Back to Analytics
            </a>
            <h1 class="text-3xl font-bold text-gray-800">{{ $streetName }}</h1>
            <p class="text-gray-600 mt-2">Reports from {{ $startDate }} — {{ $endDate }}</p>
        </div>

        <!-- Reports Section - SCROLLABLE -->
        <div class="flex-1 overflow-hidden p-6">
            <div class="h-full flex flex-col bg-white rounded-lg shadow-sm">
                
                <!-- Green Header -->
                <div class="bg-green-500 text-white px-6 py-4 rounded-t-lg">
                    <h2 class="text-xl font-semibold">{{ $reports->count() }} Report(s)</h2>
                </div>

                <!-- Scrollable Table Container -->
                <div class="flex-1 overflow-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($reports as $report)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <!-- Image -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="h-16 w-16 bg-gray-200 rounded-lg overflow-hidden">
                                        @if($report->image)
                                            <img src="{{ asset('storage/' . $report->image) }}" 
                                                 alt="Report image" 
                                                 class="h-full w-full object-cover">
                                        @endif
                                    </div>
                                </td>
                                
                                <!-- User -->
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $report->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $report->user->email ?? $report->user->username }}</div>
                                    </div>
                                </td>
                                
                                <!-- Location -->
                                <td class="px-6 py-4">
                                    <a href="{{ route('reports.map', $report->id) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        View Map
                                    </a>
                                </td>
                                
                                <!-- Status -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($report->status === 'completed')
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Date -->
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $report->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection