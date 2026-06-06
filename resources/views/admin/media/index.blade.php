@extends('admin.layout')

@section('title', 'Media Library')
@section('page-title', 'Media Library')
@section('page-subtitle', 'Unggah dan kelola media gambar yang dapat digunakan kembali pada halaman proyek tanpa duplikasi data.')

@section('content')
    @if(session('success'))
        <div class="flash-message">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="flash-message" style="background: rgba(255,59,48,0.16); border-color: rgba(255,59,48,0.22);">{{ $errors->first() }}</div>
    @endif

    <!-- Upload Form -->
    <div class="admin-card" style="margin-bottom: 24px;">
        <h2>Unggah Media Baru</h2>
        <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="admin-form" style="max-width:100%; border:none; background:none; padding:0; box-shadow:none;">
            @csrf
            <div style="display:flex; gap:16px; align-items:center; flex-wrap:wrap;">
                <input type="file" name="file" required style="margin-bottom:0; width:auto; max-width:100%;">
                <button type="submit" style="padding: 12px 24px; border-radius: 14px; border: none; background: linear-gradient(90deg, #7b61ff, #b637ff); color: white; cursor: pointer;">Unggah</button>
            </div>
            <small style="display:block; margin-top:8px; color:rgba(255,255,255,0.5);">Format yang didukung: JPEG, PNG, JPG, GIF, SVG, WEBP (Maksimal 10MB)</small>
        </form>
    </div>

    @if($mediaList->isEmpty())
        <div class="admin-card">
            <p>Belum ada media di pustaka. Silakan unggah gambar di atas.</p>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
            @foreach($mediaList as $media)
                <div class="admin-card" style="display:flex; flex-direction:column; justify-content:space-between; position:relative;">
                    <div style="width:100%; height:140px; border-radius:10px; overflow:hidden; background:rgba(0,0,0,0.2); display:flex; align-items:center; justify-content:center; margin-bottom:12px;">
                        <img src="{{ $media->url }}" alt="{{ $media->filename }}" style="max-width:100%; max-height:100%; object-fit:contain;">
                    </div>
                    <div>
                        <h3 style="font-size:0.9rem; word-break:break-all; margin-bottom:4px;" title="{{ $media->filename }}">{{ Str::limit($media->filename, 20) }}</h3>
                        <small style="display:block; color:rgba(255,255,255,0.5); margin-bottom:8px;">
                            {{ round($media->size / 1024, 2) }} KB | {{ $media->mime_type }}
                        </small>
                        <input type="text" readonly value="{{ 'storage/' . $media->file_path }}" style="width:100%; font-size:0.75rem; padding:6px; border-radius:6px; border:1px solid rgba(255,255,255,0.1); background:rgba(0,0,0,0.3); color:rgba(255,255,255,0.7); margin-bottom:10px;" onclick="this.select(); document.execCommand('copy'); alert('Path disalin!');">
                    </div>
                    <form action="{{ route('admin.media.destroy', $media) }}" method="POST" style="margin-top:auto;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="width:100%; background:rgba(255,59,48,0.2); color:#ff453a; border:1px solid rgba(255,59,48,0.3); padding:8px; border-radius:8px; cursor:pointer;" onclick="return confirm('Hapus media ini secara permanen?')">Hapus</button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
@endsection
