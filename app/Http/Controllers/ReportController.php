<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Http\Requests\StoreReportRequest;
use Cloudinary\Cloudinary;

class ReportController extends Controller
{
    public function create()
    {
        return view('reports.create');
    }

    public function index()
    {
        return redirect()->route('dashboard');
    }

    public function store(StoreReportRequest $request)
    {
        $data = $request->validated();

        // Preserve existing logic: Set user, status, and move street to title
        $data['user_id'] = auth()->id();
        $data['status'] = 'pending';
        $data['title'] = $data['street'];
        unset($data['street']);

        // Default image to null in case no file is uploaded
        $data['image'] = null;

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

            // CRITICAL FIX: Save the full HTTPS secure URL to the database
            $data['image'] = $result['secure_url'];
        }

        Report::create($data);

        return redirect()->route('dashboard')->with('success', 'Report submitted successfully!');
    }

    public function destroy($id)
    {
        $report = Report::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $report->delete();

        // CHANGE THIS LINE:
        // From: return redirect()->route('reports.deleted')->with('success', ...);
        // To:
        return redirect()->route('dashboard')->with('success', 'Report deleted successfully.');
    }

    public function deleted()
    {
        $reports = Report::onlyTrashed()
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('reports.deleted', compact('reports'));
    }
}