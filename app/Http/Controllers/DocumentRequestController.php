<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use Illuminate\Http\Request;

class DocumentRequestController extends Controller
{
    public function index()
    {
        $requests = DocumentRequest::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('documents.index', compact('requests'));
    }

    public function create()
    {
        $documentTypes = DocumentRequest::documentTypes();

        return view('documents.create', compact('documentTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_type' => ['required', 'string', 'in:'.implode(',', DocumentRequest::documentTypes())],
            'purpose' => ['nullable', 'string', 'max:500'],
        ]);

        DocumentRequest::create([
            'user_id' => auth()->id(),
            'document_type' => $request->document_type,
            'purpose' => $request->purpose,
            'status' => 'pending',
        ]);

        return redirect()->route('documents.index')
            ->with('success', 'Document request submitted successfully.');
    }
}
