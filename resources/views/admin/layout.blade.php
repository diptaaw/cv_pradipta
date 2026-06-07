<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel')</title>
    @vite(['resources/css/app.css'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-page">
    <!-- Ambient Glow Orbs -->
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>
    <div class="bg-orb orb-3"></div>

    <div class="admin-wrapper">
        <!-- 1. Left Sidebar -->
        <aside class="admin-sidebar">
            <div>
                <div class="sidebar-brand">
                    <img src="{{ asset('images/ui/avatar.png') }}" alt="Branding">
                    <span>DIPTAAW CMS</span>
                </div>
                <ul class="sidebar-menu">
                    @php
                        $route = request()->route()->getName();
                    @endphp
                    <li class="sidebar-menu-item {{ str_starts_with($route, 'admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="11" rx="1"/><rect width="7" height="5" x="3" y="14" rx="1"/></svg>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ str_starts_with($route, 'admin.about') ? 'active' : '' }}">
                        <a href="{{ route('admin.about.edit') }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            <span class="nav-label">Home Content</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ str_starts_with($route, 'admin.experiences') ? 'active' : '' }}">
                        <a href="{{ route('admin.experiences.index') }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>
                            <span class="nav-label">Experiences</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ str_starts_with($route, 'admin.projects') ? 'active' : '' }}">
                        <a href="{{ route('admin.projects.index') }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 14 1.45-2.9A2 2 0 0 1 9.24 10H20a2 2 0 0 1 1.94 2.5l-1.55 6a2 2 0 0 1-1.94 1.5H4a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h3.93a2 2 0 0 1 1.66.9l.82 1.2a2 2 0 0 0 1.66.9H18a2 2 0 0 1 2 2v2"/></svg>
                            <span class="nav-label">Projects</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ str_starts_with($route, 'admin.resumes') ? 'active' : '' }}">
                        <a href="{{ route('admin.resumes.index') }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                            <span class="nav-label">Resume PDF</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ str_starts_with($route, 'admin.tags') ? 'active' : '' }}">
                        <a href="{{ route('admin.tags.index') }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2H2v10l9.29 9.29c.94.94 2.46.94 3.42 0l6.58-6.58c.94-.94.94-2.46 0-3.42L12 2Z"/><path d="M6 6h.01"/></svg>
                            <span class="nav-label">Tags</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ str_starts_with($route, 'admin.updates') ? 'active' : '' }}">
                        <a href="{{ route('admin.updates.index') }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path></svg>
                            <span class="nav-label">Updates</span>
                        </a>
                    </li>
                    @if(auth()->user() && auth()->user()->isSuperAdmin())
                        <li class="sidebar-menu-item {{ str_starts_with($route, 'admin.admins') ? 'active' : '' }}">
                            <a href="{{ route('admin.admins.index') }}">
                                <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                <span class="nav-label">Admin Users</span>
                            </a>
                        </li>
                    @endif
                    <li class="sidebar-menu-item {{ str_starts_with($route, 'admin.analytics') ? 'active' : '' }}">
                        <a href="{{ route('admin.analytics.index') }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/></svg>
                            <span class="nav-label">Analytics</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ str_starts_with($route, 'admin.settings') ? 'active' : '' }}">
                        <a href="{{ route('admin.settings.index') }}">
                            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.1a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                            <span class="nav-label">Settings</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">
                <a href="/" target="_blank" class="btn-portfolio">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    <span>View Site</span>
                </a>
                <form method="POST" action="{{ route('admin.logout') }}" style="display:block; width: 100%;">
                    @csrf
                    <button type="submit" class="btn-logout" style="width: 100%; border: none;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- 2. Main Content Area -->
        <main class="admin-main">
            <!-- Top Bar -->
            <header class="admin-topbar">
                <div class="topbar-left">
                    <div class="admin-breadcrumb">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        @yield('breadcrumb')
                    </div>
                    <h1>@yield('page-title')</h1>
                </div>
                <div class="topbar-right">
                    <div class="topbar-search">
                        <input type="search" placeholder="Quick search..." aria-label="Search">
                    </div>
                    <!-- Notification Bell -->
                    <div class="topbar-notification" title="No new notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </div>
                    <div class="profile-widget">
                        <img src="{{ auth()->user() && auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/ui/avatar.png') }}" alt="Admin Avatar">
                        <div class="profile-widget-info">
                            <span class="profile-widget-name">{{ auth()->user()->name }}</span>
                            <span class="profile-widget-role">
                                {{ auth()->user()->isSuperAdmin() ? 'Super Admin' : 'Admin' }}
                            </span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="admin-content">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
