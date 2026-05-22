<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    /**
     * Proxy-download the attached file through Laravel so Cloudinary auth is bypassed.
     */
    public function download($id): StreamedResponse
    {
        $docRequest = DocumentRequest::where('user_id', auth()->id())
            ->findOrFail($id);

        abort_if(! $docRequest->file_path, 404, 'No file attached.');

        return $this->streamFile(
            $docRequest->file_path,
            $docRequest->document_type,
            $docRequest->file_extension
        );
    }

    private function streamFile(string $url, string $name, ?string $ext): StreamedResponse
    {
        // Use stored extension; fall back to parsing URL
        if (! $ext) {
            $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
        }

        $ext = strtolower($ext);

        // Fix old URLs stored as /image/upload/ for non-image files
        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        if (! $isImage) {
            $url = str_replace('/image/upload/', '/raw/upload/', $url);
        }

        $response = Http::timeout(30)->get($url);

        abort_if(! $response->successful(), 404, 'File could not be retrieved from storage.');

        $mimeMap = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
        ];

        $mime = $mimeMap[$ext] ?? 'application/octet-stream';
        $filename = Str::slug($name).'.'.$ext;

        return response()->streamDownload(function () use ($response) {
            echo $response->body();
        }, $filename, ['Content-Type' => $mime]);
    }
}
