@extends('admin.layout')

@section('title', 'Admin User Management')
@section('page-title', 'Admin User Management')
@section('page-subtitle', 'Kelola pengguna administrator sistem. Hanya Super Admin yang dapat mengakses bagian ini.')

@section('content')
    @if(session('success'))
        <div class="flash-message">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="flash-message" style="background: rgba(255,59,48,0.16); border-color: rgba(255,59,48,0.22);">{{ $errors->first() }}</div>
    @endif

    <div class="admin-actions" style="margin-bottom: 24px;">
        <a href="{{ route('admin.admins.create') }}">Tambah Admin Baru</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Dibuat Pada</th>
                <th>Login Terakhir</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $admin)
                <tr>
                    <td>{{ $admin->name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>
                        @if($admin->isSuperAdmin())
                            <span class="admin-badge" style="background:rgba(123,97,255,0.16); border-color:rgba(123,97,255,0.22); color:#a38fff;">Super Admin</span>
                        @else
                            <span class="admin-badge">Admin</span>
                        @endif
                    </td>
                    <td>
                        @if($admin->is_active)
                            <span class="admin-badge" style="background:rgba(46,204,113,0.18); color:#2ecc71; border-color:rgba(46,204,113,0.24)">Aktif</span>
                        @else
                            <span class="admin-badge" style="background:rgba(255,59,48,0.18); color:#ff453a; border-color:rgba(255,59,48,0.22)">Nonaktif</span>
                        @endif
                    </td>
                    <td>{{ $admin->created_at->format('M d, Y') }}</td>
                    <td>{{ $admin->last_login_at ? $admin->last_login_at->format('M d, Y H:i') : 'Never' }}</td>
                    <td>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <a href="{{ route('admin.admins.edit', $admin) }}">Edit</a>
                            
                            @if(auth()->id() !== $admin->id)
                                <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:rgba(255,59,48,0.18); color:#ff453a; border:none; padding:8px 12px; border-radius:8px; cursor:pointer;" onclick="return confirm('Hapus admin ini secara permanen?')">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
