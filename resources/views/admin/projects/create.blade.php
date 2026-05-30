@extends('admin.layout')

@section('title', 'Tambah Project')
@section('page-title', 'Tambah Project')
@section('page-subtitle', 'Tambahkan project baru dengan thumbnail, teknologi, dan link yang dapat ditampilkan di halaman utama.')

@section('content')
    @if($errors->any())
        <div class="flash-message">{{ $errors->first() }}</div>
    @endif

    <form class="admin-form" method="POST" action="{{ route('admin.projects.store') }}">
        @csrf

        <label>Judul Project</label>
        <input type="text" name="title" value="{{ old('title') }}" required>

        <label>URL Thumbnail</label>
        <input type="text" name="thumbnail" value="{{ old('thumbnail') }}" placeholder="images/projects/example.png">

        <label>Deskripsi</label>
        <textarea name="description">{{ old('description') }}</textarea>

        <label>Technologies (pisahkan dengan koma)</label>
        <input type="text" name="technologies" value="{{ old('technologies') }}" placeholder="Vue, Tailwind, Laravel">

        <label>Project Link</label>
        <input type="text" name="project_link" value="{{ old('project_link') }}">

        <label>GitHub Link</label>
        <input type="text" name="github_link" value="{{ old('github_link') }}">

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
            <button type="submit">Simpan Project</button>
            <a href="{{ route('admin.projects.index') }}">Batal</a>
        </div>
    </form>
@endsection
