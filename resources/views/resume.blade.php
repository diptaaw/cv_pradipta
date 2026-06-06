<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Resume - Pradipta Portfolio</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body id="top" class="inner-page">

<div class="bg-orb orb-1"></div>
<div class="bg-orb orb-2"></div>
<div class="bg-orb orb-3"></div>

@include('partials.navbar', ['activePage' => 'resume'])

<div class="spotlight"></div>
<div class="custom-cursor">
    <img src="/images/cursor/cursor.png" alt="">
</div>

<main class="inner-shell resume-shell">
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
</main>

<div class="particles"></div>

</body>
</html>
