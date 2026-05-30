@extends('admin.layout')

@section('title', 'Manage Projects')
@section('page-title', 'Manage Projects')
@section('page-subtitle', 'Tambahkan, edit, atau hapus project yang tampil di halaman utama, termasuk kemampuan memasukkan teknologi dan link GitHub.')

@section('content')
    @if(session('success'))
        <div class="flash-message">{{ session('success') }}</div>
    @endif

    <div class="admin-actions" style="margin-bottom: 24px;">
        <a href="{{ route('admin.projects.create') }}">Tambah Project Baru</a>
    </div>

    @if($projects->isEmpty())
        <div class="admin-card">
            <p>Tidak ada project saat ini. Tambahkan project baru agar muncul di web utama.</p>
        </div>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Teknologi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->title }}</td>
                        <td>
                            @foreach($project->technologies ?? [] as $tech)
                                <span class="admin-badge">{{ $tech }}</span>
                            @endforeach
                        </td>
                        <td>{{ $project->is_published ? 'Published' : 'Draft' }}</td>
                        <td style="display:flex; gap:10px; align-items:center;">
                            <a href="{{ route('admin.projects.edit', $project) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: rgba(255,255,255,0.08); color:#fff;">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
