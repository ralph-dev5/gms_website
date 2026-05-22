<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use Illuminate\Http\Request;
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

        return $this->streamFile($docRequest->file_path, $docRequest->document_type);
    }

    private function streamFile(string $url, string $name): StreamedResponse
    {
        $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));

        // Fix old URLs that were stored as /image/upload/ for non-image files
        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        if (! $isImage) {
            $url = str_replace('/image/upload/', '/raw/upload/', $url);
        }

        $context = stream_context_create([
            'http' => ['timeout' => 30, 'header' => 'User-Agent: Laravel/GMS'],
            'ssl' => ['verify_peer' => false],
        ]);

        $contents = @file_get_contents($url, false, $context);

        abort_if($contents === false, 404, 'File could not be retrieved from storage.');

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

        return response()->streamDownload(function () use ($contents) {
            echo $contents;
        }, $filename, ['Content-Type' => $mime]);
    }
}
