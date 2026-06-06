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

                <button
                    type="button"
                    class="theme-button"
                    aria-label="Toggle color theme"
                    aria-pressed="false"
                    data-theme-toggle
                >
                    <span class="theme-icon" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </nav>
</div>
