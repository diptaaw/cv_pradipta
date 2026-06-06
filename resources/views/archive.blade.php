<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Project Archive - Pradipta Portfolio</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body id="top" class="inner-page">

<div class="bg-orb orb-1"></div>
<div class="bg-orb orb-2"></div>
<div class="bg-orb orb-3"></div>

@include('partials.navbar', ['activePage' => 'archive'])

<div class="spotlight"></div>
<div class="custom-cursor">
    <img src="/images/cursor/cursor.png" alt="">
</div>

<main class="inner-shell archive-shell">
    <section class="inner-hero reveal">
        <p class="inner-kicker">Project Archive</p>
        <div class="inner-hero-grid">
            <div>
                <h1>Complete work index.</h1>
                <p>
                    A deeper catalog of multimedia, visual production, and creative technology projects,
                    arranged for quick scanning as the collection grows.
                </p>
            </div>
            <div class="archive-stats">
                <span>{{ str_pad($projects->count(), 2, '0', STR_PAD_LEFT) }}</span>
                <small>published projects</small>
            </div>
        </div>
    </section>

    <section class="archive-toolbar reveal" aria-label="Archive browsing controls">
        <div class="archive-search-shell">
            <span>Search</span>
            <input type="search" placeholder="Ready for CMS-powered search" aria-label="Search projects" disabled>
        </div>
        <div class="archive-filter-row">
            <button type="button" class="archive-filter active">All</button>
            <button type="button" class="archive-filter" disabled>Category</button>
            <button type="button" class="archive-filter" disabled>Technology</button>
            <button type="button" class="archive-filter" disabled>Year</button>
        </div>
    </section>

    <section class="archive-list reveal" aria-label="Project archive list">
        <div class="archive-list-head">
            <span>Year</span>
            <span>Project</span>
            <span>Category</span>
            <span>Stack</span>
            <span>Links</span>
        </div>

        @forelse($projects as $project)
            @php
                $year = $project->year ?? optional($project->updated_at)->format('Y') ?? 'Now';
                $category = $project->category ?? 'Portfolio Work';
                $projectUrl = $project->project_url ?? $project->project_link;
                $githubUrl = $project->github_url ?? $project->github_link;
            @endphp

            <article class="archive-row">
                <div class="archive-year">{{ $year }}</div>

                <div class="archive-project-main">
                    <h2>{{ $project->title }}</h2>
                    <p>{{ $project->description ?: 'Project details will be added from the CMS.' }}</p>
                </div>

                <div class="archive-category">{{ $category }}</div>

                <div class="archive-tech-list">
                    @forelse($project->technologies ?? [] as $tech)
                        <span>{{ $tech }}</span>
                    @empty
                        <span>CMS Ready</span>
                    @endforelse
                </div>

                <div class="archive-links">
                    @if($projectUrl)
                        <a href="{{ $projectUrl }}" target="_blank" rel="noopener">Live</a>
                    @endif

                    @if($githubUrl)
                        <a href="{{ $githubUrl }}" target="_blank" rel="noopener">GitHub</a>
                    @endif

                    @unless($projectUrl || $githubUrl)
                        <span>Queued</span>
                    @endunless
                </div>
            </article>
        @empty
            <div class="archive-empty">
                <span>Archive ready</span>
                <h2>No published projects yet.</h2>
                <p>Once projects are published from the admin dashboard, they will appear here automatically.</p>
            </div>
        @endforelse
    </section>
</main>

<div class="particles"></div>

</body>
</html>
