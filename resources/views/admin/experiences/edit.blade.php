@extends('admin.layout')

@section('title', 'Edit Pengalaman')
@section('page-title', 'Edit Pengalaman')
@section('page-subtitle', 'Perbarui pengalaman yang ditampilkan di halaman utama dengan tag, deskripsi, dan status publikasi.')

@section('content')
    @if($errors->any())
        <div class="flash-message">{{ $errors->first() }}</div>
    @endif

    <form class="admin-form" method="POST" action="{{ route('admin.experiences.update', $experience) }}">
        @csrf
        @method('PUT')

        <label>Judul</label>
        <input type="text" name="title" value="{{ old('title', $experience->title) }}" required>

        <label>Organisasi</label>
        <input type="text" name="organization" value="{{ old('organization', $experience->organization) }}">

        <label>Tahun</label>
        <input type="text" name="year" value="{{ old('year', $experience->year) }}">

        <label>Deskripsi</label>
        <textarea name="description">{{ old('description', $experience->description) }}</textarea>

        <label>Tags (pisahkan dengan koma)</label>
        <input type="text" name="tags" value="{{ old('tags', is_array($experience->tags) ? implode(', ', $experience->tags) : '') }}" placeholder="Leadership, Team Coordination">

        <label>Posisi Tampilan</label>
        <input type="number" name="position" value="{{ old('position', $experience->position) }}">

        <label>
            <input type="checkbox" name="featured" {{ old('featured', $experience->featured) ? 'checked' : '' }}>
            Tampilkan sebagai unggulan
        </label>

        <label>
            <input type="checkbox" name="is_published" {{ old('is_published', $experience->is_published) ? 'checked' : '' }}>
            Publikasikan sekarang
        </label>

        <div class="form-actions">
            <button type="submit">Simpan Perubahan</button>
            <a href="{{ route('admin.experiences.index') }}">Batal</a>
        </div>
    </form>
@endsection
