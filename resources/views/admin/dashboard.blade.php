@extends('admin.layout')

@section('title', 'Control Room Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    @if(session('success'))
        <div class="flash-message" style="margin-bottom: 24px; padding: 12px 16px; background: rgba(46,204,113,0.12); border: 1px solid rgba(46,204,113,0.20); color: #2ecc71; border-radius: 12px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <!-- 1. Statistics Cards Row -->
    <div class="stats-row">
        <!-- Projects -->
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-label">Projects</span>
                <div class="stat-card-icon purple">
                    <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 14 1.45-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.55 6a2 2 0 0 1-1.94 1.5H4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h3.93a2 2 0 0 1 1.66.9l.82 1.2a2 2 0 0 0 1.66.9H18a2 2 0 0 1 2 2v2"/></svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $projectCount }}</div>
            <a href="{{ route('admin.projects.index') }}" class="stat-card-action">Manage &rarr;</a>
        </div>

        <!-- Experiences -->
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-label">Experiences</span>
                <div class="stat-card-icon blue">
                    <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $experienceCount }}</div>
            <a href="{{ route('admin.experiences.index') }}" class="stat-card-action">Manage &rarr;</a>
        </div>

        <!-- Tags -->
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-label">Tags</span>
                <div class="stat-card-icon green">
                    <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.46.94 3.42 0l6.58-6.58c.94-.94.94-2.46 0-3.42L12 2Z"/><path d="M6 6h.01"/></svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $tagCount }}</div>
            <a href="{{ route('admin.tags.index') }}" class="stat-card-action">Manage &rarr;</a>
        </div>

        <!-- Administrators -->
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-label">Admins</span>
                <div class="stat-card-icon amber">
                    <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
            </div>
            <div class="stat-card-value">{{ $adminCount }}</div>
            @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.admins.index') }}" class="stat-card-action">Manage &rarr;</a>
            @else
                <span class="stat-card-action" style="color: rgba(255,255,255,0.25); cursor: default;">View Only</span>
            @endif
        </div>

        <!-- Resume Status -->
        <div class="stat-card">
            <div class="stat-card-header">
                <span class="stat-card-label">Resume</span>
                <div class="stat-card-icon {{ $publishedResume ? 'green' : 'rose' }}">
                    <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                </div>
            </div>
            <div class="stat-card-value" style="font-size: 1.4rem; margin-top: 18px; margin-bottom: 8px;">
                @if($publishedResume)
                    <span style="color: #2ecc71; -webkit-text-fill-color: #2ecc71;">Active</span>
                @else
                    <span style="color: rgba(255,255,255,0.3); -webkit-text-fill-color: rgba(255,255,255,0.3);">Offline</span>
                @endif
            </div>
            <a href="{{ route('admin.resumes.index') }}" class="stat-card-action">Manage &rarr;</a>
        </div>
    </div>

    <!-- 2. Middle Row: Homepage Overview & Recent Activity -->
    <div class="admin-grid" style="grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px; margin-bottom: 28px;">
        <!-- Homepage Overview -->
        <div class="admin-card">
            <h2 style="font-size: 1rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#a996ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="11" rx="1"/><rect width="7" height="5" x="3" y="14" rx="1"/></svg>
                Homepage Overview
            </h2>
            <div style="display: flex; flex-direction: column; gap: 16px; font-size: 0.86rem;">
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.04); padding-bottom: 12px;">
                    <span style="color: rgba(255,255,255,0.45);">About Section Status</span>
                    @if($aboutStatus === 'Published')
                        <span class="admin-badge" style="background: rgba(46,204,113,0.10); border: 1px solid rgba(46,204,113,0.15); color: #2ecc71;">{{ $aboutStatus }}</span>
                    @elseif($aboutStatus === 'Draft')
                        <span class="admin-badge" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); color: rgba(255,255,255,0.4);">{{ $aboutStatus }}</span>
                    @else
                        <span class="admin-badge" style="background: rgba(255,59,48,0.10); border: 1px solid rgba(255,59,48,0.15); color: #ff6b6b;">{{ $aboutStatus }}</span>
                    @endif
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.04); padding-bottom: 12px;">
                    <span style="color: rgba(255,255,255,0.45);">Featured Projects on Home</span>
                    <span style="font-weight: 700; font-size: 0.82rem; color: #a996ff;">{{ $featuredProjectsCount }} / 5</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding-bottom: 4px;">
                    <span style="color: rgba(255,255,255,0.45);">Featured Experiences on Home</span>
                    <span style="font-weight: 700; font-size: 0.82rem; color: #a996ff;">{{ $featuredExperiencesCount }} / 5</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity Timeline -->
        <div class="admin-card">
            <h2 style="font-size: 1rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#a996ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Recent Activity
            </h2>

            @if($recentActivities->isEmpty())
                <p style="color: rgba(255,255,255,0.3); text-align: center; padding: 40px 0; font-size: 0.88rem;">
                    No recent activities recorded.
                </p>
            @else
                <div class="timeline" style="max-height: 180px; overflow-y: auto;">
                    @foreach($recentActivities as $log)
                        <div class="timeline-item">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <div class="timeline-meta">
                                    {{ $log->created_at->diffForHumans() }} &middot; by <strong style="color: rgba(255,255,255,0.8)">{{ $log->user ? $log->user->name : 'System' }}</strong>
                                </div>
                                <div class="timeline-title">{{ $log->action }}</div>
                                <div class="timeline-desc">{{ $log->details }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- 3. Bottom Row: Quick Actions -->
    <div class="admin-card" style="margin-bottom: 24px;">
        <h2 style="font-size: 1rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#a996ff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            Quick Actions
        </h2>
        <div class="quick-actions-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));">
            <a href="{{ route('admin.projects.create') }}" class="quick-action-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 14 1.45-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.55 6a2 2 0 0 1-1.94 1.5H4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h3.93a2 2 0 0 1 1.66.9l.82 1.2a2 2 0 0 0 1.66.9H18a2 2 0 0 1 2 2v2"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg>
                New Project
            </a>
            <a href="{{ route('admin.experiences.create') }}" class="quick-action-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg>
                New Experience
            </a>
            <a href="{{ route('admin.resumes.index') }}" class="quick-action-btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Upload Resume
            </a>
            @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.admins.create') }}" class="quick-action-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="16" y1="11" x2="22" y2="11"/></svg>
                    Add Admin
                </a>
            @else
                <div class="quick-action-btn" style="opacity: 0.3; cursor: not-allowed;" title="Super Admin only">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="16" y1="11" x2="22" y2="11"/></svg>
                    Add Admin
                </div>
            @endif
        </div>
    </div>
@endsection
