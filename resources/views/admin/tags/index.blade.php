@extends('admin.layout')

@section('title', 'Manage Tags')
@section('page-title', 'Manage Tags')
@section('page-subtitle', 'Kelola tag terpusat untuk proyek dan pengalaman. Sistem mencegah nama duplikat secara otomatis.')

@section('content')
    @if(session('success'))
        <div class="flash-message">{{ session('success') }}</div>
    @endif

    <div class="admin-actions" style="margin-bottom: 24px;">
        <a href="{{ route('admin.tags.create') }}">Tambah Tag Baru</a>
    </div>

    @if($tags->isEmpty())
        <div class="admin-card">
            <p>Tidak ada tag saat ini. Tambahkan tag baru agar bisa dihubungkan ke Proyek atau Pengalaman.</p>
        </div>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nama Tag</th>
                    <th>Jumlah Penggunaan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tags as $tag)
                    <tr>
                        <td><span class="admin-badge">{{ $tag->name }}</span></td>
                        <td>
                            Proyek: {{ $tag->projects_count }}, 
                            Pengalaman: {{ $tag->experiences_count }} 
                            <strong>(Total: {{ $tag->usage_count }})</strong>
                        </td>
                        <td style="display:flex; gap:10px; align-items:center;">
                            <a href="{{ route('admin.tags.edit', $tag) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: rgba(255,255,255,0.08); color:#fff; border:none; padding:8px 12px; border-radius:8px; cursor:pointer;" onclick="return confirm('Apakah Anda yakin ingin menghapus tag ini? Semua asosiasi ke Proyek dan Pengalaman akan dilepas.')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
