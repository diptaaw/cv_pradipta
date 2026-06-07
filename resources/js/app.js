const spotlight =
    document.querySelector(".spotlight");

let mouseX = 0;
let mouseY = 0;

let currentX = 0;
let currentY = 0;

const cursor = document.querySelector(".custom-cursor");
/* WHAT'S NEW DROPDOWN CONTROLLER */
const updatesButton = document.querySelector("[data-updates-toggle]");
const updatesDropdown = document.querySelector("[data-updates-dropdown]");
const updatesDot = document.querySelector("[data-updates-dot]");
const updatesContent = document.querySelector("[data-updates-content]");

let cachedUpdates = [];
let latestUpdateId = null;

function escapeHtml(str) {
    if (!str) return '';
    return str
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function renderUpdates(updates) {
    if (!updatesContent) return;
    if (updates.length === 0) {
        updatesContent.innerHTML = `<div class="updates-loader">No updates yet.</div>`;
        return;
    }
    updatesContent.innerHTML = updates.map(update => `
        <div class="update-item">
            <div class="update-item-header">
                <h4 class="update-item-title">
                    ${update.is_pinned ? '<span class="update-pin-badge">Pinned</span>' : ''}
                    ${escapeHtml(update.title)}
                </h4>
                <span class="update-item-date">${escapeHtml(update.date)}</span>
            </div>
            <p class="update-item-desc">${escapeHtml(update.description)}</p>
        </div>
    `).join('');
}

async function fetchUpdates() {
    try {
        const response = await fetch('/api/updates');
        if (!response.ok) throw new Error('Failed to fetch updates');
        const updates = await response.json();
        cachedUpdates = updates;
        
        renderUpdates(updates);

        if (updates.length > 0) {
            const latest = updates[0];
            latestUpdateId = latest.id;
            
            const lastViewedId = localStorage.getItem("portfolio-last-viewed-update-id");
            if (!lastViewedId || parseInt(lastViewedId) !== latestUpdateId) {
                if (updatesDot) {
                    updatesDot.classList.remove("hidden");
                }
            }
        }
    } catch (err) {
        console.error('Error loading portfolio updates:', err);
        if (updatesContent) {
            updatesContent.innerHTML = `<div class="updates-loader" style="color: #ff6b6b;">Failed to load updates.</div>`;
        }
    }
}

function openUpdatesDropdown() {
    if (!updatesDropdown || !updatesButton) return;
    updatesDropdown.classList.remove("hidden");
    updatesButton.setAttribute("aria-expanded", "true");
    updatesButton.classList.add("active");
    // Force reflow
    updatesDropdown.offsetHeight;
    updatesDropdown.classList.add("active");

    // Clear unread dot on open
    if (latestUpdateId !== null) {
        localStorage.setItem("portfolio-last-viewed-update-id", latestUpdateId.toString());
        if (updatesDot) {
            updatesDot.classList.add("hidden");
        }
    }
}

function closeUpdatesDropdown() {
    if (!updatesDropdown || !updatesButton || !updatesDropdown.classList.contains("active")) return;
    updatesDropdown.classList.remove("active");
    updatesButton.setAttribute("aria-expanded", "false");
    updatesButton.classList.remove("active");
    
    // Add hidden back after transition ends
    const onTransitionEnd = (e) => {
        if (e.propertyName === "opacity" && !updatesDropdown.classList.contains("active")) {
            updatesDropdown.classList.add("hidden");
            updatesDropdown.removeEventListener("transitionend", onTransitionEnd);
        }
    };
    updatesDropdown.addEventListener("transitionend", onTransitionEnd);
}

if (updatesButton && updatesDropdown) {
    updatesButton.addEventListener("click", (e) => {
        e.stopPropagation();
        const isOpen = updatesDropdown.classList.contains("active");
        if (isOpen) {
            closeUpdatesDropdown();
        } else {
            openUpdatesDropdown();
        }
    });

    // Close when clicking outside
    document.addEventListener("click", (e) => {
        if (!updatesDropdown.contains(e.target) && !updatesButton.contains(e.target)) {
            closeUpdatesDropdown();
        }
    });

    // Close on Escape key press
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeUpdatesDropdown();
        }
    });
}

// Fetch updates on page load
fetchUpdates();

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

let lastSpotlightTime = performance.now();
function animateSpotlight() {
    const now = performance.now();
    const dt = Math.min((now - lastSpotlightTime) / 1000, 0.1);
    lastSpotlightTime = now;

    // Time-based smooth trailing easing (4.8 rad/s convergence)
    const ease = 1.0 - Math.exp(-4.8 * dt);
    currentX += (mouseX - currentX) * ease;
    currentY += (mouseY - currentY) * ease;

    if (spotlight) {
        spotlight.style.left = `${currentX}px`;
        spotlight.style.top = `${currentY}px`;
    }

    requestAnimationFrame(animateSpotlight);
}

animateSpotlight();

// Overhauled Canvas-based Celestial Background System
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

    const bgCanvas = document.createElement("canvas");
    bgCanvas.id = "constellation-bg";
    particlesContainer.appendChild(bgCanvas);
    const bgCtx = bgCanvas.getContext("2d");

    const fgCanvas = document.createElement("canvas");
    fgCanvas.id = "constellation-fg";
    particlesOverlayContainer.appendChild(fgCanvas);
    const fgCtx = fgCanvas.getContext("2d");

    document.body.classList.add("canvas-active");

    let width = window.innerWidth;
    let height = window.innerHeight;

    let isMobileDevice = width <= 768;
    let isTabletDevice = width > 768 && width <= 1024;

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

    // Counts based on device performance
    const smallStarCount = isMobileDevice ? 30 : (isTabletDevice ? 50 : 80);
    const medStarCount = isMobileDevice ? 15 : (isTabletDevice ? 25 : 35);
    const fireflyCount = isMobileDevice ? 10 : (isTabletDevice ? 16 : 24);
    const foregroundCount = isMobileDevice ? 3 : (isTabletDevice ? 5 : 8);

    let smoothMouseOffsetX = 0;
    let smoothMouseOffsetY = 0;
    let interpolatedScrollY = window.scrollY;
    let lastScrollY = window.scrollY;
    let scrollVelocity = 0;

    // Star Color Themes for organic light/color bleeding
    const starColorThemes = [
        {
            name: 'purple',
            core: 'rgba(245, 240, 255, 1.0)',
            glowRGB: { r: 139, g: 92, b: 255 },
            lineRGB: { r: 169, g: 150, b: 255 },
            lineCoreRGB: { r: 245, g: 240, b: 255 }
        },
        {
            name: 'magenta',
            core: 'rgba(255, 240, 245, 1.0)',
            glowRGB: { r: 219, g: 39, b: 119 },
            lineRGB: { r: 219, g: 39, b: 119 },
            lineCoreRGB: { r: 255, g: 240, b: 245 }
        },
        {
            name: 'violet',
            core: 'rgba(245, 240, 255, 1.0)',
            glowRGB: { r: 124, g: 58, b: 237 },
            lineRGB: { r: 124, g: 58, b: 237 },
            lineCoreRGB: { r: 245, g: 240, b: 255 }
        },
        {
            name: 'blue',
            core: 'rgba(240, 250, 255, 1.0)',
            glowRGB: { r: 14, g: 165, b: 233 },
            lineRGB: { r: 56, g: 189, b: 248 },
            lineCoreRGB: { r: 240, g: 250, b: 255 }
        }
    ];

    function scaleRGBAOpacity(rgbaStr, factor) {
        const match = rgbaStr.match(/rgba\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*([\d\.]+)\s*\)/);
        if (match) {
            const r = match[1];
            const g = match[2];
            const b = match[3];
            const a = parseFloat(match[4]);
            return `rgba(${r}, ${g}, ${b}, ${(a * factor).toFixed(5)})`;
        }
        return rgbaStr;
    }

    function transitionStarState(star, newState) {
        star.state = newState;
        star.stateTimer = 0;
        if (newState === 'spawning') {
            star.stateDuration = 1.5 + Math.random() * 2.5; // 1.5 - 4s
            star.birthStartScale = 0.1 + Math.random() * 0.2; // 0.1 - 0.3
            star.scaleFactor = star.birthStartScale;
            star.brightnessFactor = 0.0;
            star.blurFactor = 1.0;
        } else if (newState === 'active') {
            star.stateDuration = 20 + Math.random() * 40; // 20-60s active
            star.scaleFactor = 1.0;
            star.brightnessFactor = 1.0;
            star.blurFactor = 1.0;
        } else if (newState === 'despawning') {
            star.stateDuration = 3 + Math.random() * 3; // 3-6s
            star.scaleFactor = 1.0;
            star.brightnessFactor = 1.0;
            star.blurFactor = 1.0;
        } else if (newState === 'dormant') {
            star.stateDuration = 1 + Math.random() * 4; // 1-5s dormant
            star.scaleFactor = 0.0;
            star.brightnessFactor = 0.0;
            star.blurFactor = 1.0;
        }
    }

    function updateStarLifecycle(star, dt) {
        star.stateTimer += dt;
        if (star.state === 'spawning') {
            const progress = Math.min(star.stateTimer / star.stateDuration, 1.0);
            const t = progress * progress * (3 - 2 * progress);
            star.scaleFactor = star.birthStartScale + (1.0 - star.birthStartScale) * t;
            star.brightnessFactor = t;
            star.blurFactor = 1.0;
            if (star.stateTimer >= star.stateDuration) {
                transitionStarState(star, 'active');
            }
        } else if (star.state === 'active') {
            star.scaleFactor = 1.0;
            star.brightnessFactor = 1.0;
            star.blurFactor = 1.0;
            if (star.stateTimer >= star.stateDuration) {
                transitionStarState(star, 'despawning');
            }
        } else if (star.state === 'despawning') {
            const progress = Math.min(star.stateTimer / star.stateDuration, 1.0);
            const t = progress * progress * (3 - 2 * progress);
            star.scaleFactor = 1.0 - t;
            star.brightnessFactor = 1.0 - t;
            star.blurFactor = 1.0 + t * 0.5;
            if (star.stateTimer >= star.stateDuration) {
                transitionStarState(star, 'dormant');
            }
        } else if (star.state === 'dormant') {
            star.scaleFactor = 0.0;
            star.brightnessFactor = 0.0;
            star.blurFactor = 1.0;
            if (star.stateTimer >= star.stateDuration) {
                star.baseX = Math.random() * width;
                star.baseY = Math.random() * height;
                transitionStarState(star, 'spawning');
            }
        }
    }

    function initStarLifecycleRandomized(star) {
        const rand = Math.random();
        if (rand < 0.7) {
            star.state = 'active';
            star.stateDuration = 20 + Math.random() * 40;
            star.stateTimer = Math.random() * star.stateDuration;
            star.scaleFactor = 1.0;
            star.brightnessFactor = 1.0;
            star.blurFactor = 1.0;
        } else if (rand < 0.85) {
            star.state = 'spawning';
            star.stateDuration = 1.5 + Math.random() * 2.5;
            star.stateTimer = Math.random() * star.stateDuration;
            star.birthStartScale = 0.1 + Math.random() * 0.2;
            const progress = star.stateTimer / star.stateDuration;
            const t = progress * progress * (3 - 2 * progress);
            star.scaleFactor = star.birthStartScale + (1.0 - star.birthStartScale) * t;
            star.brightnessFactor = t;
            star.blurFactor = 1.0;
        } else {
            star.state = 'despawning';
            star.stateDuration = 3 + Math.random() * 3;
            star.stateTimer = Math.random() * star.stateDuration;
            star.birthStartScale = 0.1 + Math.random() * 0.2;
            const progress = star.stateTimer / star.stateDuration;
            const t = progress * progress * (3 - 2 * progress);
            star.scaleFactor = 1.0 - t;
            star.brightnessFactor = 1.0 - t;
            star.blurFactor = 1.0 + t * 0.5;
        }
    }

    // Layer 4: Independent Twinkling Stars
    const smallStars = [];
    for (let i = 0; i < smallStarCount; i++) {
        const star = {
            baseX: Math.random() * width,
            baseY: Math.random() * height,
            size: 0.35 + Math.random() * 0.35, // much smaller far stars (0.35 - 0.7)
            twinklePhase: Math.random() * Math.PI * 2,
            twinkleSpeed: 0.003 + Math.random() * 0.004, // slower and more organic twinkle speed
            baseOpacity: 0.15 + Math.random() * 0.20, // lower opacity for far stars (15% to 35%)
            parallaxMouse: 0.015,
            parallaxScroll: 0.035,
            driftAngle: Math.random() * Math.PI * 2,
            driftSpeed: 0.0008 + Math.random() * 0.0012,
            theme: starColorThemes[Math.floor(Math.random() * starColorThemes.length)],
            seed: Math.random() * 1000
        };
        initStarLifecycleRandomized(star);
        smallStars.push(star);
    }

    const medStars = [];
    for (let i = 0; i < medStarCount; i++) {
        const star = {
            baseX: Math.random() * width,
            baseY: Math.random() * height,
            size: 0.8 + Math.random() * 0.6, // mid-distance sizes (0.8 - 1.4)
            twinklePhase: Math.random() * Math.PI * 2,
            twinkleSpeed: 0.002 + Math.random() * 0.003, // slower and more organic twinkle speed
            baseOpacity: 0.45 + Math.random() * 0.20, // normal opacity (45% to 65%)
            parallaxMouse: 0.04,
            parallaxScroll: 0.08,
            driftAngle: Math.random() * Math.PI * 2,
            driftSpeed: 0.0012 + Math.random() * 0.0018,
            theme: starColorThemes[Math.floor(Math.random() * starColorThemes.length)],
            seed: Math.random() * 1000
        };
        initStarLifecycleRandomized(star);
        medStars.push(star);
    }

    function transitionAtmosphereState(obj, newState) {
        obj.state = newState;
        obj.stateTimer = 0;
        if (newState === 'spawning') {
            obj.stateDuration = 5 + Math.random() * 5; // 5-10s
            obj.lifecycleFade = 0;
        } else if (newState === 'active') {
            obj.stateDuration = 40 + Math.random() * 40; // 40-80s active
            obj.lifecycleFade = 1;
        } else if (newState === 'despawning') {
            obj.stateDuration = 8 + Math.random() * 7; // 8-15s
            obj.lifecycleFade = 1;
        } else if (newState === 'dormant') {
            obj.stateDuration = 2 + Math.random() * 4; // 2-6s dormant
            obj.lifecycleFade = 0;
        }
    }

    function updateAtmosphereLifecycle(obj, dt) {
        obj.stateTimer += dt;
        if (obj.state === 'spawning') {
            const t = Math.min(obj.stateTimer / obj.stateDuration, 1);
            obj.lifecycleFade = t * t * (3 - 2 * t);
            if (obj.stateTimer >= obj.stateDuration) {
                transitionAtmosphereState(obj, 'active');
            }
        } else if (obj.state === 'active') {
            obj.lifecycleFade = 1.0;
            if (obj.stateTimer >= obj.stateDuration) {
                transitionAtmosphereState(obj, 'despawning');
            }
        } else if (obj.state === 'despawning') {
            const t = Math.min(obj.stateTimer / obj.stateDuration, 1);
            obj.lifecycleFade = 1 - (t * t * (3 - 2 * t));
            if (obj.stateTimer >= obj.stateDuration) {
                transitionAtmosphereState(obj, 'dormant');
            }
        } else if (obj.state === 'dormant') {
            obj.lifecycleFade = 0.0;
            if (obj.stateTimer >= obj.stateDuration) {
                // Reposition the fraction randomly
                obj.baseXFraction = 0.15 + Math.random() * 0.7;
                obj.baseYFraction = 0.15 + Math.random() * 0.7;
                transitionAtmosphereState(obj, 'spawning');
            }
        }
    }

    function initAtmosphereLifecycleRandomized(obj) {
        const rand = Math.random();
        if (rand < 0.7) {
            obj.state = 'active';
            obj.stateDuration = 40 + Math.random() * 40;
            obj.stateTimer = Math.random() * obj.stateDuration;
            obj.lifecycleFade = 1.0;
        } else if (rand < 0.85) {
            obj.state = 'spawning';
            obj.stateDuration = 5 + Math.random() * 5;
            obj.stateTimer = Math.random() * obj.stateDuration;
            const t = obj.stateTimer / obj.stateDuration;
            obj.lifecycleFade = t * t * (3 - 2 * t);
        } else {
            obj.state = 'despawning';
            obj.stateDuration = 8 + Math.random() * 7;
            obj.stateTimer = Math.random() * obj.stateDuration;
            const t = obj.stateTimer / obj.stateDuration;
            obj.lifecycleFade = 1 - (t * t * (3 - 2 * t));
        }
    }

    // Layer 1: Living Nebula Gradients
    const nebulaBlobs = [
        { baseXFraction: 0.20, baseYFraction: 0.30, radiusFraction: 0.60, color: 'rgba(54, 31, 147, 0.12)', angleX: Math.random() * 10, angleY: Math.random() * 10, speedX: 0.0003, speedY: 0.00015 },
        { baseXFraction: 0.80, baseYFraction: 0.70, radiusFraction: 0.55, color: 'rgba(139, 92, 255, 0.09)', angleX: Math.random() * 10, angleY: Math.random() * 10, speedX: 0.00015, speedY: 0.00022 },
        { baseXFraction: 0.45, baseYFraction: 0.60, radiusFraction: 0.65, color: 'rgba(88, 56, 255, 0.08)', angleX: Math.random() * 10, angleY: Math.random() * 10, speedX: 0.00022, speedY: 0.0002 },
        { baseXFraction: 0.70, baseYFraction: 0.25, radiusFraction: 0.50, color: 'rgba(198, 107, 255, 0.07)', angleX: Math.random() * 10, angleY: Math.random() * 10, speedX: 0.00035, speedY: 0.0001 }
    ];
    nebulaBlobs.forEach(initAtmosphereLifecycleRandomized);

    // Layer 2: Interactive Parallax Haze Layers
    const hazeLayers = [
        {
            name: 'Far Haze',
            baseXFraction: 0.35,
            baseYFraction: 0.40,
            scaleX: 3.5,
            scaleY: 1.2,
            radius: 280,
            color0: 'rgba(54, 31, 147, 0.04)',
            color5: 'rgba(54, 31, 147, 0.012)',
            driftAngle: Math.random() * Math.PI,
            driftSpeed: 0.0001,
            parallaxMouse: 0.01,
            parallaxScroll: 0.02
        },
        {
            name: 'Middle Haze',
            baseXFraction: 0.65,
            baseYFraction: 0.55,
            scaleX: 3.0,
            scaleY: 1.0,
            radius: 220,
            color0: 'rgba(139, 92, 255, 0.03)',
            color5: 'rgba(139, 92, 255, 0.008)',
            driftAngle: Math.random() * Math.PI,
            driftSpeed: 0.0002,
            parallaxMouse: 0.03,
            parallaxScroll: 0.06
        },
        {
            name: 'Near Haze',
            baseXFraction: 0.45,
            baseYFraction: 0.75,
            scaleX: 2.5,
            scaleY: 0.8,
            radius: 180,
            color0: 'rgba(198, 107, 255, 0.038)',
            color5: 'rgba(198, 107, 255, 0.01)',
            driftAngle: Math.random() * Math.PI,
            driftSpeed: 0.0003,
            parallaxMouse: 0.05,
            parallaxScroll: 0.10
        }
    ];
    hazeLayers.forEach(initAtmosphereLifecycleRandomized);

    // Layer 3: Constellation templates
    const constellationTemplates = [
        {
            name: "Ursa Major",
            stars: [{x: 0, y: 35}, {x: 35, y: 30}, {x: 65, y: 45}, {x: 90, y: 55}, {x: 95, y: 85}, {x: 140, y: 95}, {x: 145, y: 55}, {x: 95, y: 85}],
            lines: [[0, 1], [1, 2], [2, 3], [3, 4], [4, 5], [5, 6], [6, 7], [7, 4]]
        },
        {
            name: "Cassiopeia",
            stars: [{x: 0, y: 10}, {x: 25, y: 35}, {x: 50, y: 15}, {x: 75, y: 40}, {x: 100, y: 20}],
            lines: [[0, 1], [1, 2], [2, 3], [3, 4]]
        },
        {
            name: "Cygnus",
            stars: [{x: 50, y: 0}, {x: 50, y: 40}, {x: 50, y: 80}, {x: 50, y: 120}, {x: 10, y: 40}, {x: 90, y: 40}],
            lines: [[0, 1], [1, 2], [2, 3], [1, 4], [1, 5]]
        },
        {
            name: "Orion",
            stars: [{x: 10, y: 0}, {x: 60, y: 5}, {x: 100, y: 10}, {x: 35, y: 45}, {x: 50, y: 46}, {x: 65, y: 47}, {x: 20, y: 90}, {x: 85, y: 95}, {x: 30, y: 15}],
            lines: [[0, 1], [1, 2], [2, 5], [5, 4], [4, 3], [3, 0], [3, 6], [6, 7], [7, 5], [0, 8]]
        },
        {
            name: "Pegasus",
            stars: [{x: 0, y: 0}, {x: 80, y: 0}, {x: 80, y: 80}, {x: 0, y: 80}, {x: -30, y: 110}, {x: 110, y: -30}],
            lines: [[0, 1], [1, 2], [2, 3], [3, 0], [3, 4], [1, 5]]
        }
    ];

    const constellations = [
        { layer: 'far', state: 'inactive' },
        { layer: 'far', state: 'inactive' },
        { layer: 'middle', state: 'inactive' },
        { layer: 'middle', state: 'inactive' },
        { layer: 'near', state: 'inactive' },
        { layer: 'near', state: 'inactive' }
    ];

    function initConstellation(c, randomizeState = false) {
        const templateIdx = Math.floor(Math.random() * constellationTemplates.length);
        const template = constellationTemplates[templateIdx];

        const margin = 80;
        c.baseX = -margin + Math.random() * (width + margin * 2);
        c.baseY = -margin + Math.random() * (height + margin * 2);

        // Assign a color theme
        c.theme = starColorThemes[Math.floor(Math.random() * starColorThemes.length)];

        if (c.layer === 'far') {
            c.scale = 0.55 + Math.random() * 0.15;
            c.maxOpacity = 0.22 + Math.random() * 0.06;
            c.parallaxMouse = 0.015;
            c.parallaxScroll = 0.035;
        } else if (c.layer === 'middle') {
            c.scale = 0.85 + Math.random() * 0.25;
            c.maxOpacity = 0.45 + Math.random() * 0.10;
            c.parallaxMouse = 0.04;
            c.parallaxScroll = 0.08;
        } else {
            c.scale = 1.35 + Math.random() * 0.35;
            c.maxOpacity = 0.68 + Math.random() * 0.12;
            c.parallaxMouse = 0.075;
            c.parallaxScroll = 0.15;
        }

        c.rotationAngle = Math.random() * Math.PI * 2;
        c.template = template;

        c.stars = template.stars.map((s, idx) => {
            const birthStartScale = 0.1 + Math.random() * 0.2;
            return {
                x: s.x,
                y: s.y,
                twinklePhase: Math.random() * Math.PI * 2,
                twinkleSpeed: 0.6 + Math.random() * 1.2, // Rad/sec (scaled in update)
                seed: Math.random() * 1000,
                starId: idx,
                fadeVal: 0,
                birthStartScale: birthStartScale,
                scaleFactor: birthStartScale,
                brightnessFactor: 0.0,
                blurFactor: 1.0,
                birthDuration: 1.5 + Math.random() * 2.5, // 1.5 - 4.0 seconds duration
                birthTimer: 0
            };
        });

        // Reset line draw values
        c.lines = template.lines.map(() => ({
            drawVal: 0,
            drawDuration: 1.5 + Math.random() * 2.5, // 1.5 - 4.0 seconds duration
            drawTimer: 0
        }));

        c.driftAngle = Math.random() * Math.PI * 2;
        c.driftSpeed = 0.018 + Math.random() * 0.024; // Rad/sec
        c.driftX = 0;
        c.driftY = 0;

        // Select initial stars (2-3) to fade in during the spawning phase
        const initialStarsSet = new Set();
        if (template.lines.length > 0) {
            initialStarsSet.add(template.lines[0][0]);
            initialStarsSet.add(template.lines[0][1]);
        }
        if (template.stars.length > 2) {
            for (let i = 0; i < template.stars.length; i++) {
                if (!initialStarsSet.has(i)) {
                    initialStarsSet.add(i);
                    break;
                }
            }
        }
        c.initialStars = Array.from(initialStarsSet);

        // Build flat sequential construction tasks list
        c.constructionTasks = [];
        const revealedStars = new Set(c.initialStars); // Start with initial stars marked as revealed
        template.lines.forEach((line, lineIdx) => {
            const [starA, starB] = line;
            if (!revealedStars.has(starA)) {
                c.constructionTasks.push({ type: 'star', starIdx: starA });
                revealedStars.add(starA);
            }
            if (!revealedStars.has(starB)) {
                c.constructionTasks.push({ type: 'star', starIdx: starB });
                revealedStars.add(starB);
            }
            c.constructionTasks.push({ type: 'line', lineIdx: lineIdx });
        });

        // Ensure all stars are revealed (fallback)
        template.stars.forEach((_, starIdx) => {
            if (!revealedStars.has(starIdx)) {
                c.constructionTasks.push({ type: 'star', starIdx: starIdx });
                revealedStars.add(starIdx);
            }
        });

        c.fadeOutDuration = 5 + Math.random() * 5; // 5-10 seconds fading out
        c.hasSpawnedReplacement = false;
        c.fadeVal = 1.0;

        if (randomizeState) {
            const rand = Math.random();
            if (rand < 0.35) {
                // Start as completed (idle)
                c.state = 'idle';
                c.idleTimer = Math.random() * 20;
                c.stars.forEach(s => {
                    s.fadeVal = 1.0;
                    s.scaleFactor = 1.0;
                    s.brightnessFactor = 1.0;
                });
                c.lines.forEach(l => l.drawVal = 1.0);
            } else if (rand < 0.7 && c.constructionTasks.length > 0) {
                // Start constructing partially
                c.state = 'constructing';
                c.taskIdx = Math.floor(Math.random() * c.constructionTasks.length);
                // Set prior tasks to complete
                c.stars.forEach(s => {
                    s.fadeVal = 1.0;
                    s.scaleFactor = 1.0;
                    s.brightnessFactor = 1.0;
                });
                c.lines.forEach(l => l.drawVal = 1.0);
                // Hide future tasks
                for (let idx = c.taskIdx; idx < c.constructionTasks.length; idx++) {
                    const task = c.constructionTasks[idx];
                    if (task.type === 'star') {
                        const s = c.stars[task.starIdx];
                        s.fadeVal = 0;
                        s.scaleFactor = s.birthStartScale;
                        s.brightnessFactor = 0;
                    } else if (task.type === 'line') {
                        c.lines[task.lineIdx].drawVal = 0;
                    }
                }
                c.taskTimer = 0;
                c.taskState = 'delay';
                c.delayDuration = 0.3 + Math.random() * 0.9;
            } else {
                // Start spawning
                c.state = 'spawning';
                c.initialStars.forEach(idx => {
                    const s = c.stars[idx];
                    if (s) {
                        s.birthTimer = Math.random() * s.birthDuration;
                        const progress = s.birthTimer / s.birthDuration;
                        const t = progress * progress * (3 - 2 * progress);
                        s.fadeVal = t;
                        s.scaleFactor = s.birthStartScale + (1.0 - s.birthStartScale) * t;
                        s.brightnessFactor = t;
                    }
                });
            }
        } else {
            // Normal initialization
            c.state = 'spawning';
            c.taskIdx = 0;
            c.taskTimer = 0;
            c.taskState = 'delay';
            c.delayDuration = 0.3 + Math.random() * 0.9;
        }
    }

    constellations.forEach((c, idx) => {
        if (idx % 2 === 0) {
            initConstellation(c, true);
        } else {
            c.state = 'inactive';
        }
    });


    function transitionFireflyState(ff, newState) {
        ff.state = newState;
        ff.stateTimer = 0;
        if (newState === 'spawning') {
            ff.stateDuration = 1.5 + Math.random() * 2.5; // 1.5-4s
            ff.birthStartScale = 0.1 + Math.random() * 0.2;
            ff.scaleFactor = ff.birthStartScale;
            ff.brightnessFactor = 0.0;
            ff.blurFactor = 1.0;
        } else if (newState === 'active') {
            ff.stateDuration = 20 + Math.random() * 20; // 20-40s active
            ff.scaleFactor = 1.0;
            ff.brightnessFactor = 1.0;
            ff.blurFactor = 1.0;
        } else if (newState === 'despawning') {
            ff.stateDuration = 3 + Math.random() * 2; // 3-5s
            ff.scaleFactor = 1.0;
            ff.brightnessFactor = 1.0;
            ff.blurFactor = 1.0;
        } else if (newState === 'dormant') {
            ff.stateDuration = 1 + Math.random() * 3; // 1-4s dormant
            ff.scaleFactor = 0.0;
            ff.brightnessFactor = 0.0;
            ff.blurFactor = 1.0;
        }
    }

    function updateFireflyLifecycle(ff, dt) {
        ff.stateTimer += dt;
        if (ff.state === 'spawning') {
            const progress = Math.min(ff.stateTimer / ff.stateDuration, 1.0);
            const t = progress * progress * (3 - 2 * progress);
            ff.scaleFactor = ff.birthStartScale + (1.0 - ff.birthStartScale) * t;
            ff.brightnessFactor = t;
            ff.blurFactor = 1.0;
            if (ff.stateTimer >= ff.stateDuration) {
                transitionFireflyState(ff, 'active');
            }
        } else if (ff.state === 'active') {
            ff.scaleFactor = 1.0;
            ff.brightnessFactor = 1.0;
            ff.blurFactor = 1.0;
            if (ff.stateTimer >= ff.stateDuration) {
                transitionFireflyState(ff, 'despawning');
            }
        } else if (ff.state === 'despawning') {
            const progress = Math.min(ff.stateTimer / ff.stateDuration, 1.0);
            const t = progress * progress * (3 - 2 * progress);
            ff.scaleFactor = 1.0 - t;
            ff.brightnessFactor = 1.0 - t;
            ff.blurFactor = 1.0 + t * 0.5;
            if (ff.stateTimer >= ff.stateDuration) {
                transitionFireflyState(ff, 'dormant');
            }
        } else if (ff.state === 'dormant') {
            ff.scaleFactor = 0.0;
            ff.brightnessFactor = 0.0;
            ff.blurFactor = 1.0;
            if (ff.stateTimer >= ff.stateDuration) {
                ff.baseX = Math.random() * width;
                ff.baseY = Math.random() * height;
                ff.vx = 0;
                ff.vy = 0;
                transitionFireflyState(ff, 'spawning');
            }
        }
    }

    function initFireflyLifecycleRandomized(ff) {
        const rand = Math.random();
        if (rand < 0.7) {
            ff.state = 'active';
            ff.stateDuration = 20 + Math.random() * 20;
            ff.stateTimer = Math.random() * ff.stateDuration;
            ff.scaleFactor = 1.0;
            ff.brightnessFactor = 1.0;
            ff.blurFactor = 1.0;
        } else if (rand < 0.85) {
            ff.state = 'spawning';
            ff.stateDuration = 1.5 + Math.random() * 2.5;
            ff.stateTimer = Math.random() * ff.stateDuration;
            ff.birthStartScale = 0.1 + Math.random() * 0.2;
            const progress = ff.stateTimer / ff.stateDuration;
            const t = progress * progress * (3 - 2 * progress);
            ff.scaleFactor = ff.birthStartScale + (1.0 - ff.birthStartScale) * t;
            ff.brightnessFactor = t;
            ff.blurFactor = 1.0;
        } else {
            ff.state = 'despawning';
            ff.stateDuration = 3 + Math.random() * 2;
            ff.stateTimer = Math.random() * ff.stateDuration;
            ff.birthStartScale = 0.1 + Math.random() * 0.2;
            const progress = ff.stateTimer / ff.stateDuration;
            const t = progress * progress * (3 - 2 * progress);
            ff.scaleFactor = 1.0 - t;
            ff.brightnessFactor = 1.0 - t;
            ff.blurFactor = 1.0 + t * 0.5;
        }
    }

    function transitionForegroundParticleState(p, newState) {
        p.state = newState;
        p.stateTimer = 0;
        if (newState === 'spawning') {
            p.stateDuration = 1.5 + Math.random() * 2.5; // 1.5-4s
            p.birthStartScale = 0.1 + Math.random() * 0.2;
            p.scaleFactor = p.birthStartScale;
            p.brightnessFactor = 0.0;
            p.blurFactor = 1.0;
        } else if (newState === 'active') {
            p.stateDuration = 15 + Math.random() * 20; // 15-35s active
            p.scaleFactor = 1.0;
            p.brightnessFactor = 1.0;
            p.blurFactor = 1.0;
        } else if (newState === 'despawning') {
            p.stateDuration = 3 + Math.random() * 3; // 3-6s
            p.scaleFactor = 1.0;
            p.brightnessFactor = 1.0;
            p.blurFactor = 1.0;
        } else if (newState === 'dormant') {
            p.stateDuration = 1 + Math.random() * 3; // 1-4s dormant
            p.scaleFactor = 0.0;
            p.brightnessFactor = 0.0;
            p.blurFactor = 1.0;
        }
    }

    function updateForegroundParticleLifecycle(p, dt) {
        p.stateTimer += dt;
        if (p.state === 'spawning') {
            const progress = Math.min(p.stateTimer / p.stateDuration, 1.0);
            const t = progress * progress * (3 - 2 * progress);
            p.scaleFactor = p.birthStartScale + (1.0 - p.birthStartScale) * t;
            p.brightnessFactor = t;
            p.blurFactor = 1.0;
            if (p.stateTimer >= p.stateDuration) {
                transitionForegroundParticleState(p, 'active');
            }
        } else if (p.state === 'active') {
            p.scaleFactor = 1.0;
            p.brightnessFactor = 1.0;
            p.blurFactor = 1.0;
            if (p.stateTimer >= p.stateDuration) {
                transitionForegroundParticleState(p, 'despawning');
            }
        } else if (p.state === 'despawning') {
            const progress = Math.min(p.stateTimer / p.stateDuration, 1.0);
            const t = progress * progress * (3 - 2 * progress);
            p.scaleFactor = 1.0 - t;
            p.brightnessFactor = 1.0 - t;
            p.blurFactor = 1.0 + t * 0.5;
            if (p.stateTimer >= p.stateDuration) {
                transitionForegroundParticleState(p, 'dormant');
            }
        } else if (p.state === 'dormant') {
            p.scaleFactor = 0.0;
            p.brightnessFactor = 0.0;
            p.blurFactor = 1.0;
            if (p.stateTimer >= p.stateDuration) {
                p.baseX = Math.random() * width;
                p.baseY = Math.random() * height;
                transitionForegroundParticleState(p, 'spawning');
            }
        }
    }

    function initForegroundParticleLifecycleRandomized(p) {
        const rand = Math.random();
        if (rand < 0.7) {
            p.state = 'active';
            p.stateDuration = 15 + Math.random() * 20;
            p.stateTimer = Math.random() * p.stateDuration;
            p.scaleFactor = 1.0;
            p.brightnessFactor = 1.0;
            p.blurFactor = 1.0;
        } else if (rand < 0.85) {
            p.state = 'spawning';
            p.stateDuration = 1.5 + Math.random() * 2.5;
            p.stateTimer = Math.random() * p.stateDuration;
            p.birthStartScale = 0.1 + Math.random() * 0.2;
            const progress = p.stateTimer / p.stateDuration;
            const t = progress * progress * (3 - 2 * progress);
            p.scaleFactor = p.birthStartScale + (1.0 - p.birthStartScale) * t;
            p.brightnessFactor = t;
            p.blurFactor = 1.0;
        } else {
            p.state = 'despawning';
            p.stateDuration = 3 + Math.random() * 3;
            p.stateTimer = Math.random() * p.stateDuration;
            p.birthStartScale = 0.1 + Math.random() * 0.2;
            const progress = p.stateTimer / p.stateDuration;
            const t = progress * progress * (3 - 2 * progress);
            p.scaleFactor = 1.0 - t;
            p.brightnessFactor = 1.0 - t;
            p.blurFactor = 1.0 + t * 0.5;
        }
    }

    // Layer 5: Fireflies
    const fireflies = [];
    for (let i = 0; i < fireflyCount; i++) {
        const size = 5 + Math.random() * 9;
        const opacity = 0.3 + Math.random() * 0.4;
        const speedMult = 0.35 + Math.random() * 0.45;
        
        // Depth distribution
        const randDepth = Math.random();
        let parallaxFactor = 0.16;
        let scale = 1.0;
        if (randDepth < 0.25) {
            parallaxFactor = 0.10; // Distant
            scale = 0.6;
        } else if (randDepth > 0.8) {
            parallaxFactor = 0.26; // Foreground
            scale = 1.6;
        }

        const ff = {
            baseX: Math.random() * width,
            baseY: Math.random() * height,
            vx: 0,
            vy: 0,
            targetVx: 0,
            targetVy: 0,
            size: size * scale,
            baseOpacity: opacity,
            currentOpacity: opacity,
            angle: Math.random() * Math.PI * 2,
            speed: (20 + Math.random() * 25) * speedMult, // 7 to 36 px/sec base speed
            wobbleSeed: Math.random() * 1000,
            wobbleSpeed: 0.003 + Math.random() * 0.006,
            parallaxFactor: parallaxFactor,
            speedMult: speedMult,
            colorIndex: Math.floor(Math.random() * 3)
        };
        initFireflyLifecycleRandomized(ff);
        fireflies.push(ff);
    }

    // Layer 6: Foreground blurred particles
    const foregroundParticles = [];
    for (let i = 0; i < foregroundCount; i++) {
        const size = 65 + Math.random() * 50;
        const opacity = 0.10 + Math.random() * 0.15;
        const p = {
            baseX: Math.random() * width,
            baseY: Math.random() * height,
            vx: (Math.random() - 0.5) * 0.06,
            vy: (Math.random() - 0.5) * 0.06,
            size: size,
            baseOpacity: opacity,
            currentOpacity: opacity,
            angle: Math.random() * Math.PI * 2,
            speed: 0.00015 + Math.random() * 0.00035,
            parallaxFactor: 0.32
        };
        initForegroundParticleLifecycleRandomized(p);
        foregroundParticles.push(p);
    }

    const fireflyColors = [
        { r: 109, g: 76, b: 255 },  // Purple
        { r: 139, g: 92, b: 255 },  // Violet
        { r: 198, g: 107, b: 255 }  // Magenta
    ];

    // Helper to draw wide, soft environment light diffusion (Reacting to bright stars)
    function drawHazeReaction(ctx, cx, cy, radius, colorRGB, opacity) {
        if (opacity <= 0) return;
        ctx.save();
        ctx.globalCompositeOperation = 'screen';
        const grad = ctx.createRadialGradient(cx, cy, 0, cx, cy, radius);
        const { r, g, b } = colorRGB;
        const maxOp = 0.035 * opacity;
        grad.addColorStop(0, `rgba(${r}, ${g}, ${b}, ${maxOp})`);
        grad.addColorStop(0.5, `rgba(${r}, ${g}, ${b}, ${maxOp * 0.25})`);
        grad.addColorStop(1, 'transparent');
        ctx.fillStyle = grad;
        ctx.beginPath();
        ctx.arc(cx, cy, radius, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();
    }

    // Helper to draw organic, turbulent star halo (Breaking perfect circles)
    function drawOrganicHalo(ctx, cx, cy, baseRadius, colorRGB, opacity, phase, seed) {
        if (opacity <= 0) return;
        ctx.save();
        const { r, g, b } = colorRGB;
        ctx.beginPath();
        const points = 10;
        
        const grad = ctx.createRadialGradient(cx, cy, 0, cx, cy, baseRadius * 1.4);
        grad.addColorStop(0, `rgba(${r}, ${g}, ${b}, ${opacity})`);
        grad.addColorStop(0.5, `rgba(${r}, ${g}, ${b}, ${opacity * 0.35})`);
        grad.addColorStop(1, 'transparent');
        ctx.fillStyle = grad;

        for (let i = 0; i < points; i++) {
            const angle = (i / points) * Math.PI * 2;
            const offset = 0.18 * Math.sin(phase + angle * 3 + seed) + 0.08 * Math.cos(phase * 1.7 - angle * 5 + seed * 1.3);
            const radius = baseRadius * (1.0 + offset);
            const tx = cx + Math.cos(angle) * radius;
            const ty = cy + Math.sin(angle) * radius;
            if (i === 0) {
                ctx.moveTo(tx, ty);
            } else {
                ctx.lineTo(tx, ty);
            }
        }
        ctx.closePath();
        ctx.fill();
        ctx.restore();
    }

    // Helper to draw volumetric lens spikes (Diffraction spikes for mid/near stars)
    function drawDiffractionSpikes(ctx, cx, cy, length, width, colorRGB, opacity, angle) {
        if (opacity <= 0 || length <= 0) return;
        ctx.save();
        ctx.globalCompositeOperation = 'screen';
        ctx.translate(cx, cy);
        ctx.rotate(angle);

        const { r, g, b } = colorRGB;

        // Draw horizontal spike
        let gradH = ctx.createRadialGradient(0, 0, 0, 0, 0, length);
        gradH.addColorStop(0, `rgba(${r}, ${g}, ${b}, ${0.45 * opacity})`);
        gradH.addColorStop(0.3, `rgba(${r}, ${g}, ${b}, ${0.15 * opacity})`);
        gradH.addColorStop(1, 'transparent');
        ctx.fillStyle = gradH;
        ctx.beginPath();
        ctx.ellipse(0, 0, length, width, 0, 0, Math.PI * 2);
        ctx.fill();

        // Draw vertical spike
        let gradV = ctx.createRadialGradient(0, 0, 0, 0, 0, length);
        gradV.addColorStop(0, `rgba(${r}, ${g}, ${b}, ${0.45 * opacity})`);
        gradV.addColorStop(0.3, `rgba(${r}, ${g}, ${b}, ${0.15 * opacity})`);
        gradV.addColorStop(1, 'transparent');
        ctx.fillStyle = gradV;
        ctx.beginPath();
        ctx.ellipse(0, 0, width, length, 0, 0, Math.PI * 2);
        ctx.fill();

        ctx.restore();
    }

    // Volumetric star renderer combining organic glow, color bleed, and lens flares
    function drawStarVolumetric(ctx, cx, cy, size, opacity, theme, depth, phase, seed, linePulseFactor = 0) {
        const { glowRGB, core } = theme;
        const totalOpacity = opacity * (1.0 + linePulseFactor * 0.4);

        if (depth === 'far') {
            // Far stars: almost no bloom, less saturated (use soft desaturated white-blue), no spikes
            const farGlowRGB = { r: 200, g: 215, b: 255 };
            
            // Faint, tiny organic halo
            drawOrganicHalo(ctx, cx, cy, size * 1.6, farGlowRGB, totalOpacity * 0.3, phase, seed);
            
            // Sharp tiny core
            ctx.beginPath();
            ctx.fillStyle = 'rgba(235, 240, 255, 1.0)';
            ctx.arc(cx, cy, size, 0, Math.PI * 2);
            ctx.fill();
        } else if (depth === 'middle') {
            // Mid stars: moderate bloom, desaturated glow, short spikes
            // 1. Moderate local haze reaction
            drawHazeReaction(ctx, cx, cy, 35, glowRGB, totalOpacity * 0.7);

            // 2. Volumetric organic bloom
            drawOrganicHalo(ctx, cx, cy, size * 3.5, glowRGB, totalOpacity * 0.35, phase, seed);
            drawOrganicHalo(ctx, cx, cy, size * 1.5, glowRGB, totalOpacity * 0.65, phase, seed + 2);

            // 3. Subtle spikes
            const spikeLength = size * 10;
            const spikeWidth = size * 0.5;
            const rotationAngle = (phase * 0.05) + seed;
            drawDiffractionSpikes(ctx, cx, cy, spikeLength, spikeWidth, glowRGB, totalOpacity * 0.25, rotationAngle);

            // 4. Star Core
            ctx.beginPath();
            ctx.fillStyle = core;
            ctx.arc(cx, cy, size, 0, Math.PI * 2);
            ctx.fill();
        } else {
            // Near stars: bright volumetric flares, prominent diffraction spikes
            // 1. Environmental haze reaction
            drawHazeReaction(ctx, cx, cy, 140, glowRGB, totalOpacity);

            // 2. Organic volumetric bloom
            drawOrganicHalo(ctx, cx, cy, size * 6.5, glowRGB, totalOpacity * 0.45, phase, seed);
            drawOrganicHalo(ctx, cx, cy, size * 2.8, glowRGB, totalOpacity * 0.8, phase, seed + 2);

            // 3. Prominent lens diffraction spikes
            const spikeLength = size * 26;
            const spikeWidth = size * 1.3;
            const rotationAngle = (phase * 0.05) + seed;
            drawDiffractionSpikes(ctx, cx, cy, spikeLength, spikeWidth, glowRGB, totalOpacity * 0.35, rotationAngle);

            // 4. Large Core
            ctx.beginPath();
            ctx.fillStyle = core;
            ctx.arc(cx, cy, size * 1.2, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    function drawHazeLayer(haze, dt) {
        updateAtmosphereLifecycle(haze, dt);

        haze.driftAngle = (haze.driftAngle + haze.driftSpeed * 60 * (1.0 + scrollVelocity * 0.4) * dt) % (Math.PI * 2);

        const driftX = Math.sin(haze.driftAngle) * (width * 0.08);
        const driftY = Math.cos(haze.driftAngle * 0.8) * (height * 0.06);

        const scrollShiftY = -interpolatedScrollY * haze.parallaxScroll;
        const mouseShiftX = smoothMouseOffsetX * haze.parallaxMouse;
        const mouseShiftY = smoothMouseOffsetY * haze.parallaxMouse;

        let cx = (width * haze.baseXFraction + driftX + mouseShiftX) % width;
        if (cx < 0) cx += width;
        let cy = (height * haze.baseYFraction + driftY + scrollShiftY + mouseShiftY) % height;
        if (cy < 0) cy += height;

        bgCtx.save();
        bgCtx.translate(cx, cy);
        bgCtx.scale(haze.scaleX, haze.scaleY);

        let grad = bgCtx.createRadialGradient(0, 0, 0, 0, 0, haze.radius);
        grad.addColorStop(0, scaleRGBAOpacity(haze.color0, haze.lifecycleFade));
        grad.addColorStop(0.5, scaleRGBAOpacity(haze.color5, haze.lifecycleFade));
        grad.addColorStop(1, 'transparent');

        bgCtx.fillStyle = grad;
        bgCtx.beginPath();
        bgCtx.arc(0, 0, haze.radius, 0, Math.PI * 2);
        bgCtx.fill();
        bgCtx.restore();
    }

    function updateConstellation(c, dt) {
        c.driftAngle = (c.driftAngle + c.driftSpeed * dt) % (Math.PI * 2);
        c.driftX = Math.sin(c.driftAngle) * 16;
        c.driftY = Math.cos(c.driftAngle * 0.75) * 12;

        if (c.state === 'inactive') return;

        if (c.state === 'spawning') {
            c.stars.forEach(s => {
                s.twinklePhase = (s.twinklePhase + s.twinkleSpeed * 0.5 * dt) % (Math.PI * 2);
            });
            c.spawnTimer += dt;
            
            let allInitialStarsStabilized = true;
            c.initialStars.forEach(idx => {
                const s = c.stars[idx];
                if (s) {
                    s.birthTimer += dt;
                    const progress = Math.min(s.birthTimer / s.birthDuration, 1.0);
                    const t = progress * progress * (3 - 2 * progress); // smoothstep
                    s.fadeVal = t;
                    s.scaleFactor = s.birthStartScale + (1.0 - s.birthStartScale) * t;
                    s.brightnessFactor = t;
                    if (s.birthTimer < s.birthDuration) {
                        allInitialStarsStabilized = false;
                    }
                }
            });

            c.fadeVal = 1.0;

            if (allInitialStarsStabilized) {
                c.initialStars.forEach(idx => {
                    const s = c.stars[idx];
                    if (s) {
                        s.fadeVal = 1.0;
                        s.scaleFactor = 1.0;
                        s.brightnessFactor = 1.0;
                    }
                });
                c.state = 'constructing';
                c.taskIdx = 0;
                c.taskState = 'delay';
                c.delayDuration = 0.3 + Math.random() * 0.9;
                c.taskTimer = 0;
            }
        } else if (c.state === 'constructing') {
            c.stars.forEach(s => {
                s.twinklePhase = (s.twinklePhase + s.twinkleSpeed * 0.5 * dt) % (Math.PI * 2);
            });

            if (c.taskIdx >= c.constructionTasks.length) {
                c.state = 'shimmering';
                c.shimmerProgress = 0;
                return;
            }

            if (c.taskState === 'delay') {
                c.taskTimer += dt;
                if (c.taskTimer >= c.delayDuration) {
                    c.taskState = 'animating';
                    c.taskTimer = 0;
                    
                    // Initialize current task values
                    const currentTask = c.constructionTasks[c.taskIdx];
                    if (currentTask.type === 'star') {
                        const star = c.stars[currentTask.starIdx];
                        star.birthDuration = 1.5 + Math.random() * 2.5; // 1.5 - 4.0s
                        star.birthTimer = 0;
                        star.birthStartScale = 0.1 + Math.random() * 0.2;
                        star.fadeVal = 0;
                        star.scaleFactor = star.birthStartScale;
                        star.brightnessFactor = 0.0;
                        star.blurFactor = 1.0;
                    } else if (currentTask.type === 'line') {
                        const line = c.lines[currentTask.lineIdx];
                        line.drawDuration = 1.5 + Math.random() * 2.5; // 1.5 - 4.0s
                        line.drawTimer = 0;
                        line.drawVal = 0;
                    }
                }
            } else if (c.taskState === 'animating') {
                const currentTask = c.constructionTasks[c.taskIdx];
                if (currentTask.type === 'star') {
                    const star = c.stars[currentTask.starIdx];
                    star.birthTimer += dt;
                    const progress = Math.min(star.birthTimer / star.birthDuration, 1.0);
                    const t = progress * progress * (3 - 2 * progress); // smoothstep
                    
                    star.scaleFactor = star.birthStartScale + (1.0 - star.birthStartScale) * t;
                    star.fadeVal = t;
                    star.brightnessFactor = t;
                    star.blurFactor = 1.0;

                    if (star.birthTimer >= star.birthDuration) {
                        star.scaleFactor = 1.0;
                        star.fadeVal = 1.0;
                        star.brightnessFactor = 1.0;
                        
                        c.taskIdx++;
                        c.taskState = 'delay';
                        c.delayDuration = 0.3 + Math.random() * 0.9;
                        c.taskTimer = 0;
                    }
                } else if (currentTask.type === 'line') {
                    const line = c.lines[currentTask.lineIdx];
                    line.drawTimer += dt;
                    const progress = Math.min(line.drawTimer / line.drawDuration, 1.0);
                    line.drawVal = progress; // grows length 0% to 100%

                    if (line.drawTimer >= line.drawDuration) {
                        line.drawVal = 1.0;
                        
                        c.taskIdx++;
                        c.taskState = 'delay';
                        c.delayDuration = 0.3 + Math.random() * 0.9;
                        c.taskTimer = 0;
                    }
                }
            }
        } else if (c.state === 'shimmering') {
            c.stars.forEach(s => {
                s.twinklePhase = (s.twinklePhase + s.twinkleSpeed * dt) % (Math.PI * 2);
            });
            c.shimmerProgress += dt / 1.5; // Elegant 1.5 second shimmer duration
            if (c.shimmerProgress >= 1) {
                c.shimmerProgress = 1;
                c.state = 'idle';
                c.idleTimer = 10 + Math.random() * 10; // 10 to 20 seconds idle
            }
        } else if (c.state === 'idle') {
            c.stars.forEach(s => {
                s.twinklePhase = (s.twinklePhase + s.twinkleSpeed * dt) % (Math.PI * 2);
            });
            c.idleTimer -= dt;
            if (c.idleTimer <= 0) {
                c.state = 'fading_out';
                c.fadeVal = 1.0;
                c.replacementDelay = 2 + Math.random() * 6; // random delay 2-8 seconds
            }
        } else if (c.state === 'fading_out') {
            c.stars.forEach(s => {
                s.twinklePhase = (s.twinklePhase + s.twinkleSpeed * 1.5 * dt) % (Math.PI * 2);
            });
            c.fadeVal -= dt / c.fadeOutDuration; // Smooth 5-10s fade out

            // Separate fading rates for lines and stars during fading_out
            let starFade = Math.min(1.0, c.fadeVal / 0.7);

            // Scale and blur factors during death sequence
            c.stars.forEach(s => {
                s.scaleFactor = starFade;
                s.fadeVal = starFade;
                s.blurFactor = 1.0 + (1.0 - starFade) * 0.5; // slight blur increase
            });

            // Trigger replacement constellation with a delay
            if (!c.hasSpawnedReplacement) {
                c.replacementDelay -= dt;
                if (c.replacementDelay <= 0) {
                    c.hasSpawnedReplacement = true;
                    // Find an inactive replacement constellation in the pool for this layer!
                    const replacement = constellations.find(other => other.layer === c.layer && other.state === 'inactive');
                    if (replacement) {
                        initConstellation(replacement, false); // Start its birth sequence
                    }
                }
            }

            if (c.fadeVal <= 0) {
                c.fadeVal = 0;
                c.state = 'inactive';
            }
        }
    }

    function drawConstellation(c) {
        if (c.state === 'inactive') return;

        const { stars, template, baseX, baseY, progress, shimmerProgress, state, scale, rotationAngle, maxOpacity, fadeVal } = c;

        let scrollShiftY = -interpolatedScrollY * c.parallaxScroll;
        let px = smoothMouseOffsetX * c.parallaxMouse;
        let py = smoothMouseOffsetY * c.parallaxMouse;

        const cosRot = Math.cos(rotationAngle);
        const sinRot = Math.sin(rotationAngle);

        stars.forEach(s => {
            const scaledX = s.x * scale;
            const scaledY = s.y * scale;
            const rotX = scaledX * cosRot - scaledY * sinRot;
            const rotY = scaledX * sinRot + scaledY * cosRot;

            s.currX = baseX + rotX + px + c.driftX;
            s.currY = baseY + rotY + py + scrollShiftY + c.driftY;
        });

        // 1. Draw paths
        const lineCount = template.lines.length;

        // Separate fading rates for lines and stars during fading_out
        let lineFade = 1.0;
        if (state === 'fading_out') {
            // Lines fade out completely first (during the first 60% of the fade-out)
            lineFade = Math.max(0, (fadeVal - 0.4) / 0.6);
        }

        for (let i = 0; i < lineCount; i++) {
            const edge = template.lines[i];
            const starA = stars[edge[0]];
            const starB = stars[edge[1]];
            const ratio = c.lines[i].drawVal;

            if (ratio > 0) {
                // Lines fade in while being drawn (multiply by ratio), and fade out via lineFade
                const finalLineOpacity = maxOpacity * lineFade * ratio;

                bgCtx.beginPath();
                bgCtx.moveTo(starA.currX, starA.currY);
                bgCtx.lineTo(
                    starA.currX + (starB.currX - starA.currX) * ratio,
                    starA.currY + (starB.currY - starA.currY) * ratio
                );
                bgCtx.strokeStyle = `rgba(${c.theme.lineRGB.r}, ${c.theme.lineRGB.g}, ${c.theme.lineRGB.b}, ${0.12 * finalLineOpacity})`;
                bgCtx.lineWidth = 2.4 * scale;
                bgCtx.stroke();

                bgCtx.beginPath();
                bgCtx.moveTo(starA.currX, starA.currY);
                bgCtx.lineTo(
                    starA.currX + (starB.currX - starA.currX) * ratio,
                    starA.currY + (starB.currY - starA.currY) * ratio
                );
                bgCtx.strokeStyle = `rgba(${c.theme.lineCoreRGB.r}, ${c.theme.lineCoreRGB.g}, ${c.theme.lineCoreRGB.b}, ${0.36 * finalLineOpacity})`;
                bgCtx.lineWidth = 0.85 * scale;
                bgCtx.stroke();
            }
        }

        // 2. Shimmer pulse (triggered only once per completed constellation, during shimmering state)
        let activeShimmerLineIdx = -1;
        let lineRatio = 0;
        if (state === 'shimmering' && shimmerProgress > 0) {
            const pulsePos = shimmerProgress * lineCount;
            activeShimmerLineIdx = Math.floor(pulsePos);
            lineRatio = pulsePos % 1;

            if (activeShimmerLineIdx < lineCount) {
                const edge = template.lines[activeShimmerLineIdx];
                const starA = stars[edge[0]];
                const starB = stars[edge[1]];

                const pulseX = starA.currX + (starB.currX - starA.currX) * lineRatio;
                const pulseY = starA.currY + (starB.currY - starA.currY) * lineRatio;

                const pulseOpacity = maxOpacity;

                // Subtle, elegant shimmer completion effect: size 4*scale, opacity 0.5
                let pulseGrad = bgCtx.createRadialGradient(pulseX, pulseY, 0, pulseX, pulseY, 4 * scale);
                pulseGrad.addColorStop(0, `rgba(255, 255, 255, ${0.45 * pulseOpacity})`);
                pulseGrad.addColorStop(0.3, `rgba(${c.theme.glowRGB.r}, ${c.theme.glowRGB.g}, ${c.theme.glowRGB.b}, ${0.28 * pulseOpacity})`);
                pulseGrad.addColorStop(1, 'transparent');

                bgCtx.fillStyle = pulseGrad;
                bgCtx.beginPath();
                bgCtx.arc(pulseX, pulseY, 4 * scale, 0, Math.PI * 2);
                bgCtx.fill();
            }
        }

        // 3. Draw stars with advanced volumetric bloom & depth reaction
        stars.forEach(s => {
            if (s.fadeVal <= 0) return;

            let starSize = 1.6 * scale * (s.scaleFactor || 1.0) * (s.blurFactor || 1.0);
            let twinkleOp = 1.0;

            // Twinkle only when fully stabilized
            if (s.fadeVal >= 1.0 && s.scaleFactor >= 1.0 && (state === 'constructing' || state === 'shimmering' || state === 'idle' || state === 'fading_out')) {
                const rawVal = 0.5 + 0.35 * Math.sin(s.twinklePhase) + 0.15 * Math.cos(s.twinklePhase * 2.2 + s.seed);
                // Apply smoothstep easing: rawVal^2 * (3 - 2*rawVal)
                const tVal = rawVal * rawVal * (3 - 2 * rawVal);
                twinkleOp = tVal;
                starSize = (1.2 + 0.8 * tVal) * scale * (s.scaleFactor || 1.0) * (s.blurFactor || 1.0);
            }

            const starOpacity = maxOpacity * s.fadeVal * twinkleOp;

            let linePulseFactor = 0;
            if (activeShimmerLineIdx !== -1) {
                const edge = template.lines[activeShimmerLineIdx];
                if (edge[0] === s.starId) {
                    linePulseFactor = Math.max(0, 1.0 - lineRatio);
                } else if (edge[1] === s.starId) {
                    linePulseFactor = lineRatio;
                }
            }

            drawStarVolumetric(bgCtx, s.currX, s.currY, starSize, starOpacity, c.theme, c.layer, s.twinklePhase, s.seed, linePulseFactor);
        });
    }

    function drawSmallStar(star, dt) {
        star.driftAngle = (star.driftAngle + star.driftSpeed * 60 * dt) % (Math.PI * 2);
        const driftX = Math.sin(star.driftAngle) * 3;
        const driftY = Math.cos(star.driftAngle * 0.7) * 2;

        let drawX = (star.baseX + driftX + smoothMouseOffsetX * star.parallaxMouse) % width;
        if (drawX < 0) drawX += width;
        let drawY = (star.baseY + driftY + smoothMouseOffsetY * star.parallaxMouse - interpolatedScrollY * star.parallaxScroll) % height;
        if (drawY < 0) drawY += height;

        star.twinklePhase = (star.twinklePhase + star.twinkleSpeed * 60 * dt) % (Math.PI * 2);
        const rawVal = 0.625 + 0.275 * Math.sin(star.twinklePhase) + 0.1 * Math.cos(star.twinklePhase * 1.7 + star.seed);
        const twinkleVal = rawVal * rawVal * (3 - 2 * rawVal);
        
        updateStarLifecycle(star, dt);
        const currentOp = star.baseOpacity * twinkleVal * star.brightnessFactor;
        const currentSize = star.size * star.scaleFactor * star.blurFactor;

        drawStarVolumetric(bgCtx, drawX, drawY, currentSize, currentOp, star.theme, 'far', star.twinklePhase, star.seed);
    }

    function drawMediumStar(star, dt) {
        star.driftAngle = (star.driftAngle + star.driftSpeed * 60 * dt) % (Math.PI * 2);
        const driftX = Math.sin(star.driftAngle) * 8;
        const driftY = Math.cos(star.driftAngle * 0.7) * 6;

        let drawX = (star.baseX + driftX + smoothMouseOffsetX * star.parallaxMouse) % width;
        if (drawX < 0) drawX += width;
        let drawY = (star.baseY + driftY + smoothMouseOffsetY * star.parallaxMouse - interpolatedScrollY * star.parallaxScroll) % height;
        if (drawY < 0) drawY += height;

        star.twinklePhase = (star.twinklePhase + star.twinkleSpeed * 60 * dt) % (Math.PI * 2);
        const rawVal = 0.625 + 0.275 * Math.sin(star.twinklePhase) + 0.1 * Math.cos(star.twinklePhase * 1.7 + star.seed);
        const twinkleVal = rawVal * rawVal * (3 - 2 * rawVal);
        
        updateStarLifecycle(star, dt);
        const currentOp = star.baseOpacity * twinkleVal * star.brightnessFactor;
        const currentSize = star.size * star.scaleFactor * star.blurFactor;

        drawStarVolumetric(bgCtx, drawX, drawY, currentSize, currentOp, star.theme, 'middle', star.twinklePhase, star.seed);
    }

    let lastTime = performance.now();

    // Main animation loop
    function animateConstellations() {
        const now = performance.now();
        const dt = Math.min((now - lastTime) / 1000, 0.1); // clamp dt to max 100ms
        lastTime = now;

        // Easing mouse offsets (time-delta normalizer)
        const targetOffsetX = mouseX - width / 2;
        const targetOffsetY = mouseY - height / 2;
        const mouseEase = 1.0 - Math.exp(-2.4 * dt); // ~0.04 at 60fps
        smoothMouseOffsetX += (targetOffsetX - smoothMouseOffsetX) * mouseEase;
        smoothMouseOffsetY += (targetOffsetY - smoothMouseOffsetY) * mouseEase;

        // Scroll velocity computations
        const scrollEase = 1.0 - Math.exp(-4.8 * dt); // ~0.08 at 60fps
        interpolatedScrollY += (window.scrollY - interpolatedScrollY) * scrollEase;
        const scrollDelta = Math.abs(window.scrollY - lastScrollY);
        scrollVelocity += scrollDelta * 4.8 * dt;
        scrollVelocity = Math.min(scrollVelocity, 12);
        scrollVelocity *= Math.exp(-3.6 * dt); // deceleration
        lastScrollY = window.scrollY;

        // Clear canvas states
        bgCtx.clearRect(0, 0, width, height);
        fgCtx.clearRect(0, 0, width, height);

        // Fill background color
        bgCtx.fillStyle = "#05050a";
        bgCtx.fillRect(0, 0, width, height);

        // --- LAYER 1: NEBULA SYSTEM ---
        nebulaBlobs.forEach((blob) => {
            updateAtmosphereLifecycle(blob, dt);

            blob.angleX = (blob.angleX + blob.speedX * 60 * (1.0 + scrollVelocity * 0.3) * dt) % (Math.PI * 2);
            blob.angleY = (blob.angleY + blob.speedY * 60 * (1.0 + scrollVelocity * 0.3) * dt) % (Math.PI * 2);

            const driftX = Math.sin(blob.angleX) * (width * 0.08);
            const driftY = Math.cos(blob.angleY) * (height * 0.08);

            const scrollShiftY = -interpolatedScrollY * 0.02;

            let cx = (width * blob.baseXFraction + driftX + smoothMouseOffsetX * 0.01) % width;
            if (cx < 0) cx += width;
            let cy = (height * blob.baseYFraction + driftY + scrollShiftY + smoothMouseOffsetY * 0.01) % height;
            if (cy < 0) cy += height;

            const radius = Math.max(width, height) * blob.radiusFraction;
            let grad = bgCtx.createRadialGradient(cx, cy, 0, cx, cy, radius);

            grad.addColorStop(0, scaleRGBAOpacity(blob.color, blob.lifecycleFade));
            grad.addColorStop(0.5, scaleRGBAOpacity(blob.color, blob.lifecycleFade * 0.3));
            grad.addColorStop(1, "transparent");

            bgCtx.fillStyle = grad;
            bgCtx.beginPath();
            bgCtx.arc(cx, cy, radius, 0, Math.PI * 2);
            bgCtx.fill();
        });

        // Update active constellation drifts and cycles
        for (let i = 0; i < constellations.length; i++) {
            updateConstellation(constellations[i], dt);
        }

        // --- LAYER 2, 3, 4: DEEP PARALLAX ATMOSPHERIC PASSES ---

        // PASS A: Far depth
        drawHazeLayer(hazeLayers[0], dt);
        constellations.forEach(c => {
            if (c.layer === 'far') drawConstellation(c);
        });
        smallStars.forEach(s => drawSmallStar(s, dt));

        // PASS B: Mid depth
        drawHazeLayer(hazeLayers[1], dt);
        constellations.forEach(c => {
            if (c.layer === 'middle') drawConstellation(c);
        });
        medStars.forEach(s => drawMediumStar(s, dt));

        // PASS C: Near depth
        drawHazeLayer(hazeLayers[2], dt);
        constellations.forEach(c => {
            if (c.layer === 'near') drawConstellation(c);
        });

        // --- LAYER 5: Drifting FIREFLIES ---
        fireflies.forEach((ff) => {
            updateFireflyLifecycle(ff, dt);

            // Organic random-walk steering wandering updates
            ff.wobbleSeed = (ff.wobbleSeed + ff.wobbleSpeed * 60 * dt) % (Math.PI * 2);
            const angleDelta = (Math.sin(ff.wobbleSeed * 0.3) * 0.04 + (Math.random() - 0.5) * 0.05) * 60 * dt;
            ff.angle = (ff.angle + angleDelta) % (Math.PI * 2);

            const windX = Math.sin(ff.wobbleSeed) * 0.12 * 60 * ff.speedMult;
            const windY = Math.cos(ff.wobbleSeed * 0.8) * 0.12 * 60 * ff.speedMult;

            // Physical speed: 20px/s to 45px/s scaled by speedMult and brightnessFactor
            const wanderSpeed = (20 + ff.speedMult * 25) * ff.brightnessFactor;
            ff.targetVx = Math.cos(ff.angle) * wanderSpeed + windX * ff.brightnessFactor;
            ff.targetVy = Math.sin(ff.angle) * wanderSpeed + windY * ff.brightnessFactor;

            // Parallax screen positions
            let fx = ff.baseX + smoothMouseOffsetX * ff.parallaxFactor;
            let fy = ff.baseY + smoothMouseOffsetY * ff.parallaxFactor - interpolatedScrollY * ff.parallaxFactor;

            // Seamless wrap check based on final screen coordinates
            if (fx < -50) {
                ff.baseX += width + 100;
                fx += width + 100;
            } else if (fx > width + 50) {
                ff.baseX -= width + 100;
                fx -= width + 100;
            }

            if (fy < -50) {
                ff.baseY += height + 100;
                fy += height + 100;
            } else if (fy > height + 50) {
                ff.baseY -= height + 100;
                fy -= height + 100;
            }

            // Interactive hovering nearby repelling & opacity reduction
            const dx = fx - mouseX;
            const dy = fy - mouseY;
            const dist = Math.sqrt(dx * dx + dy * dy);
            
            let hoverFactor = 1.0;
            // Guard against division by zero and NaN states
            if (dist < 150 && dist > 0.1 && !isNaN(dist)) {
                const repelForce = Math.pow((150 - dist) / 150, 1.5);
                const forceMagnitude = repelForce * 120 * ff.speedMult;
                ff.targetVx += (dx / dist) * forceMagnitude;
                ff.targetVy += (dy / dist) * forceMagnitude;
                
                hoverFactor = 0.35 + 0.65 * (dist / 150);
            }

            // Converge velocity smoothly (accelRate = 4.0/s)
            ff.vx += (ff.targetVx - ff.vx) * 4.0 * dt;
            ff.vy += (ff.targetVy - ff.vy) * 4.0 * dt;

            // Position update
            ff.baseX += ff.vx * dt;
            ff.baseY += ff.vy * dt;

            ff.currentOpacity += (ff.baseOpacity * hoverFactor - ff.currentOpacity) * 6.0 * dt;
            const fireflyOpacity = ff.currentOpacity * 0.65 * ff.brightnessFactor;

            const color = fireflyColors[ff.colorIndex];
            const currentSize = ff.size * ff.scaleFactor * ff.blurFactor;

            let grad = bgCtx.createRadialGradient(fx, fy, 0, fx, fy, currentSize * 1.5);
            grad.addColorStop(0, `rgba(${color.r}, ${color.g}, ${color.b}, ${fireflyOpacity})`);
            grad.addColorStop(0.3, `rgba(${color.r}, ${color.g}, ${color.b}, ${fireflyOpacity * 0.4})`);
            grad.addColorStop(1, "transparent");

            bgCtx.fillStyle = grad;
            bgCtx.beginPath();
            bgCtx.arc(fx, fy, currentSize * 1.5, 0, Math.PI * 2);
            bgCtx.fill();
        });

        // --- LAYER 6: FOREGROUND BLURRED PARTICLES (Foreground Canvas) ---
        fgCtx.save();
        fgCtx.globalCompositeOperation = "screen";

        foregroundParticles.forEach((p) => {
            updateForegroundParticleLifecycle(p, dt);
            p.angle = (p.angle + p.speed * 60 * dt) % (Math.PI * 2);
            p.baseX += (p.vx * 60 + Math.sin(p.angle) * 0.03 * 60) * dt;
            p.baseY += (p.vy * 60 + Math.cos(p.angle) * 0.03 * 60) * dt;

            if (p.baseX < -150) p.baseX = width + 150;
            if (p.baseX > width + 150) p.baseX = -150;
            if (p.baseY < -150) p.baseY = height + 150;
            if (p.baseY > height + 150) p.baseY = -150;

            let finalX = (p.baseX + smoothMouseOffsetX * p.parallaxFactor) % width;
            if (finalX < 0) finalX += width;
            let finalY = (p.baseY + smoothMouseOffsetY * p.parallaxFactor - interpolatedScrollY * p.parallaxFactor) % height;
            if (finalY < 0) finalY += height;

            const baseOp = 0.055 * p.brightnessFactor;
            const currentSize = p.size * p.scaleFactor * p.blurFactor;

            let grad = fgCtx.createRadialGradient(finalX, finalY, 0, finalX, finalY, currentSize);
            grad.addColorStop(0, `rgba(169, 150, 255, ${baseOp})`);
            grad.addColorStop(0.5, `rgba(169, 150, 255, ${baseOp * 0.3})`);
            grad.addColorStop(1, "transparent");

            fgCtx.fillStyle = grad;
            fgCtx.beginPath();
            fgCtx.arc(finalX, finalY, currentSize, 0, Math.PI * 2);
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

