@extends('admin.layout')

@section('title', 'Manage Resume PDF')
@section('page-title', 'Resume PDF')

@section('breadcrumb')
    <span>&rarr;</span> <span class="active">Resume PDF</span>
@endsection

@section('content')
    @if(session('success'))
        <div class="flash-message" style="margin-bottom: 24px; padding: 12px 16px; background: rgba(46,204,113,0.15); border: 1px solid rgba(46,204,113,0.25); color: #2ecc71; border-radius: 10px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="flash-message" style="margin-bottom: 24px; padding: 12px 16px; background: rgba(255,59,48,0.15); border: 1px solid rgba(255,59,48,0.25); color: #ff453a; border-radius: 10px; font-weight: 600;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="cms-form-grid" style="grid-template-columns: 1fr 1.2fr;">
        <!-- Left Panel: Uploader & PDF Preview -->
        <div class="cms-card-section">
            <!-- Upload Card -->
            <div class="admin-card">
                <h2 style="font-size: 1.15rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    📤 Upload New Resume (PDF)
                </h2>

                <form action="{{ route('admin.resumes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="cms-form-group" style="margin-bottom: 16px;">
                        <label for="title">Resume Version Title</label>
                        <input class="cms-input" id="title" type="text" name="title" placeholder="e.g. Resume June 2026" required>
                    </div>

                    <div class="cms-form-group" style="margin-bottom: 16px;">
                        <label for="file">Choose PDF File</label>
                        <input class="cms-input" id="file" type="file" name="file" accept="application/pdf" required style="padding: 10px !important;">
                        <span style="font-size: 0.7rem; color: rgba(255,255,255,0.4)">Format: PDF only. Max 10MB.</span>
                    </div>

                    <div class="cms-form-group" style="margin-bottom: 20px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="is_published" value="1" style="width: 18px; height: 18px; accent-color: #7b61ff; cursor: pointer;">
                            <span style="font-size: 0.85rem; font-weight: 600; color: white;">Publish immediately (sets old resume to draft)</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-primary-cms" style="width: 100%;">
                        🚀 Upload PDF File
                    </button>
                </form>
            </div>

            <!-- Active Preview Card -->
            <div class="admin-card" style="margin-top: 24px;">
                <h2 style="font-size: 1.15rem; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    👁️ Active Resume Preview
                </h2>
                @if($activeResume)
                    <div style="margin-bottom: 12px; font-size: 0.82rem; color: rgba(255,255,255,0.6);">
                        Showing active PDF: <strong style="color: white;">{{ $activeResume->title }}</strong>
                    </div>
                    <div style="border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2); height: 420px;">
                        <object data="{{ Storage::disk(config('filesystems.default'))->url($activeResume->file_path) }}" type="application/pdf" width="100%" height="100%">
                            <div style="padding: 40px; text-align: center;">
                                <p style="margin-bottom: 16px;">This browser does not support inline PDFs.</p>
                                <a href="{{ Storage::disk(config('filesystems.default'))->url($activeResume->file_path) }}" target="_blank" class="action-btn">Open PDF in New Tab</a>
                            </div>
                        </object>
                    </div>
                @else
                    <div style="padding: 40px; text-align: center; border: 1px dashed rgba(255,255,255,0.15); border-radius: 12px; color: rgba(255,255,255,0.4);">
                        No active resume published currently.
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Panel: History List -->
        <div class="admin-card">
            <h2 style="font-size: 1.15rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                📜 Upload History & Versions
            </h2>

            @if($resumes->isEmpty())
                <p style="color: rgba(255,255,255,0.4); text-align: center; padding: 40px 0;">
                    No resumes uploaded yet. Use the left panel to upload.
                </p>
            @else
                <div class="cms-table-wrapper">
                    <table class="cms-table">
                        <thead>
                            <tr>
                                <th>Version Title</th>
                                <th>File Size</th>
                                <th>Uploaded At</th>
                                <th>Status</th>
                                <th style="text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resumes as $resume)
                                <tr>
                                    <td>
                                        <a href="{{ Storage::disk(config('filesystems.default'))->url($resume->file_path) }}" target="_blank" style="color: #c4b5fd; font-weight: 700; text-decoration: none;" title="Open PDF in new tab">
                                            {{ $resume->title }} ↗
                                        </a>
                                    </td>
                                    <td>
                                        <span style="font-family: monospace; font-size: 0.82rem; color: rgba(255,255,255,0.6);">
                                            {{ $resume->file_size }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="font-size: 0.82rem; color: rgba(255,255,255,0.5)">
                                            {{ $resume->created_at->format('M d, Y H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($resume->is_published)
                                            <span class="admin-badge" style="background: rgba(46,204,113,0.12); border: 1px solid rgba(46,204,113,0.18); color: #2ecc71; font-size: 0.72rem;">Active</span>
                                        @else
                                            <span class="admin-badge" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.4); font-size: 0.72rem;">Draft</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons" style="justify-content: flex-end;">
                                            @if(!$resume->is_published)
                                                <form method="POST" action="{{ route('admin.resumes.publish', $resume) }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="action-btn" style="background: rgba(123,97,255,0.15); border-color: rgba(123,97,255,0.25); color: #c4b5fd;">Publish</button>
                                                </form>
                                            @else
                                                <form method="POST" action="{{ route('admin.resumes.unpublish', $resume) }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="action-btn">Unpublish</button>
                                                </form>
                                            @endif

                                            <form method="POST" action="{{ route('admin.resumes.destroy', $resume) }}" onsubmit="return confirm('Are you sure you want to permanently delete this resume file?')" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
