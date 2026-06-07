<!-- Retro Pixel-Art Loader Overlay -->
<script>
    (function() {
        const isReload = (window.performance && ((window.performance.getEntriesByType && window.performance.getEntriesByType("navigation")[0] && window.performance.getEntriesByType("navigation")[0].type === "reload") || (window.performance.navigation && window.performance.navigation.type === 1)));
        if (sessionStorage.getItem('portfolioLoaded') && !isReload) {
            document.documentElement.classList.add('loader-skipped');
            if (document.readyState === 'complete' || document.readyState === 'interactive') {
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        document.documentElement.classList.add('page-transition-ready');
                    });
                });
            } else {
                window.addEventListener('DOMContentLoaded', () => {
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => {
                            document.documentElement.classList.add('page-transition-ready');
                        });
                    });
                });
            }
        }
    })();
</script>
<div id="retro-loader" class="retro-loader-overlay">
    <div class="loader-bg-stars"></div>
    <div class="loader-box">
        <div class="pixel-corners"></div>
        <div class="loader-content">
            <div class="pixel-text loader-title-text">Pradipta Creative Portofolio</div>
            <div id="loader-status-text" class="pixel-text status-text">Loading...</div>
            
            <!-- Constellation Progress Bar -->
            <div class="constellation-progress-bar">
                <span class="constellation-node" data-index="1">●</span>
                <span class="constellation-line" data-index="1">──</span>
                <span class="constellation-node" data-index="2">●</span>
                <span class="constellation-line" data-index="2">──</span>
                <span class="constellation-node" data-index="3">●</span>
                <span class="constellation-line" data-index="3">──</span>
                <span class="constellation-node" data-index="4">●</span>
                <span class="constellation-line" data-index="4">──</span>
                <span class="constellation-node" data-index="5">●</span>
                <span class="constellation-line" data-index="5">──</span>
                <span class="constellation-node" data-index="6">●</span>
                <span class="constellation-line" data-index="6">──</span>
                <span class="constellation-node" data-index="7">●</span>
                <span class="constellation-line" data-index="7">──</span>
                <span class="constellation-node" data-index="8">●</span>
            </div>
            
            <div id="loader-sub-text" class="pixel-text sub-text"></div>
        </div>
    </div>
</div>
