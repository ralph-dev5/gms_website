<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Cloudinary\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function dashboard()
    {
        $reports = Report::with('user')->latest()->get();

        return view('admin.dashboard', compact('reports'));
    }

    public function users(Request $request)
    {
        $search = $request->search;
        $users = User::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })->latest()->paginate(10);

        return view('admin.users', compact('users', 'search'));
    }

    public function analytics(Request $request)
    {
        $totalUsers = User::count();
        $totalReports = Report::withTrashed()->count();
        $pendingReports = Report::withTrashed()->where('status', 'pending')->count();
        $resolvedReports = Report::withTrashed()->where('status', 'completed')->count();

        $range = $request->get('range', 'month');

        switch ($range) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'custom':
                $customMonth = $request->custom_month ?? now()->format('Y-m');
                $startDate = Carbon::parse($customMonth.'-01')->startOfMonth();
                $endDate = Carbon::parse($customMonth.'-01')->endOfMonth();
                break;
            case 'month':
            default:
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
        }

        $streetRankings = Report::withTrashed()
            ->selectRaw('title as street, COUNT(*) as total')
            ->whereNotNull('title')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('title')
            ->orderBy('total')
            ->get();

        return view('admin.analytics', compact(
            'totalUsers', 'totalReports', 'pendingReports',
            'resolvedReports', 'streetRankings', 'startDate', 'endDate', 'range'
        ));
    }

    public function deletedReports()
    {
        $reports = Report::onlyTrashed()->with('user')->latest()->get();

        return view('admin.deleted-reports', compact('reports'));
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('profile_photo')) {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
            ]);
            $result = $cloudinary->uploadApi()->upload(
                $request->file('profile_photo')->getRealPath(),
                ['folder' => 'profile-photos']
            );
            $user->profile_photo_path = $result['secure_url'];
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function streetReports(Request $request)
    {
        $street = $request->get('street');
        $range = $request->get('range', 'month');
        $customMonth = $request->get('custom_month', now()->format('Y-m'));

        switch ($range) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'custom':
                $startDate = Carbon::parse($customMonth.'-01')->startOfMonth();
                $endDate = Carbon::parse($customMonth.'-01')->endOfMonth();
                break;
            default:
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
        }

        $reports = Report::withTrashed()
            ->with('user')
            ->where('title', $street)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        return view('admin.street-reports', compact(
            'street', 'reports', 'range', 'startDate', 'endDate', 'customMonth'
        ));
    }

    public function userReports($id)
    {
        $user = User::findOrFail($id);
        $reports = Report::where('user_id', $id)->latest()->get();

        return view('admin.user-reports', compact('user', 'reports'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return back()->with('error', 'Admin users cannot be deleted.');
        }
        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    public function updateReportStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed'],
        ]);
        $report = Report::findOrFail($id);
        $report->update(['status' => $request->status]);

        return back()->with('success', 'Report status updated.');
    }

    public function documentRequests(Request $request)
    {
        $status = $request->get('status', 'all');
        $requests = DocumentRequest::with('user')
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->get();

        return view('admin.document-requests', compact('requests', 'status'));
    }

    public function updateDocumentRequest(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'admin_note' => ['nullable', 'string', 'max:500'],
            'document_file' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png'],
        ]);

        $docRequest = DocumentRequest::findOrFail($id);
        $filePath = $docRequest->file_path;
        $fileExtension = $docRequest->file_extension;

        if ($request->hasFile('document_file')) {
            $cloudinary = new Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key' => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
            ]);

            $file = $request->file('document_file');
            $fileExtension = strtolower($file->getClientOriginalExtension());
            $isImage = in_array($fileExtension, ['jpg', 'jpeg', 'png']);
            $resourceType = $isImage ? 'image' : 'raw';

            $result = $cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                ['folder' => 'documents', 'resource_type' => $resourceType]
            );

            $filePath = $result['secure_url'];
        }

        $docRequest->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
            'file_path' => $filePath,
            'file_extension' => $fileExtension,
        ]);

        return back()->with('success', 'Document request updated.');
    }

    public function destroyReport($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return back()->with('success', 'Report deleted.');
    }

    /**
     * Admin: proxy-download a document file through Laravel (bypasses Cloudinary auth).
     */
    public function downloadDocument($id): StreamedResponse
    {
        $docRequest = DocumentRequest::findOrFail($id);

        abort_if(! $docRequest->file_path, 404, 'No file attached.');

        $url = $docRequest->file_path;

        // Use stored extension; fall back to parsing URL
        $ext = $docRequest->file_extension
            ? strtolower($docRequest->file_extension)
            : strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));

        $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        if (! $isImage) {
            $url = str_replace('/image/upload/', '/raw/upload/', $url);
        }

        $response = Http::timeout(30)->get($url);
        abort_if(! $response->successful(), 404, 'File could not be retrieved.');

        $mimeMap = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
        ];

        $mime = $mimeMap[$ext] ?? 'application/octet-stream';
        $filename = Str::slug($docRequest->document_type).'.'.$ext;

        return response()->streamDownload(function () use ($response) {
            echo $response->body();
        }, $filename, ['Content-Type' => $mime]);
    }
}
