@extends('admin.layout')

@section('title', 'Edit About Section')
@section('page-title', 'Edit About Section')
@section('page-subtitle', 'Perbarui headline, paragraf, dan ringkasan About agar konten halaman utama selaras dengan profil Anda.')

@section('content')
    @if(session('success'))
        <div class="flash-message">{{ session('success') }}</div>
    @endif

    <form class="admin-form" method="POST" action="{{ route('admin.about.update') }}">
        @csrf
        @method('PUT')

        <label>Headline</label>
        <input type="text" name="headline" value="{{ old('headline', $about->headline ?? '') }}">

        <label>Subheadline</label>
        <input type="text" name="subheadline" value="{{ old('subheadline', $about->subheadline ?? '') }}">

        <label>Ringkasan Singkat</label>
        <input type="text" name="short_intro" value="{{ old('short_intro', $about->short_intro ?? '') }}">

        <label>URL Image Profil (optional)</label>
        <input type="text" name="profile_image" value="{{ old('profile_image', $about->profile_image ?? '') }}">

        <label>Paragraf About (gunakan baris baru untuk setiap paragraf)</label>
        <textarea name="paragraphs">{{ old('paragraphs', isset($about) ? implode("\n", $about->paragraphs ?? []) : '') }}</textarea>

        <label>
            <input type="checkbox" name="is_published" {{ old('is_published', $about->is_published ?? false) ? 'checked' : '' }}>
            Publikasikan konten About
        </label>

        <div class="form-actions">
            <button type="submit">Simpan About</button>
            <a href="{{ route('admin.dashboard') }}">Kembali</a>
        </div>
    </form>
@endsection
