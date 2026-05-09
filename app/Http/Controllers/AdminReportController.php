<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function dashboard()
    {
        $reports = Report::with('user')->latest()->get();
        return view('admin.dashboard', compact('reports'));
    }

    public function users(Request $request)
    {
        $search = $request->search;
        $users = User::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })->latest()->paginate(10);
        return view('admin.users', compact('users', 'search'));
    }

    public function analytics(Request $request)
    {
        $totalUsers      = User::count();
        $totalReports    = Report::count();
        $pendingReports  = Report::where('status', 'pending')->count();
        $resolvedReports = Report::where('status', 'resolved')->count();

        $range = $request->get('range', 'month');

        $startDate = match($range) {
            'today'  => now()->startOfDay(),
            'week'   => now()->startOfWeek(),
            'month'  => now()->startOfMonth(),
            'custom' => $request->custom_month
                            ? \Carbon\Carbon::parse($request->custom_month . '-01')->startOfMonth()
                            : now()->startOfMonth(),
            default  => now()->startOfMonth(),
        };

        $endDate = ($range === 'custom' && $request->custom_month)
            ? \Carbon\Carbon::parse($request->custom_month . '-01')->endOfMonth()
            : now()->endOfDay();

        // withTrashed() includes soft-deleted reports so rankings are permanent
        $streetRankings = Report::withTrashed()
            ->selectRaw('title as street, COUNT(*) as total')
            ->whereNotNull('title')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('title')
            ->orderByDesc('total')
            ->get();

        return view('admin.analytics', compact(
            'totalUsers',
            'totalReports',
            'pendingReports',
            'resolvedReports',
            'streetRankings',
            'range',
            'startDate',
            'endDate'
        ));
    }

    public function deletedReports()
    {
        $reports = Report::onlyTrashed()->with('user')->latest()->get();
        return view('admin.deleted-reports', compact('reports'));
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function userReports($id)
    {
        $user    = User::findOrFail($id);
        $reports = Report::where('user_id', $id)->latest()->get();
        return view('admin.user-reports', compact('user', 'reports'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return back()->with('error', 'Admin users cannot be deleted.');
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function updateReportStatus(Request $request, $id)
    {
        $request->validate(['status' => ['required', 'in:pending,in_progress,completed']]);
        $report = Report::findOrFail($id);
        $report->update(['status' => $request->status]);
        return back()->with('success', 'Report status updated.');
    }

    // Soft-deletes the report — rankings are unaffected because withTrashed() counts it
    public function destroyReport($id)
    {
        $report = Report::where('id', $id)->where('status', 'completed')->firstOrFail();
        $report->delete();
        return back()->with('success', 'Report deleted.');
    }
}