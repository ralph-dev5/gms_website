<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    /**
     * Update the status of a user report.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, $id)
    {
        // Find the report by ID or fail
        $report = Report::findOrFail($id);

        // Validate the status input
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        // Update the status
        $report->status = $validated['status'];
        $report->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Report status updated successfully.');
    }
}
