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

</head>

<body id="top">

<div class="bg-orb orb-1"></div>
<div class="bg-orb orb-2"></div>
<div class="bg-orb orb-3"></div>

@include('partials.navbar', ['activePage' => 'home'])

<div class="spotlight"></div>

    <div class="custom-cursor">
        <img src="/images/cursor/cursor.png" alt="">
    </div>

    <div class="main-container">

        <!-- LEFT SIDE -->
        <div class="left-panel">

            <div>

                <h1 class="name">
                    {{ $about->headline ?? 'Pradipta Adicandra Wicaksono' }}
                </h1>

                <h2 class="title">
                    {{ $about->subheadline ?? 'Multimedia & Broadcasting Engineer' }}
                </h2>

                <p class="description">
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

            <div class="socials">

                <!-- Admin trigger -->
                <a href="{{ route('admin.login') }}" class="social-link admin-trigger" aria-label="Admin Portal" title="Admin">
                    <img src="{{ asset('images/icons/admin.svg') }}" alt="">
                </a>

                @php
                    $github = \App\Models\SiteSetting::get('social_github');
                    $instagram = \App\Models\SiteSetting::get('social_instagram');
                    $linkedin = \App\Models\SiteSetting::get('social_linkedin');
                    $email = \App\Models\SiteSetting::get('social_email');
                @endphp

                @if($github)
                    <a href="{{ $github }}" target="_blank" class="social-link" aria-label="Visit GitHub">
                        <img src="{{ asset('images/icons/github.svg') }}" alt="">
                    </a>
                @endif

                @if($instagram)
                    <a href="{{ $instagram }}" target="_blank" class="social-link" aria-label="Visit Instagram">
                        <img src="{{ asset('images/icons/instagram.svg') }}" alt="">
                    </a>
                @endif

                @if($linkedin)
                    <a href="{{ $linkedin }}" target="_blank" class="social-link" aria-label="Visit LinkedIn">
                        <img src="{{ asset('images/icons/linkedin.svg') }}" alt="">
                    </a>
                @endif

                @if($email)
                    <a href="{{ str_starts_with($email, 'mailto:') ? $email : 'mailto:' . $email }}" class="social-link" aria-label="Send Email">
                        <img src="{{ asset('images/icons/email.svg') }}" alt="">
                    </a>
                @endif

            </div>

        </div>

        <div class="right-panel">

            <section id="about" class="section reveal">

                @if(isset($about) && is_array($about->paragraphs) && count($about->paragraphs))
                    @foreach($about->paragraphs as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                @else
                    <p>
                        I'm a Multimedia Broadcasting student at <b> PENS (EEPIS) </b> with a strong interest in visual storytelling, creative production, and digital media. I enjoy transforming ideas into engaging visual experiences through photography, videography, live streaming, and design.
                    </p>

                    <p>
                        Over the past few years, I've worked on various creative and organizational projects, from commercial photography and content production to multimedia events and student organizations. These experiences helped me develop not only technical skills, but also adaptability, communication, and collaborative event planning in fast-paced production environments.
                    </p>

                    <p>
                        I'm especially interested in the creative process behind media production: how visuals, lighting, composition, and storytelling can shape emotions and audience experience.
                    </p>
                @endif

            </section>

            <section id="experience" class="section reveal">

                @if($experiences->isEmpty())
                    <div class="card">
                        <div class="card-year">2025 — PRESENT</div>
                        <div class="card-content">
                            <h3>Staff PSDM · HIMA Multimedia Broadcasting PENS</h3>
                            <p>
                                Contributed to student development programs and organizational activities through team coordination, recruitment support, and collaborative event planning.
                            </p>
                            <div class="tags">
                                <span>Leadership</span>
                                <span>Team Coordination</span>
                            </div>
                        </div>
                    </div>
                @else
                    @foreach($experiences as $experience)
                        <div class="card">
                            <div class="card-year">{{ $experience->year ?? '—' }}</div>
                            <div class="card-content">
                                <h3>{{ $experience->title }}</h3>
                                <p>{{ $experience->description }}</p>
                                <div class="tags">
                                    @foreach($experience->tags ?? [] as $tag)
                                        <span>{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <a href="/resume" class="section-link">
                    View Full Résumé
                    <span>↗</span>
                </a>

            </section>

            <section id="projects" class="section reveal">

                @if($projects->isEmpty())
                    <div class="card">
                        <img loading="lazy" src="{{ asset('images/projects/wildlife.png') }}" alt="Wildlife Project" class="project-image">
                        <div class="card-content">
                            <h3 class="project-title">Interactive Wildlife Park <span class="arrow">↗</span></h3>
                            <p>An educational wildlife park built in Unity featuring interactive systems, dynamic weather, NPC behavior, and immersive environment exploration.</p>
                            <div class="tags">
                                <span>Unity</span>
                                <span>C#</span>
                                <span>Game Environment</span>
                            </div>
                        </div>
                    </div>
                @else
                    @foreach($projects as $project)
                        <div class="card">
                            <img loading="lazy" src="{{ $project->thumbnail ? asset($project->thumbnail) : asset('images/projects/wildlife.png') }}" alt="{{ $project->title }}" class="project-image">
                            <div class="card-content">
                                <h3 class="project-title">{{ $project->title }} <span class="arrow">↗</span></h3>
                                <p>{{ $project->description }}</p>
                                <div class="tags">
                                    @foreach($project->technologies ?? [] as $tech)
                                        <span>{{ $tech }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <a href="/archive" class="section-link">
                    View Full Project Archive
                    <span>↗</span>
                </a>

            </section>



            <footer class="footer">
                <p>
                {!! nl2br(e(\App\Models\SiteSetting::get('footer_designed_text', 'Designed and developed by yours truly with Figma and Visual Studio Code. Created as a digital space to showcase multimedia production, visual storytelling, and creative technology projects.'))) !!}
                </p>

                <p>
                {!! nl2br(e(\App\Models\SiteSetting::get('footer_copyright_text', '© 2026 Pradipta Adicandra Wicaksono'))) !!}
                </p>
            </footer>

        </div>

    </div>

    <div class="particles"></div>

</body>
</html>
