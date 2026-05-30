@extends('admin.layout')

@section('title', 'Edit Project')
@section('page-title', 'Edit Project')
@section('page-subtitle', 'Perbarui detail project, teknologi, dan status publikasi agar info di halaman utama tetap akurat.')

@section('content')
    @if($errors->any())
        <div class="flash-message">{{ $errors->first() }}</div>
    @endif

    <form class="admin-form" method="POST" action="{{ route('admin.projects.update', $project) }}">
        @csrf
        @method('PUT')

        <label>Judul Project</label>
        <input type="text" name="title" value="{{ old('title', $project->title) }}" required>

        <label>URL Thumbnail</label>
        <input type="text" name="thumbnail" value="{{ old('thumbnail', $project->thumbnail) }}" placeholder="images/projects/example.png">

        <label>Deskripsi</label>
        <textarea name="description">{{ old('description', $project->description) }}</textarea>

        <label>Technologies (pisahkan dengan koma)</label>
        <input type="text" name="technologies" value="{{ old('technologies', is_array($project->technologies) ? implode(', ', $project->technologies) : '') }}" placeholder="Vue, Tailwind, Laravel">

        <label>Project Link</label>
        <input type="text" name="project_link" value="{{ old('project_link', $project->project_link) }}">

        <label>GitHub Link</label>
        <input type="text" name="github_link" value="{{ old('github_link', $project->github_link) }}">

        <label>Posisi Tampilan</label>
        <input type="number" name="position" value="{{ old('position', $project->position) }}">

        <label>
            <input type="checkbox" name="featured" {{ old('featured', $project->featured) ? 'checked' : '' }}>
            Tampilkan sebagai unggulan
        </label>

        <label>
            <input type="checkbox" name="archived" {{ old('archived', $project->archived) ? 'checked' : '' }}>
            Arsipkan project
        </label>

        <label>
            <input type="checkbox" name="is_published" {{ old('is_published', $project->is_published) ? 'checked' : '' }}>
            Publikasikan sekarang
        </label>

        <div class="form-actions">
            <button type="submit">Simpan Perubahan</button>
            <a href="{{ route('admin.projects.index') }}">Batal</a>
        </div>
    </form>
@endsection
