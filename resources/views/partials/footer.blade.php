@php
    $github = \App\Models\SiteSetting::get('social_github');
    $instagram = \App\Models\SiteSetting::get('social_instagram');
    $linkedin = \App\Models\SiteSetting::get('social_linkedin');
    $email = \App\Models\SiteSetting::get('social_email');
@endphp

<footer class="bottom-container reveal">
    @include('partials.section-sparkles', ['positions' => [
        ['top' => '-20%', 'left' => '105%', 'size' => '24px', 'blur' => '0px', 'dur' => '5s', 'del' => '0s'],
        ['top' => '80%', 'left' => '110%', 'size' => '16px', 'blur' => '1.5px', 'dur' => '4.5s', 'del' => '1.5s'],
        ['top' => '50%', 'left' => '-10%', 'size' => '40px', 'blur' => '0.5px', 'dur' => '6s', 'del' => '0.5s'],
        ['top' => '110%', 'left' => '-5%', 'size' => '16px', 'blur' => '0.5px', 'dur' => '6s', 'del' => '1s']
    ]])
    <!-- Icon Row: Admin + Social Links (unified) -->
    <div class="footer-icons">
        <a href="{{ route('admin.login') }}" class="footer-icon" aria-label="Admin Portal" title="Admin">
            <img src="{{ asset('images/icons/admin.svg') }}" alt="Admin">
        </a>

        @if($github)
            <a href="{{ $github }}" target="_blank" class="footer-icon" aria-label="Visit GitHub">
                <img src="{{ asset('images/icons/github.svg') }}" alt="GitHub">
            </a>
        @endif

        @if($instagram)
            <a href="{{ $instagram }}" target="_blank" class="footer-icon" aria-label="Visit Instagram">
                <img src="{{ asset('images/icons/instagram.svg') }}" alt="Instagram">
            </a>
        @endif

        @if($linkedin)
            <a href="{{ $linkedin }}" target="_blank" class="footer-icon" aria-label="Visit LinkedIn">
                <img src="{{ asset('images/icons/linkedin.svg') }}" alt="LinkedIn">
            </a>
        @endif

        @if($email)
            <a href="{{ str_starts_with($email, 'mailto:') ? $email : 'mailto:' . $email }}" class="footer-icon" aria-label="Send Email">
                <img src="{{ asset('images/icons/email.svg') }}" alt="Email">
            </a>
        @endif
    </div>

    <!-- Footer Text & Copyright -->
    <div class="footer-text">
        <p class="footer-info">
            {!! nl2br(e(\App\Models\SiteSetting::get('footer_designed_text', 'Designed and developed by yours truly with Figma and Visual Studio Code. Created as a digital space to showcase multimedia production, visual storytelling, and creative technology projects.'))) !!}
        </p>
        <p class="footer-copyright">
            {!! nl2br(e(\App\Models\SiteSetting::get('footer_copyright_text', '© 2026 Pradipta Adicandra Wicaksono'))) !!}
        </p>
    </div>
</footer>
