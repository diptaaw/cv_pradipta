const spotlight =
    document.querySelector(".spotlight");

let mouseX = 0;
let mouseY = 0;

let currentX = 0;
let currentY = 0;

const cursor = document.querySelector(".custom-cursor");

/* MOUSE MOVE */

document.addEventListener("mousemove", (e) => {

    mouseX = e.clientX;
    mouseY = e.clientY;

    cursor.style.left = `${e.clientX}px`;
    cursor.style.top = `${e.clientY}px`;

});

/* REVEAL ON SCROLL */

const reveals =
    document.querySelectorAll(".reveal");

function revealSections() {

    reveals.forEach((reveal) => {

        const windowHeight =
            window.innerHeight;

        const revealTop =
            reveal.getBoundingClientRect().top;

        if (revealTop < windowHeight - 100) {

            reveal.classList.add("active");

        }

    });

}

window.addEventListener(
    "scroll",
    revealSections
);

revealSections();

/* ACTIVE NAVIGATION */

const sections =
    document.querySelectorAll("section");

const navLinks =
    document.querySelectorAll(".navigation a");

window.addEventListener("scroll", () => {

    let current = "";

    sections.forEach((section) => {

        const sectionTop =
            section.offsetTop;

        if (scrollY >= sectionTop - 200) {

            current =
                section.getAttribute("id");

        }

    });

    navLinks.forEach((link) => {

        link.classList.remove("active");

        if (
            link.getAttribute("href")
            === `#${current}`
        ) {

            link.classList.add("active");

        }

    });

});

/* CURSOR HOVER EFFECT */

const hoverItems =
    document.querySelectorAll(
        "a, .card, .tags span"
    );

hoverItems.forEach((item) => {

    item.addEventListener("mouseenter", () => {

        cursor.style.transform =
            "translate(-50%, -50%) scale(1.5)";

    });

    item.addEventListener("mouseleave", () => {

        cursor.style.transform =
            "translate(-50%, -50%) scale(1)";

    });

});

/* SPOTLIGHT ANIMATION */

function animateSpotlight() {

    currentX +=
        (mouseX - currentX) * 0.08;

    currentY +=
        (mouseY - currentY) * 0.08;

    spotlight.style.left =
        `${currentX}px`;

    spotlight.style.top =
        `${currentY}px`;

    requestAnimationFrame(
        animateSpotlight
    );

}

animateSpotlight();

const particlesContainer =
    document.querySelector(".particles");

const particles = [];

const PARTICLE_COUNT = 8;

for(let i = 0; i < PARTICLE_COUNT; i++){

    const particle =
        document.createElement("div");

    particle.classList.add("particle");

    particlesContainer.appendChild(
        particle
    );

    const obj = {

    el: particle,

    x:
        Math.random() * window.innerWidth,

    y:
        Math.random() * window.innerHeight,

    vx:
        (Math.random() - 0.5) * 0.3,

    vy:
        (Math.random() - 0.5) * 0.3,

    size:
        6 + Math.random() * 18,

    angle:
        Math.random() * Math.PI * 2,

    speed:
        0.002 + Math.random() * 0.003

    };

    particle.style.width =
        `${obj.size}px`;

    particle.style.height =
        `${obj.size}px`;

    particle.style.animationDuration =
        `${2 + Math.random() * 4}s`;

    particle.style.animationDelay =
        `${Math.random() * 5}s`;

    particles.push(obj);

    particle.style.opacity =
        0.3 + Math.random() * 0.7;

    particle.style.filter =
        `blur(${Math.random() * 2}px)`;


}

function animateParticles(){

    particles.forEach((p) => {

        const dx =
            p.x - mouseX;

        const dy =
            p.y - mouseY;

        const distance =
            Math.sqrt(dx * dx + dy * dy);
            p.angle += p.speed;
            p.vx += Math.cos(p.angle) * 0.015;
            p.vy += Math.sin(p.angle) * 0.015;
            p.vx += (Math.random() - 0.5) * 0.01;
            p.vy += (Math.random() - 0.5) * 0.01;
        /* MOUSE FEAR */

        if(distance < 180){

            const force =
                (180 - distance) / 180;

            p.vx +=
                (dx / distance) * force * 0.12;

            p.vy +=
                (dy / distance) * force * 0.12;

        }

        p.x += p.vx;
        p.y += p.vy;

        p.vx *= 0.985;
        p.vy *= 0.985;

        if(p.x < -100)
            p.x = window.innerWidth + 100;

        if(p.x > window.innerWidth + 100)
            p.x = -100;

        if(p.y < -100)
            p.y = window.innerHeight + 100;

        if(p.y > window.innerHeight + 100)
            p.y = -100;

        p.el.style.left = `${p.x}px`;
        p.el.style.top = `${p.y}px`;

    });

    requestAnimationFrame(
        animateParticles
    );

}

animateParticles();

/* NAVBAR COMPACT ON SCROLL */

const topNavbar =
    document.querySelector(".top-navbar");

const aboutSection =
    document.querySelector("#about");

let lastScrollY =
    window.scrollY;

window.addEventListener("scroll", () => {

    const triggerPoint =
        aboutSection.offsetTop + 0.0;

    if(window.scrollY > triggerPoint){

        topNavbar.classList.add(
            "compact"
        );

    }

    else{

        topNavbar.classList.remove(
            "compact"
        );

    }

    lastScrollY = window.scrollY;

});