<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResumeFile;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResumeFileController extends Controller
{
    public function index()
    {
        $resumes = ResumeFile::latest()->get();

        // Calculate file sizes dynamically
        foreach ($resumes as $resume) {
            try {
                if (Storage::disk(config('filesystems.default'))->exists($resume->file_path)) {
                    $bytes = Storage::disk(config('filesystems.default'))->size($resume->file_path);
                    $resume->file_size = number_format($bytes / 1024, 1) . ' KB';
                } else {
                    $resume->file_size = 'N/A';
                }
            } catch (\Exception $e) {
                $resume->file_size = 'N/A';
            }
        }

        $activeResume = ResumeFile::where('is_published', true)->latest()->first();

        return view('admin.resumes.index', compact('resumes', 'activeResume'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|mimes:pdf|max:10240', // Max 10MB
        ]);

        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('uploads/resumes', $filename, config('filesystems.default'));
            if ($publishImmediately) {
                // Unpublish all others
                ResumeFile::query()->update(['is_published' => false]);
            }

            $resume = ResumeFile::create([
                'title' => $request->input('title'),
                'file_path' => $path,
                'is_published' => $publishImmediately,
            ]);

            ActivityLog::log('Resume uploaded', 'Uploaded resume: ' . $resume->title . ($publishImmediately ? ' (published)' : ''));
            Notification::send('resume_uploaded', 'Resume uploaded', 'Uploaded resume: ' . $resume->title, 'resume', $resume->id);

            return redirect()->route('admin.resumes.index')->with('success', 'Resume "' . $resume->title . '" berhasil diunggah.');
        }

        return redirect()->route('admin.resumes.index')->withErrors(['file' => 'Gagal mengunggah file.']);
    }

    public function publish(ResumeFile $resume)
    {
        // Unpublish all other resumes
        ResumeFile::query()->update(['is_published' => false]);

        $resume->update([
            'is_published' => true,
        ]);

        ActivityLog::log('Resume published', 'Published resume: ' . $resume->title);
        Notification::send('resume_published', 'Resume published', 'Published resume: ' . $resume->title, 'resume', $resume->id);

        return redirect()->route('admin.resumes.index')->with('success', 'Resume "' . $resume->title . '" berhasil dipublikasikan.');
    }

    public function unpublish(ResumeFile $resume)
    {
        $resume->update([
            'is_published' => false,
        ]);

        ActivityLog::log('Resume unpublished', 'Unpublished resume: ' . $resume->title);

        return redirect()->route('admin.resumes.index')->with('success', 'Resume "' . $resume->title . '" dinonaktifkan.');
    }

    public function destroy(ResumeFile $resume)
    {
        $title = $resume->title;

        if (Storage::disk(config('filesystems.default'))->exists($resume->file_path)) {
            Storage::disk(config('filesystems.default'))->delete($resume->file_path);
        }

        $resume->delete();

        ActivityLog::log('Resume deleted', 'Deleted resume file: ' . $title);
        Notification::send('resume_deleted', 'Resume removed', 'Deleted resume: ' . $title);

        return redirect()->route('admin.resumes.index')->with('success', 'Resume "' . $title . '" berhasil dihapus.');
    }
}
