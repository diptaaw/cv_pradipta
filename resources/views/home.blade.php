<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pradipta Portfolio</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="spotlight"></div>

    <div class="custom-cursor"></div>

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

                    <a href="#about">ABOUT</a>

                    <a href="#experience">EXPERIENCE</a>

                    <a href="#projects">PROJECTS</a>

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

            <section id="about" class="section">

                <p>
                    I'm a Multimedia Broadcasting student at PENS with a strong
                    interest in visual storytelling, creative production, and
                    digital media.
                </p>

                <br>

                <p>
                    I enjoy transforming ideas into engaging visual experiences
                    through photography, videography, live streaming, and design.
                </p>

            </section>



            <section id="experience" class="section">

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

            </section>



            <section id="projects" class="section">

                <div class="card">

                   <img
                    src="{{ asset('images/projects/wildlife.jpg') }}"
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

            </section>



            <footer class="footer">

                Designed and developed by yours truly with Figma
                and Visual Studio Code. Created as a digital space
                to showcase multimedia production, visual storytelling,
                and creative technology projects.

            </footer>

        </div>

    </div>

</body>
</html>