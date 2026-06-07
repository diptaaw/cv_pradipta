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
                        aria-label="What's New updates"
                        aria-expanded="false"
                        data-updates-toggle
                    >
                        <svg class="bell-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path></svg>
                        <span class="unread-dot hidden" aria-hidden="true" data-updates-dot></span>
                    </button>

                    <div class="updates-dropdown hidden" data-updates-dropdown>
                        <div class="dropdown-header">
                            <h3>What's New</h3>
                            <p>Latest portfolio updates</p>
                        </div>
                        <div class="dropdown-content-area" data-updates-content>
                            <div class="updates-loader">Loading updates...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>
