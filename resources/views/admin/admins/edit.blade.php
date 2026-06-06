@extends('admin.layout')

@section('title', 'Edit Admin')
@section('page-title', 'Edit Admin')
@section('page-subtitle', 'Perbarui detail akun administrator, ubah role, aktifkan/nonaktifkan akun, atau ubah password.')

@section('content')
    @if($errors->any())
        <div class="flash-message" style="background: rgba(255,59,48,0.16); border-color: rgba(255,59,48,0.22);">{{ $errors->first() }}</div>
    @endif

    <form class="admin-form" method="POST" action="{{ route('admin.admins.update', $admin) }}">
        @csrf
        @method('PUT')

        <label>Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name', $admin->name) }}" required>

        <label>Alamat Email</label>
        <input type="email" name="email" value="{{ old('email', $admin->email) }}" required>

        <label>Password Baru (kosongkan jika tidak diubah)</label>
        <input type="password" name="password">

        <label>Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation">

        @if(auth()->id() !== $admin->id)
            <label>Role</label>
            <select name="role_id" required>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id', $admin->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>

            <label style="display:inline-flex; align-items:center; gap:8px; margin-bottom:16px;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $admin->is_active) ? 'checked' : '' }} style="width:auto; margin-bottom:0;">
                Akun Aktif / Diaktifkan
            </label>
        @else
            <!-- Pass existing values for self to prevent locking self out -->
            <input type="hidden" name="role_id" value="{{ $admin->role_id }}">
            <input type="hidden" name="is_active" value="{{ $admin->is_active ? '1' : '0' }}">
        @endif

        <div class="form-actions">
            <button type="submit">Simpan Perubahan</button>
            <a href="{{ route('admin.admins.index') }}">Batal</a>
        </div>
    </form>
@endsection
