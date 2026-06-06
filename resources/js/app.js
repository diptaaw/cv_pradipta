const spotlight =
    document.querySelector(".spotlight");

let mouseX = 0;
let mouseY = 0;

let currentX = 0;
let currentY = 0;

const cursor = document.querySelector(".custom-cursor");

const themeToggle =
    document.querySelector("[data-theme-toggle]");

const savedTheme =
    localStorage.getItem("portfolio-theme");

if (savedTheme === "light") {
    document.body.dataset.theme = "light";
}

if (themeToggle) {
    themeToggle.setAttribute(
        "aria-pressed",
        document.body.dataset.theme === "light"
            ? "true"
            : "false"
    );

    themeToggle.addEventListener("click", () => {

        const isLight =
            document.body.dataset.theme === "light";

        if (isLight) {
            document.body.removeAttribute("data-theme");
            localStorage.setItem("portfolio-theme", "dark");
            themeToggle.setAttribute("aria-pressed", "false");
        } else {
            document.body.dataset.theme = "light";
            localStorage.setItem("portfolio-theme", "light");
            themeToggle.setAttribute("aria-pressed", "true");
        }

    });
}

/* MOUSE MOVE */

document.addEventListener("mousemove", (e) => {

    mouseX = e.clientX;
    mouseY = e.clientY;

    if (cursor) {
        cursor.style.left = `${e.clientX}px`;
        cursor.style.top = `${e.clientY}px`;
    }

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
        "a, button, .card, .tags span"
    );

hoverItems.forEach((item) => {

    item.addEventListener("mouseenter", () => {

        if (cursor) {
            cursor.style.transform =
                "translate(-50%, -50%) scale(1.5)";
        }

    });

    item.addEventListener("mouseleave", () => {

        if (cursor) {
            cursor.style.transform =
                "translate(-50%, -50%) scale(1)";
        }

    });

});

/* SPOTLIGHT ANIMATION */

function animateSpotlight() {

    currentX +=
        (mouseX - currentX) * 0.08;

    currentY +=
        (mouseY - currentY) * 0.08;

    if (spotlight) {
        spotlight.style.left =
            `${currentX}px`;

        spotlight.style.top =
            `${currentY}px`;
    }

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

    if (particlesContainer) {
        particlesContainer.appendChild(
            particle
        );
    }

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

/* RESPONSIVE NAVBAR - ZOOM & VIEWPORT DETECTION */

const layoutShell =
    document.querySelector(".layout-shell");

const topNavbar =
    document.querySelector(".top-navbar");

const rightPanel =
    document.querySelector(".right-panel");

let isInCompactMode = false;
let lastScrollY = 0;

// Detect if navbar should enter compact/adaptive mode
function shouldEnterCompactMode() {

    return false;

}

// Apply or remove compact-mode styling
function updateCompactModeState() {

    if (!topNavbar || !layoutShell) {
        return;
    }

    const needsCompact =
        shouldEnterCompactMode();

    if (needsCompact && !isInCompactMode) {

        topNavbar.classList.add(
            "navbar-compact-mode"
        );

        layoutShell.classList.add(
            "navbar-compact-mode"
        );

        // Start in reveal state
        topNavbar.classList.remove(
            "navbar-hidden"
        );

        topNavbar.classList.add(
            "navbar-reveal"
        );

        isInCompactMode = true;

    } else if (!needsCompact && isInCompactMode) {

        topNavbar.classList.remove(
            "navbar-compact-mode",
            "navbar-hidden",
            "navbar-reveal"
        );

        layoutShell.classList.remove(
            "navbar-compact-mode"
        );

        isInCompactMode = false;

    }

}

// Handle scroll reveal/hide for compact mode
function handleCompactModeScrollBehavior() {

    if (!isInCompactMode) {
        lastScrollY = window.scrollY;
        return;
    }

    const currentScrollY = window.scrollY;
    const scrollDelta = currentScrollY - lastScrollY;

    // Only trigger hide/reveal after scrolling past threshold
    if (Math.abs(scrollDelta) > 2) {

        if (scrollDelta > 0) {
            // Scrolling down - hide navbar
            topNavbar.classList.remove(
                "navbar-reveal"
            );

            topNavbar.classList.add(
                "navbar-hidden"
            );

        } else {
            // Scrolling up - reveal navbar
            topNavbar.classList.remove(
                "navbar-hidden"
            );

            topNavbar.classList.add(
                "navbar-reveal"
            );

        }

    }

    lastScrollY = currentScrollY;

}

// Monitor resize events for compact mode detection
window.addEventListener("resize", () => {

    updateCompactModeState();

}, { passive: true });

// Integrate scroll behavior with existing scroll listeners
const originalScrollHandler = window.onscroll;

window.addEventListener("scroll", () => {

    handleCompactModeScrollBehavior();

}, { passive: true });

// Use ResizeObserver for robust zoom/container detection
if (window.ResizeObserver) {

    const resizeObserver =
        new ResizeObserver(() => {

            updateCompactModeState();

        });

    resizeObserver.observe(
        document.documentElement
    );

}

// Fallback: Periodic zoom detection
let previousZoom = window.devicePixelRatio;

setInterval(() => {

    const currentZoom =
        window.devicePixelRatio;

    if (currentZoom !== previousZoom) {

        previousZoom = currentZoom;

        updateCompactModeState();

    }

}, 400);

// Initialize compact mode on page load
updateCompactModeState();

/* NAVBAR COMPACT ON COLLISION */

window.addEventListener("scroll", () => {

    if (!topNavbar || !rightPanel) {
        return;
    }

    const navbarRect =
        topNavbar.getBoundingClientRect();

    const rightPanelRect =
        rightPanel.getBoundingClientRect();

    /*
        cek apakah top right panel
        sudah menyentuh bawah navbar
        
        Skip collision compact if already
        in responsive compact mode
    */

    if (!isInCompactMode) {

        if(
            rightPanelRect.top
            <=
            navbarRect.bottom
        ){

            topNavbar.classList.add(
                "compact"
            );

        }

        else{

            topNavbar.classList.remove(
                "compact"
            );

        }

    }

});

/* ==========================================
   PREMIUM INTERACTIVE TYPOGRAPHY EXPERIENCE
   ========================================== */

const initInteractiveTypography = () => {
    const titleEl = document.querySelector(".name");
    if (!titleEl) return;

    // 1. Accessibility & Letter Splitting
    const rawText = titleEl.textContent.trim();
    titleEl.setAttribute("aria-label", rawText);
    
    // Split text into words and then characters
    const words = rawText.split(/\s+/);
    titleEl.innerHTML = ""; // Clear original text

    const charObjects = [];
    let activeDragChar = null;
    let grabOffsetX = 0;
    let grabOffsetY = 0;

    // Pointer move / drag handlers
    const onDragMove = (e) => {
        if (!activeDragChar) return;
        
        let clientX = 0;
        let clientY = 0;

        if (e.type.startsWith("touch")) {
            clientX = e.touches[0].pageX;
            clientY = e.touches[0].pageY;
            if (e.cancelable) {
                e.preventDefault();
            }
        } else {
            clientX = e.pageX;
            clientY = e.pageY;
        }

        // Set pointer coordinates target so spring physics handles the lag follow
        activeDragChar.dragTargetX = clientX - activeDragChar.origX - grabOffsetX;
        activeDragChar.dragTargetY = clientY - activeDragChar.origY - grabOffsetY;
        
        startLoop();
    };

    const onDragEnd = () => {
        if (!activeDragChar) return;

        const char = activeDragChar;
        char.state = "released";
        char.releaseTime = performance.now();
        char.el.classList.remove("is-dragging");
        
        if (!char.isHovered) {
            char.el.classList.remove("is-hovered");
        }

        activeDragChar = null;
        document.body.style.cursor = "";

        // Remove window-level handlers
        window.removeEventListener("mousemove", onDragMove);
        window.removeEventListener("touchmove", onDragMove);
        window.removeEventListener("mouseup", onDragEnd);
        window.removeEventListener("touchend", onDragEnd);
        window.removeEventListener("touchcancel", onDragEnd);
    };

    words.forEach((wordText, wordIdx) => {
        const wordSpan = document.createElement("span");
        wordSpan.classList.add("word");
        wordSpan.setAttribute("aria-hidden", "true");

        const chars = Array.from(wordText);
        chars.forEach((char) => {
            const charSpan = document.createElement("span");
            charSpan.classList.add("char");
            charSpan.textContent = char;
            wordSpan.appendChild(charSpan);

            const charObj = {
                el: charSpan,
                x: 0, 
                y: 0,
                rx: 0,
                ry: 0,
                rz: 0,
                scale: 1.0,
                vx: 0, 
                vy: 0,
                vrx: 0,
                vry: 0,
                vrz: 0,
                vs: 0,
                tx: 0, 
                ty: 0,
                trx: 0,
                try: 0,
                trz: 0,
                ts: 1.0,
                dragTargetX: 0,
                dragTargetY: 0,
                isHovered: false,
                state: "idle", // "idle" | "hovered" | "dragging" | "released" | "returning"
                releaseTime: 0,
                origX: 0,
                origY: 0
            };

            charSpan.style.transform = `translate3d(0px, 0px, 0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale(1)`;

            // Hover state tracking
            charSpan.addEventListener("mouseenter", () => {
                charObj.isHovered = true;
                charSpan.classList.add("is-hovered");
                startLoop();
            });

            charSpan.addEventListener("mouseleave", () => {
                charObj.isHovered = false;
                if (charObj.state !== "dragging") {
                    charSpan.classList.remove("is-hovered");
                }
                startLoop();
            });

            // Drag state tracking
            const onDragStart = (e) => {
                let clientX = 0;
                let clientY = 0;

                if (e.type.startsWith("touch")) {
                    clientX = e.touches[0].pageX;
                    clientY = e.touches[0].pageY;
                } else {
                    if (e.button !== 0) return; // Left click only
                    clientX = e.pageX;
                    clientY = e.pageY;
                }

                activeDragChar = charObj;
                charObj.state = "dragging";
                charObj.vx = 0;
                charObj.vy = 0;
                
                charSpan.classList.add("is-dragging");
                charSpan.classList.add("is-hovered");

                // Calculate grab offset relative to natural location + offset
                grabOffsetX = clientX - charObj.origX - charObj.x;
                grabOffsetY = clientY - charObj.origY - charObj.y;

                charObj.dragTargetX = charObj.x;
                charObj.dragTargetY = charObj.y;

                e.preventDefault();
                document.body.style.cursor = "grabbing";
                startLoop();

                // Dynamic document handlers to follow pointer anywhere
                window.addEventListener("mousemove", onDragMove);
                window.addEventListener("touchmove", onDragMove, { passive: false });
                window.addEventListener("mouseup", onDragEnd);
                window.addEventListener("touchend", onDragEnd);
                window.addEventListener("touchcancel", onDragEnd);
            };

            charSpan.addEventListener("mousedown", onDragStart);
            charSpan.addEventListener("touchstart", onDragStart, { passive: false });

            charObjects.push(charObj);
        });

        titleEl.appendChild(wordSpan);

        if (wordIdx < words.length - 1) {
            titleEl.appendChild(document.createTextNode(" "));
        }
    });

    // 2. Absolute coordinates caching
    const cacheCharPositions = () => {
        // Clear transforms temporarily to get clean layout positions
        const currentTransforms = charObjects.map(char => char.el.style.transform);
        charObjects.forEach(char => {
            char.el.style.transform = "none";
        });

        // Compute natural coordinates
        charObjects.forEach((char) => {
            const rect = char.el.getBoundingClientRect();
            char.origX = rect.left + window.scrollX;
            char.origY = rect.top + window.scrollY;
        });

        // Restore transforms
        charObjects.forEach((char, idx) => {
            char.el.style.transform = currentTransforms[idx];
        });
    };

    // Cache initial layout coordinates
    cacheCharPositions();
    window.addEventListener("resize", cacheCharPositions);

    // Re-cache once fonts load to ensure layout dimensions are accurate
    if (document.fonts) {
        document.fonts.ready.then(cacheCharPositions);
    }

    // Helper utility to clamp value
    const clamp = (val, min, max) => Math.max(min, Math.min(max, val));

    // 3. Physics Animation Loop (On-Demand)
    let loopRunning = false;

    const runPhysicsLoop = () => {
        let anyActive = false;
        const now = performance.now();

        charObjects.forEach((char) => {
            if (char.state === "dragging") {
                anyActive = true;
                char.ts = 1.05;

                // Soft low-gravity lag spring tracking the mouse
                const dragK = 0.07; // low stiffness
                const dragDamping = 0.82; // gentle momentum decay

                const fx = (char.dragTargetX - char.x) * dragK;
                char.vx = (char.vx + fx) * dragDamping;
                char.x += char.vx;

                const fy = (char.dragTargetY - char.y) * dragK;
                char.vy = (char.vy + fy) * dragDamping;
                char.y += char.vy;

                // 3D rotation tilt targets based on drag velocities (clamped to ±20deg)
                char.trx = clamp(-char.vy * 0.9, -20, 20);
                char.try = clamp(char.vx * 0.9, -20, 20);
                char.trz = clamp(char.vx * 0.6, -18, 18);

                // Wind Physics (Aerodynamic Flutter turbulence when fast)
                const speed = Math.sqrt(char.vx * char.vx + char.vy * char.vy);
                if (speed > 1.5) {
                    const flutterFrequency = 0.05;
                    const flutterFactor = clamp(speed * 0.4, 0, 4);
                    char.trz += Math.sin(now * flutterFrequency) * flutterFactor;
                    char.trx += Math.cos(now * flutterFrequency) * (flutterFactor * 0.5);
                }

            } else if (char.state === "released") {
                anyActive = true;
                char.ts = 1.05;

                // Moon Gravity: float and slide briefly with momentum (slow decay)
                char.vx *= 0.95;
                char.vy *= 0.95;
                char.x += char.vx;
                char.y += char.vy;

                char.trx = 0;
                char.try = 0;
                char.trz = 0;

                // Check 1.5s release delay hold
                if (now - char.releaseTime >= 1500) {
                    char.state = "returning";
                }

            } else if (char.state === "returning") {
                anyActive = true;
                char.ts = char.isHovered ? 1.05 : 1.0;
                
                // Spring return parameters (premium underdamped overshoot)
                const returnK = 0.024; // soft spring pull
                const returnDamping = 0.90; // underdamped decay to allow overshoot

                const fx = (0 - char.x) * returnK;
                char.vx = (char.vx + fx) * returnDamping;
                char.x += char.vx;

                const fy = (0 - char.y) * returnK;
                char.vy = (char.vy + fy) * returnDamping;
                char.y += char.vy;

                char.trx = 0;
                char.try = 0;
                char.trz = 0;

                // Snap element to origin once close enough and settled
                if (Math.abs(char.x) < 0.08 && Math.abs(char.vx) < 0.005 &&
                    Math.abs(char.y) < 0.08 && Math.abs(char.vy) < 0.005) {
                    char.x = 0;
                    char.y = 0;
                    char.vx = 0;
                    char.vy = 0;
                    char.state = char.isHovered ? "hovered" : "idle";
                }

            } else {
                // state is idle or hovered (hover spring transition)
                char.ts = char.isHovered ? 1.05 : 1.0;
                char.tx = 0;
                char.ty = char.isHovered ? -3 : 0; // slight lift (-3px)
                char.trx = 0;
                char.try = 0;
                char.trz = 0;

                const hoverK = 0.07; // luxuriously slow hover spring (~400ms duration feel)
                const hoverDamping = 0.84;

                const fx = (char.tx - char.x) * hoverK;
                char.vx = (char.vx + fx) * hoverDamping;
                char.x += char.vx;

                const fy = (char.ty - char.y) * hoverK;
                char.vy = (char.vy + fy) * hoverDamping;
                char.y += char.vy;

                // Keep loop alive if element is still settling
                if (Math.abs(char.x - char.tx) > 0.01 || Math.abs(char.vx) > 0.001 ||
                    Math.abs(char.y - char.ty) > 0.01 || Math.abs(char.vy) > 0.001) {
                    anyActive = true;
                }
            }

            // Animate scale transitions
            const scaleK = 0.1;
            const scaleDamping = 0.85;
            const fs = (char.ts - char.scale) * scaleK;
            char.vs = (char.vs + fs) * scaleDamping;
            char.scale += char.vs;

            if (Math.abs(char.scale - char.ts) > 0.001 || Math.abs(char.vs) > 0.0001) {
                anyActive = true;
            }

            // Animate 3D Rotations (Smooth organic catch-up)
            const rotK = 0.06;
            const rotDamping = 0.85;

            char.vrx = (char.vrx + (char.trx - char.rx) * rotK) * rotDamping;
            char.rx += char.vrx;
            char.vry = (char.vry + (char.try - char.ry) * rotK) * rotDamping;
            char.ry += char.vry;
            char.vrz = (char.vrz + (char.trz - char.rz) * rotK) * rotDamping;
            char.rz += char.vrz;

            if (Math.abs(char.rx - char.trx) > 0.01 || Math.abs(char.vrx) > 0.001 ||
                Math.abs(char.ry - char.try) > 0.01 || Math.abs(char.vry) > 0.001 ||
                Math.abs(char.rz - char.trz) > 0.01 || Math.abs(char.vrz) > 0.001) {
                anyActive = true;
            }

            // Write properties to CSS transform
            char.el.style.transform = `translate3d(${char.x.toFixed(2)}px, ${char.y.toFixed(2)}px, 0px) rotateX(${char.rx.toFixed(2)}deg) rotateY(${char.ry.toFixed(2)}deg) rotateZ(${char.rz.toFixed(2)}deg) scale(${char.scale.toFixed(3)})`;
        });

        if (anyActive) {
            requestAnimationFrame(runPhysicsLoop);
        } else {
            loopRunning = false;
        }
    };

    const startLoop = () => {
        if (!loopRunning) {
            loopRunning = true;
            runPhysicsLoop();
        }
    };
};

// Initialize interactive typography after content is fully loaded
if (document.readyState === "complete") {
    initInteractiveTypography();
} else {
    window.addEventListener("load", initInteractiveTypography);
}
