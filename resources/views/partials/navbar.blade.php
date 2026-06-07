@php
    $activePage = $activePage ?? 'home';

    $navItems = [
        'home' => ['label' => 'Home', 'href' => '/'],
        'resume' => ['label' => 'Resume', 'href' => '/resume'],
        'archive' => ['label' => 'Archive', 'href' => '/archive'],
    ];
@endphp

<div class="layout-shell">
    <nav class="top-navbar" aria-label="Primary navigation">
        <div class="navbar-content">
            <a href="/" class="profile-identity" aria-label="Go to homepage">
                <img
                    src="{{ asset('images/ui/avatar.png') }}"
                    class="profile-avatar"
                    alt="Profile Avatar"
                >

                <span class="profile-name">diptaaw</span>
            </a>

            <div class="navbar-right">
                @foreach($navItems as $key => $item)
                    <a
                        href="{{ $item['href'] }}"
                        class="nav-link {{ $activePage === $key ? 'active' : '' }}"
                        aria-current="{{ $activePage === $key ? 'page' : 'false' }}"
                    >
                        {{ $item['label'] }}
                    </a>
                @endforeach

                <div class="updates-wrapper">
                    <button
                        type="button"
                        class="updates-button"
                        aria-label="Notifications"
                        aria-expanded="false"
                        data-updates-toggle
                    >
                        <svg class="bell-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path></svg>
                        <span class="unread-badge hidden" aria-hidden="true" data-notification-badge>0</span>
                    </button>

                    <div class="updates-dropdown hidden" data-updates-dropdown>
                        <div class="dropdown-header">
                            <div class="dropdown-header-left">
                                <h3>Activity Feed</h3>
                                <p>Recent changes</p>
                            </div>
                            <button type="button" class="mark-all-read-btn hidden" data-mark-all-read title="Mark all as read">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Mark all read
                            </button>
                        </div>
                        <div class="dropdown-content-area" data-updates-content>
                            <div class="updates-loader">Loading activity...</div>
                        </div>
                        <button type="button" class="view-all-link hidden" data-load-more-btn>
                            View older updates
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>
