<?php

namespace App\Http\Controllers;

use App\Models\Report;

class DashboardController extends Controller
{
    public function index()
    {
        $user   = auth()->user();
        $userId = $user->id;

        // Counts only active (non-deleted) reports for the user
        $pendingCount = Report::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        $inProgressCount = Report::where('user_id', $userId)
            ->where('status', 'in_progress')
            ->count();

        $completedCount = Report::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        // Only show active (non-deleted) reports on user dashboard
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