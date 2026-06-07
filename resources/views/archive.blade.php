<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Project Archive — {{ \App\Models\SiteSetting::get('site_title', 'Pradipta Portfolio') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>
<body id="top" class="inner-page loading-active">

@include('partials.loader')

<div class="bg-orb orb-1"></div>
<div class="bg-orb orb-2"></div>
<div class="bg-orb orb-3"></div>

@include('partials.navbar', ['activePage' => 'archive'])

<div class="spotlight"></div>
<div class="custom-cursor">
    <img src="/images/cursor/cursor.png" alt="">
</div>

<main class="inner-shell archive-shell">
    <section class="inner-hero reveal">
        <p class="inner-kicker">Project Archive</p>
        <div class="inner-hero-grid">
            <div>
                <h1>Complete work index.</h1>
                <p>
                    A deeper catalog of multimedia, visual production, and creative technology projects,
                    arranged for quick scanning as the collection grows.
                </p>
            </div>
            <div class="archive-stats">
                <span>{{ str_pad($projects->count(), 2, '0', STR_PAD_LEFT) }}</span>
                <small>published projects</small>
            </div>
        </div>
    </section>

    <section class="archive-toolbar reveal" aria-label="Archive browsing controls">
        <div class="archive-search-shell">
            <span>Search</span>
            <input type="search" id="archive-search" placeholder="Type to search projects..." aria-label="Search projects">
        </div>
        <div class="archive-filter-row">
            <button type="button" id="filter-all" class="archive-filter active" style="cursor: pointer;">All</button>
            
            <select id="filter-category" class="archive-filter" style="cursor: pointer; outline: none; border-radius: 999px;">
                <option value="" style="background: #09081a; color: white;">Category</option>
            </select>
            
            <select id="filter-tag" class="archive-filter" style="cursor: pointer; outline: none; border-radius: 999px;">
                <option value="" style="background: #09081a; color: white;">Technology</option>
            </select>
            
            <select id="filter-year" class="archive-filter" style="cursor: pointer; outline: none; border-radius: 999px;">
                <option value="" style="background: #09081a; color: white;">Year</option>
            </select>
        </div>
    </section>

    <section class="archive-list" aria-label="Project archive list">
        <div class="archive-list-head reveal">
            <span>Year</span>
            <span>Project</span>
            <span>Category</span>
            <span>Stack</span>
            <span>Links</span>
        </div>

        @forelse($projects as $project)
            @php
                $year = $project->year ?? optional($project->updated_at)->format('Y') ?? 'Now';
                $category = $project->category ?? 'Portfolio Work';
                $projectUrl = $project->project_url ?? $project->project_link;
                $githubUrl = $project->github_url ?? $project->github_link;
            @endphp

            <article class="archive-row reveal">
                <div class="archive-year">{{ $year }}</div>

                <div class="archive-project-main">
                    <h2>{{ $project->title }}</h2>
                    <p>{{ $project->description ?: 'Project details will be added from the CMS.' }}</p>
                </div>

                <div class="archive-category">{{ $category }}</div>

                <div class="archive-tech-list">
                    @forelse($project->technologies ?? [] as $tech)
                        <span>{{ $tech }}</span>
                    @empty
                        <span>CMS Ready</span>
                    @endforelse
                </div>

                <div class="archive-links">
                    @if($projectUrl)
                        <a href="{{ $projectUrl }}" target="_blank" rel="noopener">Live</a>
                    @endif

                    @if($githubUrl)
                        <a href="{{ $githubUrl }}" target="_blank" rel="noopener">GitHub</a>
                    @endif

                    @unless($projectUrl || $githubUrl)
                        <span>Queued</span>
                    @endunless
                </div>
            </article>
        @empty
            <div class="archive-empty">
                <span>Archive ready</span>
                <h2>No published projects yet.</h2>
                <p>Once projects are published from the admin dashboard, they will appear here automatically.</p>
            </div>
        @endforelse
    </section>
</main>

<div class="particles"></div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let filtersData = {};

    function renderProjects(projects) {
        const listContainer = document.querySelector('.archive-list');
        const head = listContainer.querySelector('.archive-list-head');
        
        listContainer.innerHTML = '';
        if (head) {
            listContainer.appendChild(head);
        }
        
        if (projects.length === 0) {
            const emptyEl = document.createElement('div');
            emptyEl.className = 'archive-empty';
            emptyEl.innerHTML = `
                <span>Archive ready</span>
                <h2>No published projects match the filter.</h2>
                <p>Try clearing your search query or selecting a different filter option.</p>
            `;
            listContainer.appendChild(emptyEl);
            return;
        }
        
        projects.forEach(project => {
            const year = project.year || (project.created_at ? new Date(project.created_at).getFullYear() : 'Now');
            const category = project.category || 'Portfolio Work';
            const projectUrl = project.project_url || project.project_link;
            const githubUrl = project.github_url || project.github_link;
            
            let techSpans = '';
            const techs = project.technologies || [];
            if (techs.length > 0) {
                techs.forEach(tech => {
                    techSpans += `<span>${tech}</span>`;
                });
            } else {
                techSpans = '<span>CMS Ready</span>';
            }
            
            let linkHtml = '';
            if (projectUrl) {
                linkHtml += `<a href="${projectUrl}" target="_blank" rel="noopener">Live</a>`;
            }
            if (githubUrl) {
                linkHtml += `<a href="${githubUrl}" target="_blank" rel="noopener">GitHub</a>`;
            }
            if (!projectUrl && !githubUrl) {
                linkHtml = '<span>Queued</span>';
            }
            
            const row = document.createElement('article');
            row.className = 'archive-row reveal';
            row.innerHTML = `
                <div class="archive-year">${year}</div>

                <div class="archive-project-main">
                    <h2>${project.title}</h2>
                    <p>${project.description || 'Project details will be added from the CMS.'}</p>
                </div>

                <div class="archive-category">${category}</div>

                <div class="archive-tech-list">
                    ${techSpans}
                </div>

                <div class="archive-links">
                    ${linkHtml}
                </div>
            `;
            listContainer.appendChild(row);
            
            // Premium hover micro-interaction link cursor scaling
            const dynamicHoverItems = row.querySelectorAll('a, span');
            dynamicHoverItems.forEach((item) => {
                item.addEventListener("mouseenter", () => {
                    const cursor = document.querySelector(".custom-cursor");
                    if (cursor) {
                        cursor.style.transform = "translate(-50%, -50%) scale(1.5)";
                    }
                });

                item.addEventListener("mouseleave", () => {
                    const cursor = document.querySelector(".custom-cursor");
                    if (cursor) {
                        cursor.style.transform = "translate(-50%, -50%) scale(1)";
                    }
                });
            });
        });
    }

    function populateFilters(filters) {
        const catSelect = document.getElementById('filter-category');
        const tagSelect = document.getElementById('filter-tag');
        const yearSelect = document.getElementById('filter-year');
        
        filters.categories.forEach(cat => {
            const opt = document.createElement('option');
            opt.value = cat;
            opt.textContent = cat;
            opt.style.background = '#09081a';
            opt.style.color = 'white';
            catSelect.appendChild(opt);
        });
        
        filters.tags.forEach(tag => {
            const opt = document.createElement('option');
            opt.value = tag;
            opt.textContent = tag;
            opt.style.background = '#09081a';
            opt.style.color = 'white';
            tagSelect.appendChild(opt);
        });
        
        filters.years.forEach(year => {
            const opt = document.createElement('option');
            opt.value = year;
            opt.textContent = year;
            opt.style.background = '#09081a';
            opt.style.color = 'white';
            yearSelect.appendChild(opt);
        });
    }

    function updateActiveStates() {
        const allBtn = document.getElementById('filter-all');
        const catSelect = document.getElementById('filter-category');
        const tagSelect = document.getElementById('filter-tag');
        const yearSelect = document.getElementById('filter-year');
        
        const isAnyFilterActive = catSelect.value || tagSelect.value || yearSelect.value;
        
        if (isAnyFilterActive) {
            allBtn.classList.remove('active');
        } else {
            allBtn.classList.add('active');
        }
        
        if (catSelect.value) catSelect.classList.add('active');
        else catSelect.classList.remove('active');
        
        if (tagSelect.value) tagSelect.classList.add('active');
        else tagSelect.classList.remove('active');
        
        if (yearSelect.value) yearSelect.classList.add('active');
        else yearSelect.classList.remove('active');
    }

    async function fetchArchiveData() {
        const searchVal = document.getElementById('archive-search').value;
        const catVal = document.getElementById('filter-category').value;
        const tagVal = document.getElementById('filter-tag').value;
        const yearVal = document.getElementById('filter-year').value;
        
        const params = new URLSearchParams();
        if (searchVal) params.append('q', searchVal);
        if (catVal) params.append('category', catVal);
        if (tagVal) params.append('tag', tagVal);
        if (yearVal) params.append('year', yearVal);
        
        try {
            const response = await fetch(`/api/archive/projects?${params.toString()}`);
            const result = await response.json();
            if (result.success) {
                renderProjects(result.projects);
                
                const countBadge = document.querySelector('.archive-stats span');
                if (countBadge) {
                    countBadge.textContent = String(result.projects.length).padStart(2, '0');
                }
                
                if (Object.keys(filtersData).length === 0) {
                    filtersData = result.filters;
                    populateFilters(result.filters);
                }
            }
        } catch (e) {
            console.error("Error loading archive projects:", e);
        }
    }

    // Attach listeners
    document.getElementById('archive-search').addEventListener('input', () => {
        fetchArchiveData();
    });

    document.getElementById('filter-category').addEventListener('change', () => {
        updateActiveStates();
        fetchArchiveData();
    });

    document.getElementById('filter-tag').addEventListener('change', () => {
        updateActiveStates();
        fetchArchiveData();
    });

    document.getElementById('filter-year').addEventListener('change', () => {
        updateActiveStates();
        fetchArchiveData();
    });

    document.getElementById('filter-all').addEventListener('click', () => {
        document.getElementById('archive-search').value = '';
        document.getElementById('filter-category').value = '';
        document.getElementById('filter-tag').value = '';
        document.getElementById('filter-year').value = '';
        updateActiveStates();
        fetchArchiveData();
    });

    // Run initial fetch
    fetchArchiveData();
});
</script>

</body>
</html>
