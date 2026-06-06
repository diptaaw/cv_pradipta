<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResumeFile;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResumeFileController extends Controller
{
    public function index()
    {
        $resumes = ResumeFile::latest()->get();

        return view('admin.resumes.index', compact('resumes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|mimes:pdf|max:10240',
        ]);

        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/resumes', $filename, 'public');

            $publishImmediately = $request->boolean('is_published');

            $resume = ResumeFile::create([
                'title' => $request->input('title'),
                'file_path' => $path,
                'is_published' => $publishImmediately,
            ]);

            ActivityLog::log('Resume uploaded', 'Uploaded resume: ' . $resume->title . ($publishImmediately ? ' (published)' : ''));

            return redirect()->route('admin.resumes.index')->with('success', 'Resume "' . $resume->title . '" berhasil diunggah.');
        }

        return redirect()->route('admin.resumes.index')->withErrors(['file' => 'Gagal mengunggah file.']);
    }

    public function publish(ResumeFile $resume)
    {
        $resume->update([
            'is_published' => true,
        ]);

        ActivityLog::log('Resume published', 'Published resume: ' . $resume->title);

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

        if (Storage::disk('public')->exists($resume->file_path)) {
            Storage::disk('public')->delete($resume->file_path);
        }

        $resume->delete();

        ActivityLog::log('Resume deleted', 'Deleted resume file: ' . $title);

        return redirect()->route('admin.resumes.index')->with('success', 'Resume "' . $title . '" berhasil dihapus.');
    }
}
