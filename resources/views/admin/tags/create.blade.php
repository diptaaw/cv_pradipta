@extends('admin.layout')

@section('title', 'Tambah Tag Baru')
@section('page-title', 'Tambah Tag Baru')
@section('page-subtitle', 'Buat tag baru untuk dihubungkan ke proyek dan pengalaman.')

@section('content')
    @if($errors->any())
        <div class="flash-message" style="background: rgba(255,59,48,0.16); border-color: rgba(255,59,48,0.22);">{{ $errors->first() }}</div>
    @endif

    <form class="admin-form" method="POST" action="{{ route('admin.tags.store') }}">
        @csrf

        <label>Nama Tag</label>
        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Unity, Leadership, C#" required>

        <div class="form-actions">
            <button type="submit">Tambah Tag</button>
            <a href="{{ route('admin.tags.index') }}">Batal</a>
        </div>
    </form>
@endsection
