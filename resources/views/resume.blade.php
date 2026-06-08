<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Resume — {{ \App\Models\SiteSetting::get('site_title', 'Pradipta Portfolio') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body id="top" class="inner-page loading-active">

@include('partials.loader')

<div class="bg-orb orb-1"></div>
<div class="bg-orb orb-2"></div>
<div class="bg-orb orb-3"></div>

@include('partials.navbar', ['activePage' => 'resume'])

<div class="spotlight"></div>
<div class="custom-cursor">
    <img src="/images/cursor/cursor.png" alt="">
</div>

<main class="inner-shell resume-shell">
    <!-- Decorative Frame Sparkles -->
    @include('partials.section-sparkles', ['positions' => [
        // LEFT SIDE
        ['top' => '75%', 'left' => '-16%', 'size' => '95px', 'blur' => '1.5px', 'dur' => '7s', 'del' => '0.5s', 'opacity_min' => '0.25', 'opacity_max' => '0.85'], // Large lower-left
        ['top' => '30%', 'left' => '-12%', 'size' => '28px', 'blur' => '0px', 'dur' => '5s', 'del' => '1s', 'opacity_min' => '0.35', 'opacity_max' => '0.9'],     // Small top-left

        // RIGHT SIDE
        ['top' => '12%', 'left' => '110%', 'size' => '68px', 'blur' => '0.5px', 'dur' => '6s', 'del' => '0.2s', 'opacity_min' => '0.3', 'opacity_max' => '0.9'], // Medium upper-right
        ['top' => '45%', 'left' => '105%', 'size' => '32px', 'blur' => '0px', 'dur' => '4.5s', 'del' => '1.5s', 'opacity_min' => '0.2', 'opacity_max' => '0.8'], // Small content edge
        ['top' => '80%', 'left' => '108%', 'size' => '24px', 'blur' => '1px', 'dur' => '5.5s', 'del' => '0.8s', 'opacity_min' => '0.3', 'opacity_max' => '0.85']
    ]])

    <section class="resume-hero reveal">
        <div>
            <p class="inner-kicker">Resume</p>
            <h1>{{ $resume?->title ?: 'Professional resume' }}</h1>
            <p>
                A focused view of experience, production work, and creative technology background,
                presented as a living document from the portfolio CMS.
            </p>
        </div>

        <div class="resume-actions">
            <span>
                Last updated
                <strong>{{ $resume?->updated_at ? $resume->updated_at->format('M d, Y') : 'Awaiting upload' }}</strong>
            </span>

            @if($resumeUrl)
                <a href="{{ $resumeUrl }}" class="resume-download" download>Download PDF</a>
            @else
                <span class="resume-download disabled">PDF pending</span>
            @endif
        </div>
    </section>

    <section class="resume-viewer-wrap reveal">
        @if($resumeUrl)
            <div class="resume-viewer-top">
                <span>{{ $resume?->title ?: 'Resume PDF' }}</span>
                <small>Embedded document</small>
            </div>
            <object class="resume-viewer" data="{{ $resumeUrl }}#toolbar=0" type="application/pdf">
                <div class="resume-empty">
                    <span>Preview unavailable</span>
                    <h2>Your browser cannot display this PDF inline.</h2>
                    <p>The download button above will open the current resume file directly.</p>
                </div>
            </object>
        @else
            <div class="resume-empty">
                <span>CMS ready</span>
                <h2>No published resume yet.</h2>
                <p>When a PDF is uploaded and published from the admin dashboard, this page will update automatically.</p>
            </div>
        @endif
    </section>

    @include('partials.footer')
</main>

<div class="particles"></div>

</body>
</html>
