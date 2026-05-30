@extends('admin.layout')

@section('title', 'Tambah Pengalaman')
@section('page-title', 'Tambah Pengalaman')
@section('page-subtitle', 'Masukkan detail pengalaman baru, termasuk tags yang dapat tampil sebagai badge di halaman utama.')

@section('content')
    @if($errors->any())
        <div class="flash-message">{{ $errors->first() }}</div>
    @endif

    <form class="admin-form" method="POST" action="{{ route('admin.experiences.store') }}">
        @csrf

        <label>Judul</label>
        <input type="text" name="title" value="{{ old('title') }}" required>

        <label>Organisasi</label>
        <input type="text" name="organization" value="{{ old('organization') }}">

        <label>Tahun</label>
        <input type="text" name="year" value="{{ old('year') }}">

        <label>Deskripsi</label>
        <textarea name="description">{{ old('description') }}</textarea>

        <label>Tags (pisahkan dengan koma)</label>
        <input type="text" name="tags" value="{{ old('tags') }}" placeholder="Leadership, Team Coordination">

        <label>Posisi Tampilan</label>
        <input type="number" name="position" value="{{ old('position', 0) }}">

        <label>
            <input type="checkbox" name="featured" {{ old('featured') ? 'checked' : '' }}>
            Tampilkan sebagai unggulan
        </label>

        <label>
            <input type="checkbox" name="is_published" {{ old('is_published') ? 'checked' : '' }}>
            Publikasikan sekarang
        </label>

        <div class="form-actions">
            <button type="submit">Simpan Pengalaman</button>
            <a href="{{ route('admin.experiences.index') }}">Batal</a>
        </div>
    </form>
@endsection
