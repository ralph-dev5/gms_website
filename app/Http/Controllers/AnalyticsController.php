<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Show analytics dashboard with streets overview
     */
    public function index(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        // Get reports grouped by street
        $streetStats = Report::select('street', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('street')
            ->orderBy('total', 'desc')
            ->get();

        return view('analytics.index', compact('streetStats', 'startDate', 'endDate'));
    }

    /**
     * Show detailed reports for a specific street
     * THIS IS THE METHOD YOU NEED TO ADD/FIX
     */
    public function showStreet(Request $request, $street)
    {
        // Get date range
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        // Get all reports for this street with user information
        $reports = Report::where('street', $street)
            ->with('user') // Load user relationship
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // Format dates for display
        $formattedStartDate = \Carbon\Carbon::parse($startDate)->format('M d, Y');
        $formattedEndDate = \Carbon\Carbon::parse($endDate)->format('M d, Y');

        return view('analytics.street', [
            'streetName' => $street,
            'reports' => $reports,
            'startDate' => $formattedStartDate,
            'endDate' => $formattedEndDate,
        ]);
    }

    /**
     * Show map for a specific report
     */
    public function showReportMap($reportId)
    {
        $report = Report::with('user')->findOrFail($reportId);
        
        return view('reports.map', compact('report'));
    }
}