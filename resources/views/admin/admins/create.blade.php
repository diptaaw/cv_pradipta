@extends('admin.layout')

@section('title', 'Tambah Admin Baru')
@section('page-title', 'Tambah Admin Baru')
@section('page-subtitle', 'Buat administrator baru di sistem dengan role tertentu.')

@section('content')
    @if($errors->any())
        <div class="flash-message" style="background: rgba(255,59,48,0.16); border-color: rgba(255,59,48,0.22);">{{ $errors->first() }}</div>
    @endif

    <form class="admin-form" method="POST" action="{{ route('admin.admins.store') }}">
        @csrf

        <label>Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name') }}" required>

        <label>Alamat Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required>

        <label>Role</label>
        <select name="role_id" required>
            <option value="">-- Pilih Role --</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
            @endforeach
        </select>

        <label style="display:inline-flex; align-items:center; gap:8px; margin-bottom:16px;">
            <input type="checkbox" name="is_active" value="1" checked style="width:auto; margin-bottom:0;">
            Akun Aktif / Diaktifkan
        </label>

        <div class="form-actions">
            <button type="submit">Tambah Admin</button>
            <a href="{{ route('admin.admins.index') }}">Batal</a>
        </div>
    </form>
@endsection
