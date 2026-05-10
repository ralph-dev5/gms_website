<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReportRequest;
use App\Models\Report;
use Cloudinary\Cloudinary;

class ReportController extends Controller
{
    /**
     * Show the create report form.
     */
    public function create()
    {
        if ($this->hasOpenReport()) {
            return redirect()->route('dashboard')
                ->with('error', 'You can only file a new report after your previous one is completed.');
        }

        return view('reports.create');
    }

    /**
     * Redirect to dashboard (reports have no index page).
     */
    public function index()
    {
        return redirect()->route('dashboard');
    }

    private function hasOpenReport(): bool
    {
        return Report::where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'in_progress'])
            ->exists();
    }

    /**
     * Store a new report.
     * Uploads image to Cloudinary if provided.
     */
    public function store(StoreReportRequest $request)
    {
        if ($this->hasOpenReport()) {
            return back()
                ->withInput()
                ->with('error', 'You can only file a new report after your previous one is completed.');
        }

        $data = $request->validated();

        $data['user_id'] = auth()->id();
        $data['status'] = 'pending';
        $data['title'] = $data['street'];
        $data['image'] = null;
        unset($data['street']);

        if ($request->hasFile('image')) {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
            ]);

            $result = $cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'reports']
            );

            $data['image'] = $result['secure_url'];
        }

        Report::create($data);

        return redirect()->route('dashboard')->with('success', 'Report submitted successfully!');
    }

    /**
     * Soft-delete the user's own report.
     * Only allowed if the report status is 'completed'.
     * Deleted reports go to the user's deleted reports page only.
     * Admin dashboard is NOT affected.
     */
    public function destroy($id)
    {
        $report = Report::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($report->status !== 'completed') {
            return back()->with('error', 'You can only delete completed reports.');
        }

        $report->delete(); // soft delete — hidden from active list, visible in user's deleted reports

        return redirect()->route('reports.deleted')->with('success', 'Report deleted successfully.');
    }

    /**
     * Show the current user's soft-deleted reports only.
     * Admin deleted reports are separate and not shown here.
     */
    public function deleted()
    {
        $reports = Report::onlyTrashed()
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('reports.deleted', compact('reports'));
    }
}
