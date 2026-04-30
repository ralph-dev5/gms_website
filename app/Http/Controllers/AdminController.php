<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
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
        $totalUsers = User::count();
        $totalReports = Report::count();
        $pendingReports = Report::where('status', 'pending')->count();
        $resolvedReports = Report::where('status', 'resolved')->count();

        // Handle date range filter
        $range = $request->get('range', 'month');

        switch ($range) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'custom':
                $customMonth = $request->custom_month ?? now()->format('Y-m');
                $startDate = \Carbon\Carbon::parse($customMonth . '-01')->startOfMonth();
                $endDate = \Carbon\Carbon::parse($customMonth . '-01')->endOfMonth();
                break;
            case 'month':
            default:
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
        }

        // Street report rankings filtered by selected date range
        $monthlyStreetReports = Report::selectRaw('
                title as street,
                COUNT(*) as total
            ')
            ->whereNotNull('title')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('title')
            ->orderBy('total')
            ->get();

        return view('admin.analytics', compact(
            'totalUsers',
            'totalReports',
            'pendingReports',
            'resolvedReports',
            'monthlyStreetReports',
            'startDate',
            'endDate',
            'range'
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
        $user = User::findOrFail($id);
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