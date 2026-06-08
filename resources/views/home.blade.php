<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ \App\Models\SiteSetting::get('site_title', 'Pradipta Portfolio') }}</title>
    <meta name="description" content="{{ \App\Models\SiteSetting::get('meta_description', 'Multimedia & Broadcasting student exploring visual storytelling, creative technology, and digital media production.') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">

</head>

<body id="top" class="loading-active">

@include('partials.loader')

<div class="bg-orb orb-1"></div>
<div class="bg-orb orb-2"></div>
<div class="bg-orb orb-3"></div>

@include('partials.navbar', ['activePage' => 'home'])

<div class="spotlight"></div>

    <div class="custom-cursor">
        <img src="/images/cursor/cursor.png" alt="">
    </div>



    <div class="main-container">

        <!-- 1. Page Header Container (Reserved for future page titles, breadcrumbs, etc.) -->
        <header class="page-header-container" aria-label="Page Header"></header>

        <!-- Intro Showcase -->
        @include('partials.intro-showcase')

        <!-- 2. Two-Column Content Layout -->
        <div class="two-column-layout">

            <!-- Decorative Frame Sparkles -->
            @include('partials.section-sparkles', ['positions' => [
                // LEFT SIDE CLUSTER (Outside profile column)
                ['top' => '-5%', 'left' => '-15%', 'size' => '64px', 'blur' => '1.5px', 'dur' => '5s', 'del' => '0s'],
                ['top' => '5%', 'left' => '-20%', 'size' => '24px', 'blur' => '0px', 'dur' => '4.5s', 'del' => '0.5s'],
                ['top' => '20%', 'left' => '-12%', 'size' => '16px', 'blur' => '1px', 'dur' => '6s', 'del' => '1s'],
                ['top' => '45%', 'left' => '-18%', 'size' => '40px', 'blur' => '0.5px', 'dur' => '5.5s', 'del' => '0.2s'],
                ['top' => '75%', 'left' => '-15%', 'size' => '16px', 'blur' => '0px', 'dur' => '4s', 'del' => '1.5s'],
                ['top' => '90%', 'left' => '-22%', 'size' => '16px', 'blur' => '2px', 'dur' => '6.5s', 'del' => '0.8s'],

                // RIGHT SIDE CLUSTER (Outside content cards)
                ['top' => '-2%', 'left' => '110%', 'size' => '16px', 'blur' => '1px', 'dur' => '4s', 'del' => '1s'],
                ['top' => '15%', 'left' => '115%', 'size' => '40px', 'blur' => '0.5px', 'dur' => '5s', 'del' => '0s'],
                ['top' => '35%', 'left' => '108%', 'size' => '64px', 'blur' => '0px', 'dur' => '6.5s', 'del' => '1.5s'],
                ['top' => '65%', 'left' => '112%', 'size' => '24px', 'blur' => '1.5px', 'dur' => '5.5s', 'del' => '0.5s'],
                ['top' => '85%', 'left' => '105%', 'size' => '16px', 'blur' => '0px', 'dur' => '4.5s', 'del' => '1.2s'],
                ['top' => '95%', 'left' => '118%', 'size' => '16px', 'blur' => '2px', 'dur' => '6s', 'del' => '0.3s']
            ]])

            <!-- LEFT SIDE -->
            <div class="left-panel">
                <div>
                    <h1 class="name reveal">
                        {{ $about->headline ?? 'Pradipta Adicandra Wicaksono' }}
                    </h1>

                    <h2 class="title reveal">
                        {{ $about->subheadline ?? 'Multimedia & Broadcasting Engineer' }}
                    </h2>

                    <p class="description reveal">
                        {{ $about->short_intro ?? 'Exploring the intersection of visuals, storytelling, and digital experiences.' }}
                    </p>

                    <nav class="navigation">
                        <a href="#about" class="active">
                            <span>ABOUT</span>
                        </a>

                        <a href="#experience">
                            <span>EXPERIENCE</span>
                        </a>

                        <a href="#projects">
                            <span>PROJECTS</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="right-panel">

                <section id="about" class="section">
                    @if(isset($about) && is_array($about->paragraphs) && count($about->paragraphs))
                        @foreach($about->paragraphs as $paragraph)
                            <p class="reveal">{{ $paragraph }}</p>
                        @endforeach
                    @else
                        <p class="reveal">
                            I'm a Multimedia Broadcasting student at <b> PENS (EEPIS) </b> with a strong interest in visual storytelling, creative production, and digital media. I enjoy transforming ideas into engaging visual experiences through photography, videography, live streaming, and design.
                        </p>

                        <p class="reveal">
                            Over the past few years, I've worked on various creative and organizational projects, from commercial photography and content production to multimedia events and student organizations. These experiences helped me develop not only technical skills, but also adaptability, communication, and collaborative event planning in fast-paced production environments.
                        </p>

                        <p class="reveal">
                            I'm especially interested in the creative process behind media production: how visuals, lighting, composition, and storytelling can shape emotions and audience experience.
                        </p>
                    @endif
                </section>

                <section id="experience" class="section">
                    @if($experiences->isEmpty())
                        <div class="card reveal">
                            <div class="card-year">2025 — PRESENT</div>
                            <div class="card-content">
                                <h3>Staff PSDM · HIMA Multimedia Broadcasting PENS</h3>
                                <div class="description-wrapper">
                                    <p class="description-text">
                                        Contributed to student development programs and organizational activities through team coordination, recruitment support, and collaborative event planning.
                                    </p>
                                </div>
                                <div class="tags">
                                    <span>Leadership</span>
                                    <span>Team Coordination</span>
                                </div>
                            </div>
                        </div>
                    @else
                        @foreach($experiences as $experience)
                            <div class="card reveal">
                                <div class="card-year">{{ $experience->year ?? '—' }}</div>
                                <div class="card-content">
                                    <h3>{{ $experience->title }}@if($experience->organization) · {{ $experience->organization }}@endif</h3>
                                    <div class="description-wrapper">
                                        <p class="description-text">{{ $experience->description }}</p>
                                    </div>
                                    <div class="tags">
                                        @foreach($experience->tags ?? [] as $tag)
                                            <span>{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <a href="/resume" class="section-link reveal">
                        View Full Résumé
                        <span>↗</span>
                    </a>

                </section>

                <section id="projects" class="section">
                    @if($projects->isEmpty())
                        <div class="card reveal">
                            <img loading="lazy" src="{{ asset('images/projects/wildlife.png') }}" alt="Wildlife Project" class="project-image">
                            <div class="card-content">
                                <h3 class="project-title">Interactive Wildlife Park <span class="arrow">↗</span></h3>
                                <div class="description-wrapper">
                                    <p class="description-text">An educational wildlife park built in Unity featuring interactive systems, dynamic weather, NPC behavior, and immersive environment exploration.</p>
                                </div>
                                <div class="tags">
                                    <span>Unity</span>
                                    <span>C#</span>
                                    <span>Game Environment</span>
                                </div>
                            </div>
                        </div>
                    @else
                        @foreach($projects as $project)
                            <div class="card reveal">
                                <img loading="lazy" src="{{ $project->thumbnail ? storage_url($project->thumbnail) : asset('images/projects/wildlife.png') }}" alt="{{ $project->title }}" class="project-image">
                                <div class="card-content">
                                    <h3 class="project-title">{{ $project->title }} <span class="arrow">↗</span></h3>
                                    <div class="description-wrapper">
                                        <p class="description-text">{{ $project->description }}</p>
                                    </div>
                                    <div class="tags">
                                        @foreach($project->technologies ?? [] as $tech)
                                            <span>{{ $tech }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <a href="/archive" class="section-link reveal">
                        View Full Project Archive
                        <span>↗</span>
                    </a>

                </section>

            </div>

        </div>

        <!-- 3. Bottom Information Container (Footer, Socials, Admin) -->
        @include('partials.footer')

    </div>

    @include('partials.cat-companion')
    <div class="particles"></div>

</body>
</html>
