@extends('admin.layout')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'Kelola konten utama, lihat statistik, dan buka cepat halaman edit untuk pengalaman, proyek, serta About.')

@section('content')
    @if(session('success'))
        <div class="flash-message">{{ session('success') }}</div>
    @endif

    <div class="admin-grid">
        <div class="admin-card">
            <h2>Experiences</h2>
            <p>{{ $experienceCount ?? 0 }} entri saat ini.</p>
            <a href="{{ route('admin.experiences.index') }}">Kelola Experiences</a>
        </div>
        <div class="admin-card">
            <h2>Projects</h2>
            <p>{{ $projectCount ?? 0 }} entri saat ini.</p>
            <a href="{{ route('admin.projects.index') }}">Kelola Projects</a>
        </div>
        <div class="admin-card">
            <h2>About</h2>
            <p>{{ $aboutCount ?? 0 }} section disimpan.</p>
            <a href="{{ route('admin.about.edit') }}">Edit About</a>
        </div>
        <div class="admin-card">
            <h2>Social</h2>
            <p>{{ $socialLinkCount ?? 0 }} social links terdaftar.</p>
            <a href="{{ route('admin.dashboard') }}">Lihat Dashboard</a>
        </div>
    </div>

    <div class="admin-card" style="margin-top:24px;">
        <h2>Ringkasan Terbaru</h2>
        <div class="admin-grid">
            <div class="admin-card" style="background: rgba(255,255,255,0.03);">
                <h3>Recent Experiences</h3>
                <ul style="padding-left: 18px;">
                    @foreach($recentExperiences as $item)
                        <li>{{ $item->title }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="admin-card" style="background: rgba(255,255,255,0.03);">
                <h3>Recent Projects</h3>
                <ul style="padding-left: 18px;">
                    @foreach($recentProjects as $item)
                        <li>{{ $item->title }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
