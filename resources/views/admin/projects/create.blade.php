@extends('admin.layout')

@section('title', 'Tambah Project Baru')
@section('page-title', 'Tambah Project Baru')
@section('page-subtitle', 'Tambahkan detail proyek baru, pilih tag, dan sertakan gambar thumbnail serta galeri.')

@section('content')
    @if($errors->any())
        <div class="flash-message" style="background: rgba(255,59,48,0.16); border-color: rgba(255,59,48,0.22);">{{ $errors->first() }}</div>
    @endif

    <form class="admin-form" method="POST" action="{{ route('admin.projects.store') }}" style="max-width: 1000px;">
        @csrf

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
            <div>
                <label>Judul Proyek</label>
                <input type="text" name="title" value="{{ old('title') }}" required>

                <label>Kategori</label>
                <input type="text" name="category" value="{{ old('category') }}" placeholder="Contoh: Game Development, Photography">

                <label>Tahun Tampilan</label>
                <input type="text" name="year" value="{{ old('year') }}" placeholder="Contoh: 2026, 2025-2026">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div>
                        <label>Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}">
                    </div>
                    <div>
                        <label>Tanggal Selesai</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}">
                    </div>
                </div>

                <label>Project Link (Live/Demo)</label>
                <input type="url" name="project_link" value="{{ old('project_link') }}" placeholder="https://example.com">

                <label>GitHub Link</label>
                <input type="url" name="github_link" value="{{ old('github_link') }}" placeholder="https://github.com/username/project">

                <label>Posisi Tampilan (Urutan)</label>
                <input type="number" name="position" value="{{ old('position', 0) }}">
            </div>

            <div>
                <label>Deskripsi Proyek</label>
                <textarea name="description" style="min-height: 140px;">{{ old('description') }}</textarea>

                <label>Tags Terpusat (Pilih yang sudah ada)</label>
                <div style="max-height: 150px; overflow-y: auto; border: 1px solid rgba(255,255,255,0.1); padding: 12px; border-radius: 10px; background: rgba(0,0,0,0.2); margin-bottom: 12px;">
                    @forelse($tags as $tag)
                        <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; cursor: pointer; color: rgba(255,255,255,0.9);">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" style="width: auto; margin-bottom: 0;">
                            {{ $tag->name }}
                        </label>
                    @empty
                        <p style="color: rgba(255,255,255,0.5); font-size: 0.85rem; margin: 0;">Belum ada tag. Tulis di bawah untuk membuat baru.</p>
                    @endforelse
                </div>

                <label>Buat Tag Baru (Pisahkan dengan koma jika lebih dari satu)</label>
                <input type="text" name="new_tags" value="{{ old('new_tags') }}" placeholder="C#, Unity, WebGL">

                <div style="display: flex; gap: 20px; margin-top: 24px; margin-bottom: 24px; flex-wrap: wrap;">
                    <label style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 0; cursor: pointer;">
                        <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} style="width: auto; margin-bottom: 0;">
                        Tampilkan sebagai unggulan (Maks 5)
                    </label>

                    <label style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 0; cursor: pointer;">
                        <input type="checkbox" name="archived" value="1" {{ old('archived') ? 'checked' : '' }} style="width: auto; margin-bottom: 0;">
                        Arsipkan project
                    </label>

                    <label style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 0; cursor: pointer;">
                        <input type="checkbox" name="is_published" value="1" checked style="width: auto; margin-bottom: 0;">
                        Publikasikan sekarang
                    </label>
                </div>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 24px 0;">

        <!-- Thumbnail & Gallery Selection from Media Library -->
        <h2 style="font-size: 1.25rem; margin-bottom: 12px;">Pustaka Media (Pilih Gambar)</h2>
        <p style="color: rgba(255,255,255,0.6); margin-bottom: 16px; font-size: 0.88rem;">
            Pilih satu gambar untuk <strong>Thumbnail</strong>, dan beberapa gambar untuk <strong>Galeri Proyek</strong> (opsional). Anda dapat mengunggah gambar baru lewat menu Media di atas sebelum mengedit proyek ini.
        </p>

        @if($mediaList->isEmpty())
            <div class="admin-card" style="margin-bottom: 20px;">
                <p style="color: rgba(255,255,255,0.6);">Belum ada media terunggah. Silakan unggah gambar lewat <a href="{{ route('admin.media.index') }}" target="_blank" style="color: #7b61ff; font-weight: 600;">Media Library</a> terlebih dahulu.</p>
            </div>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px; max-height: 350px; overflow-y: auto; border: 1px solid rgba(255,255,255,0.1); padding: 16px; border-radius: 14px; background: rgba(0,0,0,0.15); margin-bottom: 24px;">
                @foreach($mediaList as $media)
                    @php $path = 'storage/' . $media->file_path; @endphp
                    <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); padding: 10px; border-radius: 10px; display: flex; flex-direction: column; justify-content: space-between; align-items: center;">
                        <img src="{{ asset($path) }}" alt="{{ $media->filename }}" style="width: 100%; height: 80px; object-fit: contain; border-radius: 6px; margin-bottom: 8px; background: rgba(0,0,0,0.2);">
                        <small style="display: block; font-size: 0.75rem; text-align: center; color: rgba(255,255,255,0.5); word-break: break-all; height: 32px; overflow: hidden; margin-bottom: 8px;">{{ $media->filename }}</small>
                        
                        <div style="display: flex; flex-direction: column; gap: 4px; width: 100%;">
                            <label style="display: flex; align-items: center; gap: 6px; font-size: 0.78rem; margin-bottom: 0; color: rgba(255,255,255,0.8); cursor: pointer;">
                                <input type="radio" name="thumbnail" value="{{ $path }}" style="width: auto; margin-bottom: 0;">
                                Thumbnail
                            </label>
                            <label style="display: flex; align-items: center; gap: 6px; font-size: 0.78rem; margin-bottom: 0; color: rgba(255,255,255,0.8); cursor: pointer;">
                                <input type="checkbox" name="gallery_images[]" value="{{ $path }}" style="width: auto; margin-bottom: 0;">
                                Galeri
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="form-actions">
            <button type="submit">Tambah Project</button>
            <a href="{{ route('admin.projects.index') }}">Batal</a>
        </div>
    </form>
@endsection
