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

let particlesOverlayContainer = document.querySelector(".particles-overlay");
if (!particlesOverlayContainer && particlesContainer) {
    particlesOverlayContainer = document.createElement("div");
    particlesOverlayContainer.classList.add("particles", "particles-overlay");
    particlesOverlayContainer.style.zIndex = "15";
    particlesOverlayContainer.style.pointerEvents = "none";
    document.body.appendChild(particlesOverlayContainer);
}

const particles = [];

let isMobile = window.innerWidth <= 768;
let isTablet = window.innerWidth > 768 && window.innerWidth <= 1024;
let LAYER_1_COUNT = isMobile ? 6 : (isTablet ? 10 : 16);
let LAYER_2_COUNT = isMobile ? 8 : (isTablet ? 14 : 22);
let LAYER_3_COUNT = isMobile ? 4 : (isTablet ? 8 : 12);

const FIREFLY_PALETTE = [
    { r: 108, g: 77, b: 255 },  // 0: Deep Violet
    { r: 139, g: 92, b: 255 },  // 1: Electric Purple
    { r: 167, g: 123, b: 255 }, // 2: Soft Lavender
    { r: 198, g: 107, b: 255 }, // 3: Pinkish Purple
    { r: 217, g: 92, b: 255 },  // 4: Orchid
    { r: 224, g: 82, b: 255 },  // 5: Magenta Violet
    { r: 242, g: 91, b: 255 }   // 6: Soft Neon Magenta
];

function getLayerColorIndex(layer) {
    let rand = Math.random();
    if (layer === 1) {
        return rand < 0.6 ? 0 : 1;
    } else if (layer === 2) {
        if (rand < 0.4) return 1;
        if (rand < 0.7) return 2;
        return 3;
    } else {
        if (rand < 0.4) return 3;
        if (rand < 0.7) return 4;
        if (rand < 0.9) return 5;
        return 6;
    }
}

function createParticle(layer) {
    const particle = document.createElement("div");
    particle.classList.add("particle");
    
    let size, opacity, blur, speedMult, parallaxFactor, isOverlay = false;
    
    if (layer === 1) {
        size = 3 + Math.random() * 5;
        opacity = 0.35 + Math.random() * 0.2;
        blur = 1 + Math.random() * 2;
        speedMult = 0.4;
        parallaxFactor = 0.15;
    } else if (layer === 2) {
        size = 12 + Math.random() * 18;
        opacity = 0.45 + Math.random() * 0.35;
        blur = 3 + Math.random() * 4;
        speedMult = 1.4;
        parallaxFactor = 0.8;
    } else {
        size = 60 + Math.random() * 40;
        opacity = 0.4 + Math.random() * 0.4;
        blur = 18 + Math.random() * 22;
        speedMult = 2.5 + Math.random() * 1;
        parallaxFactor = 3.0;
        isOverlay = Math.random() > 0.3;
    }

    if (isOverlay) {
        if (particlesOverlayContainer) particlesOverlayContainer.appendChild(particle);
        particle.style.mixBlendMode = "screen";
    } else {
        if (particlesContainer) particlesContainer.appendChild(particle);
    }
    
    const baseColorIndex = getLayerColorIndex(layer);
    const color = FIREFLY_PALETTE[baseColorIndex];

    const obj = {
        el: particle,
        layer: layer,
        baseX: Math.random() * window.innerWidth,
        baseY: Math.random() * window.innerHeight,
        vx: (Math.random() - 0.5) * 0.5 * speedMult,
        vy: (Math.random() - 0.5) * 0.5 * speedMult,
        targetVx: 0,
        targetVy: 0,
        size: size,
        angle: Math.random() * Math.PI * 2,
        speed: (0.002 + Math.random() * 0.003) * speedMult,
        baseOpacity: opacity,
        currentOpacity: 0,
        baseBlur: blur,
        currentBlur: blur,
        parallaxFactor: parallaxFactor,
        speedMult: speedMult,
        isOverlay: isOverlay,
        checkOffset: Math.floor(Math.random() * 10),
        overlapTargetMult: 1,
        overlapOpacityMult: 1,
        wobbleSeed: Math.random() * 1000,
        wobbleSpeed: 0.005 + Math.random() * 0.01,
        isHero: false,
        scale: 1,
        colorIndex: baseColorIndex,
        targetColorIndex: baseColorIndex,
        colorNextChangeTime: Date.now() + 10000 + Math.random() * 10000,
        currentColor: { r: color.r, g: color.g, b: color.b }
    };

    particle.style.width = `${obj.size}px`;
    particle.style.height = `${obj.size}px`;
    particle.style.opacity = obj.currentOpacity;
    particle.style.filter = `blur(${obj.baseBlur}px)`;
    particle.style.setProperty('--p-r', obj.currentColor.r);
    particle.style.setProperty('--p-g', obj.currentColor.g);
    particle.style.setProperty('--p-b', obj.currentColor.b);
    
    return obj;
}

if (particlesContainer) {
    for(let i = 0; i < LAYER_1_COUNT; i++) particles.push(createParticle(1));
    for(let i = 0; i < LAYER_2_COUNT; i++) particles.push(createParticle(2));
    for(let i = 0; i < LAYER_3_COUNT; i++) particles.push(createParticle(3));
}

let parallaxMouseX = window.innerWidth / 2;
let parallaxMouseY = window.innerHeight / 2;
let frameCount = 0;
let lastHeroTime = Date.now();

function triggerHeroMoment() {
    const foregroundParticles = particles.filter(p => p.layer === 3);
    if (foregroundParticles.length > 0) {
        const p = foregroundParticles[Math.floor(Math.random() * foregroundParticles.length)];
        p.isHero = true;
        p.targetColorIndex = Math.random() > 0.5 ? 5 : 6;
        setTimeout(() => { 
            p.isHero = false; 
            p.targetColorIndex = p.colorIndex;
        }, 1500 + Math.random() * 1000);
    }
}

function animateParticles(){
    frameCount++;
    parallaxMouseX += (mouseX - parallaxMouseX) * 0.05;
    parallaxMouseY += (mouseY - parallaxMouseY) * 0.05;
    
    const centerX = window.innerWidth / 2;
    const centerY = window.innerHeight / 2;
    const parallaxOffsetX = (centerX - parallaxMouseX);
    const parallaxOffsetY = (centerY - parallaxMouseY);

    if (Date.now() - lastHeroTime > 6000 + Math.random() * 4000) {
        triggerHeroMoment();
        lastHeroTime = Date.now();
    }

    particles.forEach((p) => {
        p.angle += p.speed;
        p.wobbleSeed += p.wobbleSpeed;
        
        const windX = Math.sin(p.wobbleSeed) * 0.35 * p.speedMult;
        const windY = Math.cos(p.wobbleSeed * 0.8) * 0.35 * p.speedMult;
        
        p.targetVx = Math.cos(p.angle) * 0.25 * p.speedMult + windX;
        p.targetVy = Math.sin(p.angle) * 0.25 * p.speedMult + windY;
        
        const screenX = p.baseX + (parallaxOffsetX * p.parallaxFactor) * 0.05;
        const screenY = p.baseY + (parallaxOffsetY * p.parallaxFactor) * 0.05;
        
        const dx = screenX - mouseX;
        const dy = screenY - mouseY;
        const distance = Math.sqrt(dx * dx + dy * dy);

        let interactionScale = 1;
        if(distance < 300){
            const force = Math.pow((300 - distance) / 300, 2);
            p.targetVx += (dx / distance) * force * 0.6 * p.speedMult;
            p.targetVy += (dy / distance) * force * 0.6 * p.speedMult;
            interactionScale = 1 + force * 0.4;
        }

        p.vx += (p.targetVx - p.vx) * 0.02;
        p.vy += (p.targetVy - p.vy) * 0.02;

        p.baseX += p.vx;
        p.baseY += p.vy;

        if(p.baseX < -200) p.baseX = window.innerWidth + 200;
        if(p.baseX > window.innerWidth + 200) p.baseX = -200;
        if(p.baseY < -200) p.baseY = window.innerHeight + 200;
        if(p.baseY > window.innerHeight + 200) p.baseY = -200;

        const finalX = p.baseX + (parallaxOffsetX * p.parallaxFactor) * 0.05;
        const finalY = p.baseY + (parallaxOffsetY * p.parallaxFactor) * 0.05;

        if (p.isOverlay && frameCount % 10 === p.checkOffset) {
            if (finalX >= 0 && finalX <= window.innerWidth && finalY >= 0 && finalY <= window.innerHeight) {
                const el = document.elementFromPoint(finalX, finalY);
                if (el) {
                    const tag = el.tagName.toLowerCase();
                    const textTags = ['h1','h2','h3','h4','p','a','span','strong','em','button'];
                    const isTextElement = textTags.includes(tag) || el.closest('a') || el.closest('.card-content') || el.closest('.name');
                    p.overlapTargetMult = isTextElement ? 0.3 : 1;
                }
            } else {
                p.overlapTargetMult = 1;
            }
        }
        
        p.overlapOpacityMult += (p.overlapTargetMult - p.overlapOpacityMult) * 0.05;

        let targetOp = p.baseOpacity * p.overlapOpacityMult * interactionScale;
        let targetScale = 1;
        
        if (p.isHero) {
            targetOp = Math.min(1, targetOp + 0.2);
            p.currentBlur += (Math.max(4, p.baseBlur - 8) - p.currentBlur) * 0.05;
            targetScale = 1.1;
        } else {
            p.currentBlur += (p.baseBlur - p.currentBlur) * 0.02;
            targetScale = 1;
        }
        
        p.scale += (targetScale - p.scale) * 0.05;
        p.currentOpacity += (targetOp - p.currentOpacity) * 0.05;
        
        const now = Date.now();
        if (now > p.colorNextChangeTime && !p.isHero) {
            let newIndex = p.colorIndex;
            if (Math.random() > 0.5) {
                newIndex = Math.min(p.colorIndex + 1, FIREFLY_PALETTE.length - 1);
            } else {
                newIndex = Math.max(p.colorIndex - 1, 0);
            }
            
            let allowedMin = 0;
            let allowedMax = 6;
            if (p.layer === 1) { allowedMin = 0; allowedMax = 1; }
            else if (p.layer === 2) { allowedMin = 1; allowedMax = 3; }
            else if (p.layer === 3) { allowedMin = 3; allowedMax = 6; }
            
            if (newIndex >= allowedMin && newIndex <= allowedMax) {
                p.targetColorIndex = newIndex;
                p.colorIndex = newIndex;
            }
            p.colorNextChangeTime = now + 10000 + Math.random() * 10000;
        }

        const targetColor = FIREFLY_PALETTE[p.targetColorIndex];
        p.currentColor.r += (targetColor.r - p.currentColor.r) * 0.005;
        p.currentColor.g += (targetColor.g - p.currentColor.g) * 0.005;
        p.currentColor.b += (targetColor.b - p.currentColor.b) * 0.005;
        
        p.el.style.transform = `translate3d(${finalX}px, ${finalY}px, 0) scale(${p.scale})`;
        p.el.style.opacity = p.currentOpacity;
        p.el.style.filter = `blur(${p.currentBlur}px)`;
        p.el.style.setProperty('--p-r', Math.round(p.currentColor.r));
        p.el.style.setProperty('--p-g', Math.round(p.currentColor.g));
        p.el.style.setProperty('--p-b', Math.round(p.currentColor.b));
    });

    requestAnimationFrame(animateParticles);
}

if (particlesContainer) {
    animateParticles();
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

// Initialize tag chips interaction
if (document.readyState === "complete") {
    initTagChips();
} else {
    window.addEventListener("load", initTagChips);
}

