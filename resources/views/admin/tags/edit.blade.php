@extends('admin.layout')

@section('title', 'Edit Tag')
@section('page-title', 'Edit Tag')
@section('page-subtitle', 'Perbarui nama tag. Perubahan nama akan otomatis berdampak pada semua proyek dan pengalaman yang menggunakan tag ini.')

@section('content')
    @if($errors->any())
        <div class="flash-message" style="background: rgba(255,59,48,0.16); border-color: rgba(255,59,48,0.22);">{{ $errors->first() }}</div>
    @endif

    <form class="admin-form" method="POST" action="{{ route('admin.tags.update', $tag) }}">
        @csrf
        @method('PUT')

        <label>Nama Tag</label>
        <input type="text" name="name" value="{{ old('name', $tag->name) }}" required>

        <div class="form-actions">
            <button type="submit">Simpan Perubahan</button>
            <a href="{{ route('admin.tags.index') }}">Batal</a>
        </div>
    </form>
@endsection
