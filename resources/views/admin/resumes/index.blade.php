@extends('admin.layout')

@section('title', 'Resume Management')
@section('page-title', 'Resume Management')
@section('page-subtitle', 'Unggah dan kelola file PDF Resume. Hanya diperbolehkan ada satu resume aktif/dipublikasikan dalam satu waktu.')

@section('content')
    @if(session('success'))
        <div class="flash-message">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="flash-message" style="background: rgba(255,59,48,0.16); border-color: rgba(255,59,48,0.22);">{{ $errors->first() }}</div>
    @endif

    <!-- Upload Form -->
    <div class="admin-card" style="margin-bottom: 24px;">
        <h2>Unggah Resume Baru (PDF)</h2>
        <form action="{{ route('admin.resumes.store') }}" method="POST" enctype="multipart/form-data" class="admin-form" style="max-width:100%;">
            @csrf
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label>Judul / Versi</label>
                    <input type="text" name="title" placeholder="Contoh: Resume June 2026" required style="margin-bottom:0;">
                </div>
                <div>
                    <label>Pilih File PDF</label>
                    <input type="file" name="file" accept="application/pdf" required style="margin-bottom:0;">
                </div>
            </div>
            
            <label style="display:inline-flex; align-items:center; gap:8px; margin-bottom:16px;">
                <input type="checkbox" name="is_published" value="1" style="width:auto; margin-bottom:0;">
                Publikasikan langsung (menonaktifkan resume lama)
            </label>

            <div class="form-actions">
                <button type="submit" style="padding: 10px 20px;">Unggah PDF</button>
            </div>
        </form>
    </div>

    <!-- History List -->
    <div class="admin-card">
        <h2>Riwayat Unggahan Resume</h2>
        @if($resumes->isEmpty())
            <p style="color:rgba(255,255,255,0.6);">Belum ada resume terunggah. Silakan unggah PDF di atas.</p>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Judul / Versi</th>
                        <th>Tanggal Unggah</th>
                        <th>Terakhir Diubah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resumes as $resume)
                        <tr>
                            <td>
                                <a href="{{ Storage::url($resume->file_path) }}" target="_blank" style="color:#7b61ff; font-weight:600; text-decoration:none;">
                                    {{ $resume->title }}
                                </a>
                            </td>
                            <td>{{ $resume->created_at->format('M d, Y H:i') }}</td>
                            <td>{{ $resume->updated_at->format('M d, Y H:i') }}</td>
                            <td>
                                @if($resume->is_published)
                                    <span class="admin-badge" style="background:rgba(46,204,113,0.18); border-color:rgba(46,204,113,0.24); color:#2ecc71;">Published (Active)</span>
                                @else
                                    <span class="admin-badge" style="background:rgba(255,255,255,0.06); color:rgba(255,255,255,0.5);">Draft</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:10px; align-items:center;">
                                    @if($resume->is_published)
                                        <form method="POST" action="{{ route('admin.resumes.unpublish', $resume) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" style="background:rgba(255,255,255,0.06); color:#fff; border:none; padding:8px 12px; border-radius:8px; cursor:pointer;">Unpublish</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.resumes.publish', $resume) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" style="background:linear-gradient(90deg, #7b61ff, #b637ff); color:#fff; border:none; padding:8px 12px; border-radius:8px; cursor:pointer;">Publish</button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.resumes.destroy', $resume) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="background:rgba(255,59,48,0.18); color:#ff453a; border:none; padding:8px 12px; border-radius:8px; cursor:pointer;" onclick="return confirm('Hapus file resume ini secara permanen?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
