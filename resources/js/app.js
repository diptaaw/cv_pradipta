const spotlight = document.querySelector(".spotlight");

const cursor = document.querySelector(".custom-cursor");



document.addEventListener("mousemove", (e) => {

    const x = e.clientX;

    const y = e.clientY;



    /* SPOTLIGHT */

    spotlight.style.left = `${x}px`;

    spotlight.style.top = `${y}px`;



    /* CURSOR */

    cursor.style.left = `${x}px`;

    cursor.style.top = `${y}px`;

});