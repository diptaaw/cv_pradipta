const spotlight = document.querySelector(".spotlight");

const cursor = document.querySelector(".custom-cursor");

document.addEventListener("mousemove", (e) => {

    cursor.style.left = `${e.clientX}px`;

    cursor.style.top = `${e.clientY}px`;

});

/* REVEAL ON SCROLL */

const reveals = document.querySelectorAll(".reveal");

window.addEventListener("scroll", () => {

    reveals.forEach((reveal) => {

        const windowHeight = window.innerHeight;

        const revealTop =
            reveal.getBoundingClientRect().top;

        if(revealTop < windowHeight - 100){

            reveal.classList.add("active");

        }

    });

});

/* ACTIVE NAVIGATION */

const sections = document.querySelectorAll("section");

const navLinks =
    document.querySelectorAll(".navigation a");

window.addEventListener("scroll", () => {

    let current = "";

    sections.forEach((section) => {

        const sectionTop =
            section.offsetTop;

        if(scrollY >= sectionTop - 200){

            current = section.getAttribute("id");

        }

    });

    navLinks.forEach((link) => {

        link.classList.remove("active");

        if(
            link.getAttribute("href")
            === `#${current}`
        ){
            link.classList.add("active");
        }

    });

});

const hoverItems = document.querySelectorAll(
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