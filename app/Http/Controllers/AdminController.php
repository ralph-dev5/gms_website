<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Admin Dashboard — shows only active (non-deleted) reports.
     */
    public function dashboard()
    {
        $reports = Report::with('user')
            ->latest()
            ->get();

        return view('admin.dashboard', compact('reports'));
    }

    /**
     * Show all registered users with optional search.
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
     * Analytics page with date range filtering and street report rankings.
     */
    public function analytics(Request $request)
    {
        $totalUsers = User::count();
        $totalReports = Report::withTrashed()->count();
        $pendingReports = Report::withTrashed()->where('status', 'pending')->count();
        $resolvedReports = Report::withTrashed()->where('status', 'resolved')->count();

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
                $startDate = Carbon::parse($customMonth.'-01')->startOfMonth();
                $endDate = Carbon::parse($customMonth.'-01')->endOfMonth();
                break;
            case 'month':
            default:
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
        }

        $streetRankings = Report::withTrashed()
            ->selectRaw('title as street, COUNT(*) as total')
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
            'streetRankings',
            'startDate',
            'endDate',
            'range'
        ));
    }

    /**
     * Admin deleted reports — shows ALL soft-deleted reports from all users.
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
     * Admin settings page.
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Update admin profile — name, email, and profile photo.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('profile_photo')) {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
            ]);

            $result = $cloudinary->uploadApi()->upload(
                $request->file('profile_photo')->getRealPath(),
                ['folder' => 'profile-photos']
            );

            $user->profile_photo_path = $result['secure_url'];
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update admin password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * View a specific user's report history.
     */
    public function userReports($id)
    {
        $user = User::findOrFail($id);
        $reports = Report::where('user_id', $id)->latest()->get();

        return view('admin.user-reports', compact('user', 'reports'));
    }

    /**
     * Delete a user — admin users cannot be deleted.
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
     * Update a report's status.
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

    /**
     * Admin hard-deletes a completed report permanently.
     */
    public function destroyReport($id)
    {
        $report = Report::where('id', $id)
            ->where('status', 'completed')
            ->firstOrFail();

        $report->delete();

        return back()->with('success', 'Report deleted.');
    }
}
