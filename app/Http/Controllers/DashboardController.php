<?php

namespace App\Http\Controllers;

use App\Models\Report;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userId = $user->id;

        // Counts by status
        $pendingCount = Report::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        $inProgressCount = Report::where('user_id', $userId)
            ->where('status', 'in_progress')
            ->count();

        $completedCount = Report::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        // Get all reports for the logged-in user
        $reports = Report::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact(
            'user',
            'pendingCount',
            'inProgressCount',
            'completedCount',
            'reports'
        ));
    }
}
