public function analytics(Request $request)
{
    $totalUsers = User::count();
    $totalReports = Report::count();
    $pendingReports = Report::where('status', 'pending')->count();
    $resolvedReports = Report::where('status', 'resolved')->count();

    // Date range filter
    $range = $request->get('range', 'month');
    $startDate = match($range) {
        'today' => now()->startOfDay(),
        'week'  => now()->startOfWeek(),
        'month' => now()->startOfMonth(),
        'custom' => $request->start_date ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : now()->startOfMonth(),
        default => now()->startOfMonth(),
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