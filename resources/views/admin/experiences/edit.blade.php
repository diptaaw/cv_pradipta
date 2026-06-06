@extends('admin.layout')

@section('title', 'Edit Pengalaman')
@section('page-title', 'Edit Pengalaman')
@section('page-subtitle', 'Perbarui data pengalaman kerja, organisasi, beserta rentang waktu dan tag terhubung.')

@section('content')
    @if($errors->any())
        <div class="flash-message" style="background: rgba(255,59,48,0.16); border-color: rgba(255,59,48,0.22);">{{ $errors->first() }}</div>
    @endif

    <form class="admin-form" method="POST" action="{{ route('admin.experiences.update', $experience) }}">
        @csrf
        @method('PUT')

        <label>Nama Organisasi / Perusahaan</label>
        <input type="text" name="organization" value="{{ old('organization', $experience->organization) }}" required>

        <label>Posisi / Jabatan</label>
        <input type="text" name="title" value="{{ old('title', $experience->title) }}" required>

        <label>Tahun Tampilan</label>
        <input type="text" name="year" value="{{ old('year', $experience->year) }}" placeholder="Contoh: 2025 — PRESENT" required>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
            <div>
                <label>Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ old('start_date', $experience->start_date ? $experience->start_date->format('Y-m-d') : '') }}">
            </div>
            <div>
                <label>Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ old('end_date', $experience->end_date ? $experience->end_date->format('Y-m-d') : '') }}">
            </div>
        </div>

        <label>Deskripsi Peran</label>
        <textarea name="description">{{ old('description', $experience->description) }}</textarea>

        <label>Tags Terpusat (Pilih yang sudah ada)</label>
        <div style="max-height: 150px; overflow-y: auto; border: 1px solid rgba(255,255,255,0.1); padding: 12px; border-radius: 10px; background: rgba(0,0,0,0.2); margin-bottom: 12px;">
            @forelse($tags as $tag)
                @php $checked = in_array($tag->id, $experienceTagIds) ? 'checked' : ''; @endphp
                <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; cursor: pointer; color: rgba(255,255,255,0.9);">
                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ $checked }} style="width: auto; margin-bottom: 0;">
                    {{ $tag->name }}
                </label>
            @empty
                <p style="color: rgba(255,255,255,0.5); font-size: 0.85rem; margin: 0;">Belum ada tag. Tulis di bawah untuk membuat baru.</p>
            @endforelse
        </div>

        <label>Buat Tag Baru (Pisahkan dengan koma jika lebih dari satu)</label>
        <input type="text" name="new_tags" value="{{ old('new_tags') }}" placeholder="Leadership, Unity, C#">

        <label>Posisi Tampilan (Urutan)</label>
        <input type="number" name="position" value="{{ old('position', $experience->position) }}">

        <div style="display: flex; gap: 20px; margin-top: 24px; margin-bottom: 24px; flex-wrap: wrap;">
            <label style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 0; cursor: pointer;">
                <input type="checkbox" name="featured" value="1" {{ old('featured', $experience->featured) ? 'checked' : '' }} style="width: auto; margin-bottom: 0;">
                Tampilkan sebagai unggulan (Maks 5)
            </label>

            <label style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 0; cursor: pointer;">
                <input type="checkbox" name="is_published" value="1" {{ old('is_published', $experience->is_published) ? 'checked' : '' }} style="width: auto; margin-bottom: 0;">
                Publikasikan sekarang
            </label>
        </div>

        <div class="form-actions">
            <button type="submit">Simpan Perubahan</button>
            <a href="{{ route('admin.experiences.index') }}">Batal</a>
        </div>
    </form>
@endsection
