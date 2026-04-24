<?php

namespace App\Http\Controllers;

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

    public function store(\App\Http\Requests\StoreReportRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['status'] = 'pending';
        $data['title'] = $data['street'];
        unset($data['street']);

        if ($request->hasFile('image')) {
            $cloudinary = new \Cloudinary\Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
            ]);
            $result = $cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'reports']
            );
            $data['image'] = $result['secure_url'];
        }

        \App\Models\Report::create($data);

        return redirect()->route('dashboard')->with('success', 'Report submitted successfully!');
    }

    public function destroy($id)
    {
        $report = \App\Models\Report::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $report->delete();

        return redirect()->route('reports.deleted')->with('success', 'Report deleted successfully.');
    }

    public function deleted()
    {
        $reports = \App\Models\Report::onlyTrashed()
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('reports.deleted', compact('reports'));
    }
}