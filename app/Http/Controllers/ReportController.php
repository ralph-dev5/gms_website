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
            $data['image'] = $request->file('image')->store('reports', 'public');
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
