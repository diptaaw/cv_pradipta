@extends('admin.layout')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'Kelola seluruh konten website portofolio dari satu dashboard terpadu.')

@section('content')
    @if(session('success'))
        <div class="flash-message">{{ session('success') }}</div>
    @endif

    <div class="admin-grid">
        <div class="admin-card">
            <h2>Projects</h2>
            <p style="font-size:1.8rem; font-weight:700; margin:10px 0;">{{ $projectCount ?? 0 }}</p>
            <a href="{{ route('admin.projects.index') }}" style="color:#a38fff; text-decoration:none; font-weight:600;">Kelola Projects &rarr;</a>
        </div>
        <div class="admin-card">
            <h2>Experiences</h2>
            <p style="font-size:1.8rem; font-weight:700; margin:10px 0;">{{ $experienceCount ?? 0 }}</p>
            <a href="{{ route('admin.experiences.index') }}" style="color:#a38fff; text-decoration:none; font-weight:600;">Kelola Experiences &rarr;</a>
        </div>
        <div class="admin-card">
            <h2>Centralized Tags</h2>
            <p style="font-size:1.8rem; font-weight:700; margin:10px 0;">{{ $tagCount ?? 0 }}</p>
            <a href="{{ route('admin.tags.index') }}" style="color:#a38fff; text-decoration:none; font-weight:600;">Kelola Tags &rarr;</a>
        </div>
        <div class="admin-card">
            <h2>Admins</h2>
            <p style="font-size:1.8rem; font-weight:700; margin:10px 0;">{{ $adminCount ?? 0 }}</p>
            @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.admins.index') }}" style="color:#a38fff; text-decoration:none; font-weight:600;">Kelola Admins &rarr;</a>
            @else
                <span style="color:rgba(255,255,255,0.4); font-size:0.88rem;">Hanya Super Admin</span>
            @endif
        </div>
    </div>

    <div class="admin-grid" style="margin-top:24px; grid-template-columns: 1fr 2fr;">
        <!-- Resume Status Card -->
        <div class="admin-card" style="display:flex; flex-direction:column; justify-content:space-between;">
            <div>
                <h2>Resume PDF Status</h2>
                <div style="margin: 20px 0;">
                    @if($publishedResume)
                        <span class="admin-badge" style="background:rgba(46,204,113,0.18); border-color:rgba(46,204,113,0.24); color:#2ecc71; margin-bottom:8px; display:inline-block;">Published</span>
                        <h3 style="font-size:1.05rem; word-break:break-all;">{{ $publishedResume->title }}</h3>
                        <small style="color:rgba(255,255,255,0.5); display:block; margin-top:4px;">Diunggah: {{ $publishedResume->created_at->format('M d, Y') }}</small>
                    @else
                        <span class="admin-badge" style="background:rgba(255,59,48,0.18); border-color:rgba(255,59,48,0.22); color:#ff453a; margin-bottom:8px; display:inline-block;">Offline</span>
                        <h3 style="font-size:1.05rem; color:rgba(255,255,255,0.5);">Tidak ada resume aktif</h3>
                    @endif
                </div>
            </div>
            <a href="{{ route('admin.resumes.index') }}" style="color:#a38fff; text-decoration:none; font-weight:600; display:block; margin-top:16px;">Kelola Resume PDF &rarr;</a>
        </div>

        <!-- Recent Activities Card -->
        <div class="admin-card">
            <h2>Recent Activity Logs</h2>
            @if($recentActivities->isEmpty())
                <p style="color:rgba(255,255,255,0.5); margin-top:16px;">Belum ada aktivitas yang dicatat.</p>
            @else
                <div style="max-height: 250px; overflow-y: auto; margin-top:12px;">
                    <table class="admin-table" style="font-size:0.88rem;">
                        <thead>
                            <tr>
                                <th>Admin</th>
                                <th>Aksi</th>
                                <th>Detail</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivities as $log)
                                <tr>
                                    <td>{{ $log->user ? $log->user->name : 'System' }}</td>
                                    <td><strong>{{ $log->action }}</strong></td>
                                    <td style="color:rgba(255,255,255,0.7);">{{ $log->details }}</td>
                                    <td>{{ $log->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
