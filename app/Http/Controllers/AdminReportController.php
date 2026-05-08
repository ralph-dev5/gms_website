<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $reports = Report::with('user')
            ->latest()
            ->get();

        return view('admin.dashboard', compact('reports'));
    }

    /**
     * Show all registered users with search
     */
    public function users(Request $request)
    {
        $search = $request->search;

        $users = User::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10);

        return view('admin.users', compact('users', 'search'));
    }

    /**
     * Analytics page
     */
    public function analytics(Request $request)
    {
        $totalUsers      = User::count();
        $totalReports    = Report::count();
        $pendingReports  = Report::where('status', 'pending')->count();
        $resolvedReports = Report::where('status', 'resolved')->count();

        // Date range filter
        $range = $request->get('range', 'month');

        $startDate = match($range) {
            'today'  => now()->startOfDay(),
            'week'   => now()->startOfWeek(),
            'month'  => now()->startOfMonth(),
            'custom' => $request->start_date
                            ? \Carbon\Carbon::parse($request->start_date)->startOfDay()
                            : now()->startOfMonth(),
            default  => now()->startOfMonth(),
        };

        $endDate = $range === 'custom' && $request->end_date
            ? \Carbon\Carbon::parse($request->end_date)->endOfDay()
            : now()->endOfDay();

        $monthlyStreetReports = Report::selectRaw('
                MONTH(created_at) as month,
                YEAR(created_at) as year,
                title as street,
                COUNT(*) as total
            ')
            ->whereNotNull('title')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('year', 'month', 'title')
            ->orderBy('year')
            ->orderBy('month')
            ->orderBy('total')
            ->get()
            ->groupBy(fn($r) => $r->year . '-' . $r->month);

        return view('admin.analytics', compact(
            'totalUsers',
            'totalReports',
            'pendingReports',
            'resolvedReports',
            'monthlyStreetReports',
            'range',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Deleted Reports
     */
    public function deletedReports()
    {
        $reports = Report::onlyTrashed()
            ->with('user')
            ->latest()
            ->get();

        return view('admin.deleted-reports', compact('reports'));
    }

    /**
     * Admin Settings
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * View a user's report history
     */
    public function userReports($id)
    {
        $user    = User::findOrFail($id);
        $reports = Report::where('user_id', $id)->latest()->get();

        return view('admin.user-reports', compact('user', 'reports'));
    }

    /**
     * Delete User
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->with('error', 'Admin users cannot be deleted.');
        }

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    /**
     * Update Report Status
     */
    public function updateReportStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed'],
        ]);

        $report = Report::findOrFail($id);
        $report->update(['status' => $request->status]);

        return back()->with('success', 'Report status updated.');
    }

    public function destroyReport($id)
    {
        $report = Report::where('id', $id)
            ->where('status', 'completed')
            ->firstOrFail();

        $report->delete();

        return back()->with('success', 'Report deleted.');
    }
}