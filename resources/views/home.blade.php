<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pradipta Portfolio</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<div class="bg-orb orb-1"></div>
<div class="bg-orb orb-2"></div>
<div class="bg-orb orb-3"></div>

<div class="top-navbar">

    <div class="navbar-content">

        <div class="navbar-left">

            <a href="#about" class="profile-identity">
            <img
            src="{{ asset('images/ui/avatar.png') }}"
            class="profile-avatar"
            >

            <span class="profile-name">
                diptaaw
            </span>
            </a>
        </div>

        <div class="navbar-right">

        <a href="#" class="nav-icon">
            <img src="/images/icons/email.svg" alt="">
        </a>

        <a href="#" class="nav-icon">
            <img src="/images/icons/instagram.svg" alt="">
        </a>

        <a href="#" class="nav-icon">
            <img src="/images/icons/github.svg" alt="">
        </a>

        <button class="theme-button">
            <img src="/images/icons/moon.svg" alt="">
        </button>

</div>

    </div>

</div>

    <div class="spotlight"></div>

    <div class="custom-cursor">
        <img src="/images/cursor/cursor.png" alt="">
    </div>

    <div class="main-container">

        <!-- LEFT SIDE -->
        <div class="left-panel">

            <div>

                <h1 class="name">
                    Pradipta Adicandra Wicaksono
                </h1>

                <h2 class="title">
                    Multimedia & Broadcasting Engineer
                </h2>

                <p class="description">
                    Exploring the intersection of visuals,
                    storytelling, and digital experiences.
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

                <a href="#">GitHub</a>

                <a href="#">Instagram</a>

                <a href="#">LinkedIn</a>

            </div>

        </div>



        <!-- RIGHT SIDE -->
        <div class="right-panel">

            <section id="about" class="section reveal">

                <p>
                    I'm a Multimedia Broadcasting student at <b> PENS (EEPIS) </b> with a strong interest in visual storytelling, creative production, and digital media.         I enjoy transforming ideas into engaging visual experiences through photography, videography, live streaming, and design.
                </p>

                <p>
                    Over the past few years, I've worked on various creative and organizational projects, from commercial photography and content production to multimedia events and student organizations. These experiences helped me develop not only technical skills, but also adaptability, communication, and collaborative problem solving in fast-paced production environments.
                </p>

                <p>
                    I'm especially interested in the creative process behind media production: how visuals, lighting, composition, and storytelling can shape emotions and audience experience.
                </p>

                <p>
                    Currently, I’m continuing to explore photography, UI/UX, branding, and multimedia technology while building projects that combine creativity with technical execution.
                </p>

            </section>



            <section id="experience" class="section reveal">

                <div class="card">

                    <div class="card-year">
                        2025 — PRESENT
                    </div>

                    <div class="card-content">

                        <h3>
                            Staff PSDM · HIMA Multimedia Broadcasting PENS
                        </h3>

                        <p>
                            Contributed to student development programs and
                            organizational activities through team coordination,
                            recruitment support, and collaborative event planning.
                        </p>

                        <div class="tags">

                            <span>Leadership</span>

                            <span>Team Coordination</span>

                        </div>

                    </div>

                </div>

                <a href="/resume" class="section-link">
            View Full Résumé
             <span>↗</span>
            </a>

            </section>

            <section id="projects" class="section reveal">

                <div class="card">

                   <img loading="lazy"
                    src="{{ asset('images/projects/wildlife.png') }}"
                    alt="Wildlife Project"
                    class="project-image"
                    >

                    <div class="card-content">


                         <h3 class="project-title">

                                Interactive Wildlife Park

                                <span class="arrow">
                                   ↗
                                </span>

                        </h3>

                        <p>
                            An educational wildlife park built in Unity featuring
                            interactive systems, dynamic weather, NPC behavior,
                            and immersive environment exploration.
                        </p>

                        <div class="tags">

                            <span>Unity</span>

                            <span>C#</span>

                            <span>Game Environment</span>

                        </div>

                    </div>

                </div>

                <a href="/archive" class="section-link">
                    View Full Project Archive
                <span>↗</span>
            </a>

            </section>



            <footer class="footer">
                <p>
                Designed and developed by yours truly with <b> Figma </b>
                and <b>Visual Studio Code.</b>  Created as a digital space
                to showcase multimedia production, visual storytelling,
                and creative technology projects.
                </p>

                <p>
                © 2026 Pradipta Adicandra Wicaksono
                </p>

            </footer>

        </div>

    </div>

    <div class="particles"></div>

</body>
</html>