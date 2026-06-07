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
    const threshold = 140; // Viewport trigger line (pixels from top)

    sections.forEach((section) => {
        const rect = section.getBoundingClientRect();
        if (rect.top <= threshold && rect.bottom >= threshold) {
            current = section.getAttribute("id");
        }
    });

    // Fallback for reaching the absolute bottom of the page
    if ((window.innerHeight + window.scrollY) >= document.documentElement.scrollHeight - 50) {
        if (sections.length > 0) {
            current = sections[sections.length - 1].getAttribute("id");
        }
    }

    if (current) {
        navLinks.forEach((link) => {
            link.classList.remove("active");
            if (
                link.getAttribute("href")
                === `#${current}`
            ) {
                link.classList.add("active");
            }
        });
    }

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

// Upgraded Canvas-based Cosmic Background System
const particlesContainer = document.querySelector(".particles");

if (particlesContainer) {
    let particlesOverlayContainer = document.querySelector(".particles-overlay");
    if (!particlesOverlayContainer) {
        particlesOverlayContainer = document.createElement("div");
        particlesOverlayContainer.classList.add("particles", "particles-overlay");
        particlesOverlayContainer.style.zIndex = "15";
        particlesOverlayContainer.style.pointerEvents = "none";
        document.body.appendChild(particlesOverlayContainer);
    }

    // Append Canvas elements
    const bgCanvas = document.createElement("canvas");
    bgCanvas.id = "constellation-bg";
    particlesContainer.appendChild(bgCanvas);
    const bgCtx = bgCanvas.getContext("2d");

    const fgCanvas = document.createElement("canvas");
    fgCanvas.id = "constellation-fg";
    particlesOverlayContainer.appendChild(fgCanvas);
    const fgCtx = fgCanvas.getContext("2d");

    document.body.classList.add("canvas-active");

    // Dimensions
    let width = window.innerWidth;
    let height = window.innerHeight;

    let isMobileDevice = width <= 768;
    let isTabletDevice = width > 768 && width <= 1024;

    // Canvas scaling
    function resizeCanvases() {
        width = window.innerWidth;
        height = window.innerHeight;
        isMobileDevice = width <= 768;
        isTabletDevice = width > 768 && width <= 1024;

        const dpr = Math.min(window.devicePixelRatio || 1, 1.5);

        bgCanvas.width = width * dpr;
        bgCanvas.height = height * dpr;
        bgCanvas.style.width = `${width}px`;
        bgCanvas.style.height = `${height}px`;
        bgCtx.resetTransform();
        bgCtx.scale(dpr, dpr);

        fgCanvas.width = width * dpr;
        fgCanvas.height = height * dpr;
        fgCanvas.style.width = `${width}px`;
        fgCanvas.style.height = `${height}px`;
        fgCtx.resetTransform();
        fgCtx.scale(dpr, dpr);
    }
    window.addEventListener("resize", resizeCanvases);
    resizeCanvases();

    // Particle pools configuration
    const smallStarCount = isMobileDevice ? 25 : (isTabletDevice ? 45 : 65);
    const medStarCount = isMobileDevice ? 12 : (isTabletDevice ? 22 : 30);
    const constellationCount = isMobileDevice ? 16 : (isTabletDevice ? 26 : 38);
    const fireflyCount = isMobileDevice ? 8 : (isTabletDevice ? 14 : 20);
    const foregroundCount = isMobileDevice ? 4 : (isTabletDevice ? 7 : 10);

    // Coordinate state tracking
    let smoothMouseOffsetX = 0;
    let smoothMouseOffsetY = 0;
    let interpolatedScrollY = window.scrollY;
    let lastScrollY = window.scrollY;
    let scrollVelocity = 0;

    // Small stars
    const smallStars = [];
    for (let i = 0; i < smallStarCount; i++) {
        smallStars.push({
            baseX: Math.random() * width,
            baseY: Math.random() * height,
            size: 0.6 + Math.random() * 0.9,
            twinklePhase: Math.random() * Math.PI * 2,
            twinkleSpeed: 0.003 + Math.random() * 0.007,
            baseOpacity: 0.25 + Math.random() * 0.5,
            parallaxFactor: 0.08 + Math.random() * 0.06
        });
    }

    // Medium stars
    const medStars = [];
    for (let i = 0; i < medStarCount; i++) {
        medStars.push({
            baseX: Math.random() * width,
            baseY: Math.random() * height,
            size: 1.5 + Math.random() * 1.2,
            twinklePhase: Math.random() * Math.PI * 2,
            twinkleSpeed: 0.002 + Math.random() * 0.005,
            baseOpacity: 0.4 + Math.random() * 0.45,
            parallaxFactor: 0.14 + Math.random() * 0.1
        });
    }

    // Constellation stars
    const constellationStars = [];
    for (let i = 0; i < constellationCount; i++) {
        constellationStars.push({
            baseX: Math.random() * width,
            baseY: Math.random() * height,
            vx: (Math.random() - 0.5) * 0.07,
            vy: (Math.random() - 0.5) * 0.07,
            size: 1.2 + Math.random() * 1.5,
            baseOpacity: 0.35 + Math.random() * 0.35,
            brightness: 1.0,
            brightnessTarget: 1.0,
            brighteningTimer: 0,
            breathePhase: Math.random() * Math.PI * 2,
            breatheSpeed: 0.002 + Math.random() * 0.005,
            parallaxFactor: 0.12
        });
    }

    // Nebula morphing blobs
    const nebulaBlobs = [
        { baseXFraction: 0.15, baseYFraction: 0.25, radiusFraction: 0.55, colorIndex: 0, angleX: Math.random() * 10, angleY: Math.random() * 10, speedX: 0.0004, speedY: 0.0002 },
        { baseXFraction: 0.85, baseYFraction: 0.75, radiusFraction: 0.50, colorIndex: 1, angleX: Math.random() * 10, angleY: Math.random() * 10, speedX: 0.0002, speedY: 0.0004 },
        { baseXFraction: 0.50, baseYFraction: 0.55, radiusFraction: 0.60, colorIndex: 2, angleX: Math.random() * 10, angleY: Math.random() * 10, speedX: 0.0003, speedY: 0.0003 },
        { baseXFraction: 0.75, baseYFraction: 0.20, radiusFraction: 0.45, colorIndex: 3, angleX: Math.random() * 10, angleY: Math.random() * 10, speedX: 0.0005, speedY: 0.0001 }
    ];

    // Fireflies
    const fireflies = [];
    for (let i = 0; i < fireflyCount; i++) {
        const size = 6 + Math.random() * 12;
        const opacity = 0.35 + Math.random() * 0.45;
        const speedMult = 0.4 + Math.random() * 0.4;
        fireflies.push({
            baseX: Math.random() * width,
            baseY: Math.random() * height,
            vx: (Math.random() - 0.5) * 0.3 * speedMult,
            vy: (Math.random() - 0.5) * 0.3 * speedMult,
            targetVx: 0,
            targetVy: 0,
            size: size,
            baseOpacity: opacity,
            currentOpacity: opacity,
            angle: Math.random() * Math.PI * 2,
            speed: (0.0015 + Math.random() * 0.002) * speedMult,
            wobbleSeed: Math.random() * 1000,
            wobbleSpeed: 0.003 + Math.random() * 0.007,
            parallaxFactor: 0.22,
            speedMult: speedMult,
            colorIndex: Math.floor(Math.random() * 4)
        });
    }

    // Foreground large blurred particles
    const foregroundParticles = [];
    for (let i = 0; i < foregroundCount; i++) {
        const size = 70 + Math.random() * 55;
        const opacity = 0.12 + Math.random() * 0.18;
        foregroundParticles.push({
            baseX: Math.random() * width,
            baseY: Math.random() * height,
            vx: (Math.random() - 0.5) * 0.08,
            vy: (Math.random() - 0.5) * 0.08,
            size: size,
            baseOpacity: opacity,
            currentOpacity: opacity,
            angle: Math.random() * Math.PI * 2,
            speed: 0.0002 + Math.random() * 0.0005,
            parallaxFactor: 0.55
        });
    }

    // Main animation loop
    function animateConstellations() {
        // Theme selection
        const isLight = document.body.dataset.theme === "light";

        // Parallax calculations
        const targetOffsetX = mouseX - width / 2;
        const targetOffsetY = mouseY - height / 2;
        smoothMouseOffsetX += (targetOffsetX - smoothMouseOffsetX) * 0.05;
        smoothMouseOffsetY += (targetOffsetY - smoothMouseOffsetY) * 0.05;

        // Scroll velocity computations
        interpolatedScrollY += (window.scrollY - interpolatedScrollY) * 0.08;
        const scrollDelta = Math.abs(window.scrollY - lastScrollY);
        scrollVelocity += scrollDelta * 0.1;
        scrollVelocity = Math.min(scrollVelocity, 12);
        scrollVelocity *= 0.94;
        lastScrollY = window.scrollY;

        // Clear canvas states
        bgCtx.clearRect(0, 0, width, height);
        fgCtx.clearRect(0, 0, width, height);

        // Fill background color
        bgCtx.fillStyle = isLight ? "#f4f0ff" : "#05050a";
        bgCtx.fillRect(0, 0, width, height);

        // --- LAYER 1: NEBULA SYSTEM ---
        nebulaBlobs.forEach((blob) => {
            // Speed accelerates during scroll
            blob.angleX += blob.speedX * (1.0 + scrollVelocity * 0.25);
            blob.angleY += blob.speedY * (1.0 + scrollVelocity * 0.25);

            const driftX = Math.sin(blob.angleX) * (width * 0.08);
            const driftY = Math.cos(blob.angleY) * (height * 0.08);

            const scrollShiftY = -interpolatedScrollY * 0.05;

            let cx = (width * blob.baseXFraction + driftX + smoothMouseOffsetX * 0.02) % width;
            if (cx < 0) cx += width;
            let cy = (height * blob.baseYFraction + driftY + scrollShiftY + smoothMouseOffsetY * 0.02) % height;
            if (cy < 0) cy += height;

            const radius = Math.max(width, height) * blob.radiusFraction;
            let grad = bgCtx.createRadialGradient(cx, cy, 0, cx, cy, radius);

            const baseOp = isLight ? 0.032 : 0.085;

            if (isLight) {
                if (blob.colorIndex === 0) {
                    grad.addColorStop(0, `rgba(109, 80, 255, ${baseOp * 0.75})`);
                    grad.addColorStop(0.5, `rgba(109, 80, 255, ${baseOp * 0.25})`);
                } else if (blob.colorIndex === 1) {
                    grad.addColorStop(0, `rgba(166, 92, 255, ${baseOp * 0.65})`);
                    grad.addColorStop(0.5, `rgba(166, 92, 255, ${baseOp * 0.2})`);
                } else if (blob.colorIndex === 2) {
                    grad.addColorStop(0, `rgba(77, 110, 255, ${baseOp * 0.55})`);
                    grad.addColorStop(0.5, `rgba(77, 110, 255, ${baseOp * 0.15})`);
                } else {
                    grad.addColorStop(0, `rgba(224, 82, 255, ${baseOp * 0.3})`);
                    grad.addColorStop(0.5, `rgba(224, 82, 255, ${baseOp * 0.08})`);
                }
            } else {
                if (blob.colorIndex === 0) {
                    grad.addColorStop(0, `rgba(109, 80, 255, ${baseOp})`);
                    grad.addColorStop(0.5, `rgba(109, 80, 255, ${baseOp * 0.3})`);
                } else if (blob.colorIndex === 1) {
                    grad.addColorStop(0, `rgba(137, 58, 255, ${baseOp * 0.95})`);
                    grad.addColorStop(0.5, `rgba(137, 58, 255, ${baseOp * 0.25})`);
                } else if (blob.colorIndex === 2) {
                    grad.addColorStop(0, `rgba(70, 40, 180, ${baseOp * 0.8})`);
                    grad.addColorStop(0.5, `rgba(70, 40, 180, ${baseOp * 0.2})`);
                } else {
                    grad.addColorStop(0, `rgba(224, 82, 255, ${baseOp * 0.65})`);
                    grad.addColorStop(0.5, `rgba(224, 82, 255, ${baseOp * 0.15})`);
                }
            }
            grad.addColorStop(1, "transparent");

            bgCtx.fillStyle = grad;
            bgCtx.beginPath();
            bgCtx.arc(cx, cy, radius, 0, Math.PI * 2);
            bgCtx.fill();
        });

        // --- LAYER 3 & 4: TWINKLING STARS ---
        smallStars.forEach((star) => {
            let drawX = (star.baseX + smoothMouseOffsetX * star.parallaxFactor) % width;
            if (drawX < 0) drawX += width;
            let drawY = (star.baseY + smoothMouseOffsetY * star.parallaxFactor - interpolatedScrollY * star.parallaxFactor) % height;
            if (drawY < 0) drawY += height;

            star.twinklePhase += star.twinkleSpeed;
            const currentOp = star.baseOpacity * (0.15 + 0.85 * (0.5 + 0.5 * Math.sin(star.twinklePhase)));

            bgCtx.beginPath();
            bgCtx.fillStyle = isLight
                ? `rgba(109, 76, 255, ${currentOp * 0.22})`
                : `rgba(230, 220, 255, ${currentOp * 0.45})`;
            bgCtx.arc(drawX, drawY, star.size, 0, Math.PI * 2);
            bgCtx.fill();
        });

        medStars.forEach((star) => {
            let drawX = (star.baseX + smoothMouseOffsetX * star.parallaxFactor) % width;
            if (drawX < 0) drawX += width;
            let drawY = (star.baseY + smoothMouseOffsetY * star.parallaxFactor - interpolatedScrollY * star.parallaxFactor) % height;
            if (drawY < 0) drawY += height;

            star.twinklePhase += star.twinkleSpeed;
            const currentOp = star.baseOpacity * (0.15 + 0.85 * (0.5 + 0.5 * Math.sin(star.twinklePhase)));

            bgCtx.beginPath();
            bgCtx.fillStyle = isLight
                ? `rgba(109, 76, 255, ${currentOp * 0.28})`
                : `rgba(230, 220, 255, ${currentOp * 0.55})`;
            bgCtx.arc(drawX, drawY, star.size, 0, Math.PI * 2);
            bgCtx.fill();
        });

        // --- LAYER 2: PROCEDURAL CONSTELLATIONS ---
        constellationStars.forEach((star) => {
            star.baseX += star.vx * (1.0 + scrollVelocity * 0.1);
            star.baseY += star.vy * (1.0 + scrollVelocity * 0.1);

            // Wrap bounds
            if (star.baseX < 0) star.baseX += width;
            if (star.baseX > width) star.baseX -= width;
            if (star.baseY < 0) star.baseY += height;
            if (star.baseY > height) star.baseY -= height;

            // Occasional gentle brightening cycle
            if (star.brighteningTimer > 0) {
                star.brighteningTimer--;
                if (star.brighteningTimer === 0) star.brightnessTarget = 1.0;
            } else {
                if (Math.random() < 0.0001) {
                    star.brightnessTarget = 2.0 + Math.random() * 1.5;
                    star.brighteningTimer = 180 + Math.floor(Math.random() * 120);
                }
            }
        });

        // Loop to connect constellation stars
        for (let i = 0; i < constellationStars.length; i++) {
            const starA = constellationStars[i];
            let ax = (starA.baseX + smoothMouseOffsetX * starA.parallaxFactor) % width;
            if (ax < 0) ax += width;
            let ay = (starA.baseY + smoothMouseOffsetY * starA.parallaxFactor - interpolatedScrollY * starA.parallaxFactor) % height;
            if (ay < 0) ay += height;

            // Update interactive cursor hover state
            const dx = ax - mouseX;
            const dy = ay - mouseY;
            const distToMouse = Math.sqrt(dx * dx + dy * dy);
            let hoverFactor = 0;
            if (distToMouse < 180) {
                hoverFactor = Math.pow((180 - distToMouse) / 180, 1.5);
            }
            starA.breathePhase += starA.breatheSpeed;
            const breatheAlpha = 0.75 + 0.25 * Math.sin(starA.breathePhase);

            starA.brightness += (starA.brightnessTarget + hoverFactor * 1.8 - starA.brightness) * 0.08;

            for (let j = i + 1; j < constellationStars.length; j++) {
                const starB = constellationStars[j];
                let bx = (starB.baseX + smoothMouseOffsetX * starB.parallaxFactor) % width;
                if (bx < 0) bx += width;
                let by = (starB.baseY + smoothMouseOffsetY * starB.parallaxFactor - interpolatedScrollY * starB.parallaxFactor) % height;
                if (by < 0) by += height;

                const lineDx = ax - bx;
                const lineDy = ay - by;
                const lineDist = Math.sqrt(lineDx * lineDx + lineDy * lineDy);

                if (lineDist < 140) {
                    const minBrightness = Math.min(starA.brightness, starB.brightness);
                    const ratio = 1 - lineDist / 140;
                    const lineOpacity = ratio * (isLight ? 0.035 : 0.12) * minBrightness * breatheAlpha;

                    bgCtx.beginPath();
                    bgCtx.strokeStyle = isLight
                        ? `rgba(109, 76, 255, ${lineOpacity})`
                        : `rgba(220, 215, 255, ${lineOpacity})`;
                    bgCtx.lineWidth = 0.55;
                    bgCtx.moveTo(ax, ay);
                    bgCtx.lineTo(bx, by);
                    bgCtx.stroke();
                }
            }

            // Draw constellation star
            bgCtx.beginPath();
            const radius = starA.size * (0.85 + 0.15 * Math.sin(starA.breathePhase));
            const starOpacity = (isLight ? 0.095 : 0.24) * starA.brightness * breatheAlpha;
            bgCtx.fillStyle = isLight
                ? `rgba(109, 76, 255, ${starOpacity})`
                : `rgba(235, 230, 255, ${starOpacity})`;

            bgCtx.arc(ax, ay, radius, 0, Math.PI * 2);
            bgCtx.fill();

            // Hover glow ring
            if (starA.brightness > 1.25) {
                bgCtx.beginPath();
                bgCtx.fillStyle = isLight
                    ? `rgba(109, 76, 255, ${starOpacity * 0.26})`
                    : `rgba(169, 150, 255, ${starOpacity * 0.32})`;
                bgCtx.arc(ax, ay, radius * 3.2, 0, Math.PI * 2);
                bgCtx.fill();
            }
        }

        // --- LAYER 5: Drifting FIREFLIES ---
        fireflies.forEach((ff) => {
            ff.angle += ff.speed;
            ff.wobbleSeed += ff.wobbleSpeed;

            const windX = Math.sin(ff.wobbleSeed) * 0.12 * ff.speedMult;
            const windY = Math.cos(ff.wobbleSeed * 0.8) * 0.12 * ff.speedMult;

            ff.targetVx = Math.cos(ff.angle) * 0.18 * ff.speedMult + windX;
            ff.targetVy = Math.sin(ff.angle) * 0.18 * ff.speedMult + windY;

            let fx = (ff.baseX + smoothMouseOffsetX * ff.parallaxFactor) % width;
            if (fx < 0) fx += width;
            let fy = (ff.baseY + smoothMouseOffsetY * ff.parallaxFactor - interpolatedScrollY * ff.parallaxFactor) % height;
            if (fy < 0) fy += height;

            // Cursor repelling
            const dx = fx - mouseX;
            const dy = fy - mouseY;
            const dist = Math.sqrt(dx * dx + dy * dy);

            if (dist < 180) {
                const force = Math.pow((180 - dist) / 180, 2);
                ff.targetVx += (dx / dist) * force * 0.45 * ff.speedMult;
                ff.targetVy += (dy / dist) * force * 0.45 * ff.speedMult;
            }

            ff.vx += (ff.targetVx - ff.vx) * 0.03;
            ff.vy += (ff.targetVy - ff.vy) * 0.03;

            ff.baseX += ff.vx;
            ff.baseY += ff.vy;

            // Wrap boundaries
            if (ff.baseX < -50) ff.baseX = width + 50;
            if (ff.baseX > width + 50) ff.baseX = -50;
            if (ff.baseY < -50) ff.baseY = height + 50;
            if (ff.baseY > height + 50) ff.baseY = -50;

            const baseOp = isLight ? 0.22 : 0.65;
            const fireflyOpacity = ff.baseOpacity * baseOp;

            let color;
            if (isLight) {
                color = { r: 109, g: 76, b: 255 };
            } else {
                const FIREFLY_PALETTE_DARK = [
                    { r: 108, g: 77, b: 255 },
                    { r: 139, g: 92, b: 255 },
                    { r: 167, g: 123, b: 255 },
                    { r: 198, g: 107, b: 255 }
                ];
                color = FIREFLY_PALETTE_DARK[ff.colorIndex];
            }

            let grad = bgCtx.createRadialGradient(fx, fy, 0, fx, fy, ff.size * 1.5);
            grad.addColorStop(0, `rgba(${color.r}, ${color.g}, ${color.b}, ${fireflyOpacity})`);
            grad.addColorStop(0.3, `rgba(${color.r}, ${color.g}, ${color.b}, ${fireflyOpacity * 0.4})`);
            grad.addColorStop(1, "transparent");

            bgCtx.fillStyle = grad;
            bgCtx.beginPath();
            bgCtx.arc(fx, fy, ff.size * 1.5, 0, Math.PI * 2);
            bgCtx.fill();
        });

        // --- LAYER 6: FOREGROUND BLURRED PARTICLES (Foreground Canvas) ---
        fgCtx.save();
        fgCtx.globalCompositeOperation = isLight ? "multiply" : "screen";

        foregroundParticles.forEach((p) => {
            p.angle += p.speed;
            p.baseX += p.vx + Math.sin(p.angle) * 0.03;
            p.baseY += p.vy + Math.cos(p.angle) * 0.03;

            // Wrap boundaries
            if (p.baseX < -150) p.baseX = width + 150;
            if (p.baseX > width + 150) p.baseX = -150;
            if (p.baseY < -150) p.baseY = height + 150;
            if (p.baseY > height + 150) p.baseY = -150;

            let finalX = (p.baseX + smoothMouseOffsetX * p.parallaxFactor) % width;
            if (finalX < 0) finalX += width;
            let finalY = (p.baseY + smoothMouseOffsetY * p.parallaxFactor - interpolatedScrollY * p.parallaxFactor) % height;
            if (finalY < 0) finalY += height;

            const baseOp = isLight ? 0.015 : 0.055;

            let grad = fgCtx.createRadialGradient(finalX, finalY, 0, finalX, finalY, p.size);
            if (isLight) {
                grad.addColorStop(0, `rgba(109, 76, 255, ${baseOp})`);
                grad.addColorStop(0.5, `rgba(109, 76, 255, ${baseOp * 0.3})`);
                grad.addColorStop(1, "transparent");
            } else {
                grad.addColorStop(0, `rgba(169, 150, 255, ${baseOp})`);
                grad.addColorStop(0.5, `rgba(169, 150, 255, ${baseOp * 0.3})`);
                grad.addColorStop(1, "transparent");
            }

            fgCtx.fillStyle = grad;
            fgCtx.beginPath();
            fgCtx.arc(finalX, finalY, p.size, 0, Math.PI * 2);
            fgCtx.fill();
        });
        fgCtx.restore();

        requestAnimationFrame(animateConstellations);
    }

    animateConstellations();
}

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

// Enhance Tag Chip Interactions (Search Google on Click/Keyboard, Accessibility Roles)
const initTagChips = () => {
    const setupChip = (chip) => {
        if (chip.dataset.tagInitialized) return;
        chip.dataset.tagInitialized = "true";

        const tagName = chip.textContent.trim();
        chip.setAttribute("role", "button");
        chip.setAttribute("tabindex", "0");
        chip.setAttribute("aria-label", `Search Google for ${tagName}`);

        // Set randomized timing, delay, intensity, and direction variables for premium ambient shine
        const duration = (5.5 + Math.random() * 1.5).toFixed(2); // 5.5s - 7.0s
        const delay = (-Math.random() * 7).toFixed(2); // Negative delay starts the loop immediately in a random phase
        const angle = (10 + Math.random() * 15).toFixed(1); // 10deg - 25deg
        const baseOpacity = (0.6 + Math.random() * 0.4).toFixed(2); // Randomized intensity/opacity

        chip.style.setProperty('--shine-duration', `${duration}s`);
        chip.style.setProperty('--shine-delay', `${delay}s`);
        chip.style.setProperty('--shine-angle', `${angle}deg`);
        chip.style.setProperty('--shine-opacity', baseOpacity);

        // Physical mouse-based 3D tilting rotation and surface reflection alignment
        chip.addEventListener("mousemove", (e) => {
            const rect = chip.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateY = ((x - centerX) / centerX) * 8;
            const rotateX = -((y - centerY) / centerY) * 8;

            const tiltX = ((x - centerX) / centerX).toFixed(2);
            const tiltY = ((y - centerY) / centerY).toFixed(2);

            chip.style.setProperty('--rotate-x', `${rotateX.toFixed(2)}deg`);
            chip.style.setProperty('--rotate-y', `${rotateY.toFixed(2)}deg`);
            chip.style.setProperty('--tilt-x', tiltX);
            chip.style.setProperty('--tilt-y', tiltY);
        });

        chip.addEventListener("mouseleave", () => {
            chip.style.removeProperty('--rotate-x');
            chip.style.removeProperty('--rotate-y');
            chip.style.removeProperty('--tilt-x');
            chip.style.removeProperty('--tilt-y');
        });

        const searchGoogle = () => {
            const query = encodeURIComponent(tagName);
            window.open(
                `https://www.google.com/search?q=${query}`,
                '_blank',
                'noopener,noreferrer'
            );
        };

        chip.addEventListener("click", (e) => {
            e.stopPropagation();
            searchGoogle();
        });

        chip.addEventListener("keydown", (e) => {
            if (e.key === "Enter" || e.key === " ") {
                e.preventDefault();
                searchGoogle();
            }
        });
    };

    // Setup initial tag chips
    const chips = document.querySelectorAll(".tags span, .archive-tech-list span");
    chips.forEach(setupChip);

    // Watch for dynamic tags (e.g. in live preview lists or dynamic archive page filtering)
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    if (node.matches(".tags span") || node.matches(".archive-tech-list span")) {
                        setupChip(node);
                    } else {
                        const subChips = node.querySelectorAll(".tags span, .archive-tech-list span");
                        subChips.forEach(setupChip);
                    }
                }
            });
        });
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
};

// Expandable descriptions with smooth height transitions and CSS line clamping
const initExpandableDescriptions = () => {
    const setups = (wrapper) => {
        const text = wrapper.querySelector(".description-text");
        if (!text) return;

        const lastText = text.textContent;
        if (wrapper.dataset.expandInitialized && wrapper.dataset.lastText === lastText) {
            return;
        }
        wrapper.dataset.expandInitialized = "true";
        wrapper.dataset.lastText = lastText;

        // Clean up any existing view-more link
        const oldLink = wrapper.parentNode.querySelector(".view-more-link");
        if (oldLink) oldLink.remove();

        // Apply clamped class to measure overflow
        text.classList.add("clamped");
        wrapper.style.maxHeight = "none";

        const isOverflowing = text.scrollHeight > text.clientHeight;

        if (!isOverflowing) {
            text.classList.remove("clamped");
            wrapper.style.maxHeight = "none";
            return;
        }

        // Create the expandable control button
        const link = document.createElement("a");
        link.className = "view-more-link";
        link.href = "#";
        link.textContent = "View More...";
        link.setAttribute("role", "button");
        link.setAttribute("tabindex", "0");
        link.setAttribute("aria-expanded", "false");

        wrapper.parentNode.insertBefore(link, wrapper.nextSibling);

        let isExpanded = false;

        const toggleExpand = (e) => {
            if (e) e.preventDefault();

            if (isExpanded) {
                // Collapse back to 3 lines
                const fullHeight = text.scrollHeight;
                wrapper.style.maxHeight = `${fullHeight}px`;
                wrapper.offsetHeight; // force reflow

                text.classList.add("clamped");
                const clampedHeight = text.clientHeight;

                wrapper.style.maxHeight = `${clampedHeight}px`;
                link.textContent = "View More...";
                link.setAttribute("aria-expanded", "false");
                isExpanded = false;
            } else {
                // Expand to show full description
                const clampedHeight = text.clientHeight;
                wrapper.style.maxHeight = `${clampedHeight}px`;
                wrapper.offsetHeight; // force reflow

                text.classList.remove("clamped");
                const fullHeight = text.scrollHeight;

                wrapper.style.maxHeight = `${fullHeight}px`;
                link.textContent = "View Less...";
                link.setAttribute("aria-expanded", "true");
                isExpanded = true;
            }
        };

        wrapper.addEventListener("transitionend", () => {
            if (isExpanded) {
                wrapper.style.maxHeight = "none";
            }
        });

        link.addEventListener("click", toggleExpand);
        link.addEventListener("keydown", (e) => {
            if (e.key === "Enter" || e.key === " ") {
                e.preventDefault();
                toggleExpand();
            }
        });
    };

    // Setup initially loaded descriptions
    const wrappers = document.querySelectorAll(".description-wrapper");
    wrappers.forEach(setups);

    // Watch for dynamic modifications (e.g. in CMS preview cards or filters)
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    if (node.matches(".description-wrapper")) {
                        setups(node);
                    } else {
                        const subs = node.querySelectorAll(".description-wrapper");
                        subs.forEach(setups);
                    }
                }
            });
            // Also monitor text content changes directly for dynamic live previews
            if (mutation.type === "characterData" || mutation.type === "childList") {
                const target = mutation.target;
                let element = null;
                if (target.nodeType === Node.TEXT_NODE) {
                    element = target.parentElement;
                } else if (target.nodeType === Node.ELEMENT_NODE) {
                    element = target;
                }
                if (element) {
                    const textNode = element.closest(".description-text");
                    if (textNode) {
                        const wrapper = textNode.closest(".description-wrapper");
                        if (wrapper) setups(wrapper);
                    }
                }
            }
        });
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true,
        characterData: true
    });
};

// Initialize tag chips & expandable descriptions interaction
if (document.readyState === "complete") {
    initTagChips();
    initExpandableDescriptions();
} else {
    window.addEventListener("load", () => {
        initTagChips();
        initExpandableDescriptions();
    });
}

