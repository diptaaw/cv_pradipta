<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index()
    {
        $mediaList = Media::latest()->get();

        return view('admin.media.index', compact('mediaList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240',
        ]);

        if ($request->file('file')->isValid()) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/media', $filename, 'public');

            $media = Media::create([
                'file_path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);

            ActivityLog::log('Media uploaded', 'Uploaded media file: ' . $media->filename);

            return redirect()->route('admin.media.index')->with('success', 'Media "' . $media->filename . '" berhasil diunggah.');
        }

        return redirect()->route('admin.media.index')->withErrors(['file' => 'Gagal mengunggah file.']);
    }

    public function destroy(Media $media)
    {
        $filename = $media->filename;

        if (Storage::disk('public')->exists($media->file_path)) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        ActivityLog::log('Media deleted', 'Deleted media file: ' . $filename);

        return redirect()->route('admin.media.index')->with('success', 'Media "' . $filename . '" berhasil dihapus.');
    }
}
