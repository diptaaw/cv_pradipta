@extends('admin.layout')

@section('title', 'Manage Experiences')
@section('page-title', 'Manage Experiences')
@section('page-subtitle', 'Tambah, ubah, atau hapus pengalaman yang tampil di halaman utama. Setiap entri mendukung tag yang dapat diedit.')

@section('content')
    @if(session('success'))
        <div class="flash-message">{{ session('success') }}</div>
    @endif

    <div class="admin-actions" style="margin-bottom: 24px;">
        <a href="{{ route('admin.experiences.create') }}">Tambah Pengalaman Baru</a>
    </div>

    @if($experiences->isEmpty())
        <div class="admin-card">
            <p>Tidak ada pengalaman saat ini. Silakan tambahkan data baru agar tampil di halaman utama.</p>
        </div>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Tahun / Organisasi</th>
                    <th>Tags</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($experiences as $experience)
                    <tr>
                        <td>{{ $experience->title }}</td>
                        <td>{{ $experience->year }} · {{ $experience->organization }}</td>
                        <td>
                            @foreach($experience->tags ?? [] as $tag)
                                <span class="admin-badge">{{ $tag }}</span>
                            @endforeach
                        </td>
                        <td>{{ $experience->is_published ? 'Published' : 'Draft' }}</td>
                        <td style="display:flex; gap:10px; align-items:center;">
                            <a href="{{ route('admin.experiences.edit', $experience) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.experiences.destroy', $experience) }}" style="display:inline;">
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
