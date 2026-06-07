@extends('admin.layout')

@section('title', 'Edit Project')
@section('page-title', 'Edit Project')

@section('breadcrumb')
    <a href="{{ route('admin.projects.index') }}">Projects</a>
    <span>&rarr;</span> <span class="active">Edit Project</span>
@endsection

@section('content')
    @if($errors->any())
        <div class="flash-message" style="margin-bottom: 24px; padding: 12px 16px; background: rgba(255,59,48,0.15); border: 1px solid rgba(255,59,48,0.25); color: #ff453a; border-radius: 10px; font-weight: 600;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.projects.update', $project) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="cms-form-grid" style="grid-template-columns: 1.2fr 1fr; gap: 24px;">
            <!-- Left Column: Core Fields -->
            <div class="admin-card cms-card-section">
                <h2 style="font-size: 1.15rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    🚀 Project Details
                </h2>

                <div class="cms-form-group">
                    <label for="title">Project Title</label>
                    <input class="cms-input" id="title" type="text" name="title" value="{{ old('title', $project->title) }}" required placeholder="e.g. Interactive Wildlife Park">
                </div>

                <div class="cms-form-group">
                    <label for="category">Category</label>
                    <input class="cms-input" id="category" type="text" name="category" value="{{ old('category', $project->category) }}" placeholder="e.g. Game Development, Photography">
                </div>

                <div class="cms-form-group">
                    <label for="year">Display Year / Period</label>
                    <input class="cms-input" id="year" type="text" name="year" value="{{ old('year', $project->year) }}" placeholder="e.g. 2026 or 2025 - 2026">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="cms-form-group">
                        <label for="start_date">Start Date</label>
                        <input class="cms-input" id="start_date" type="date" name="start_date" value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}">
                    </div>
                    <div class="cms-form-group">
                        <label for="end_date">End Date</label>
                        <input class="cms-input" id="end_date" type="date" name="end_date" value="{{ old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '') }}">
                    </div>
                </div>

                <div class="cms-form-group">
                    <label for="project_link">Live Project / Demo URL</label>
                    <input class="cms-input" id="project_link" type="url" name="project_link" value="{{ old('project_link', $project->project_link) }}" placeholder="https://example.com">
                </div>

                <div class="cms-form-group">
                    <label for="github_link">GitHub Repository URL</label>
                    <input class="cms-input" id="github_link" type="url" name="github_link" value="{{ old('github_link', $project->github_link) }}" placeholder="https://github.com/username/repo">
                </div>

                <div class="cms-form-group">
                    <label for="position">Display Order Position</label>
                    <input class="cms-input" id="position" type="number" name="position" value="{{ old('position', $project->position) }}" required>
                </div>

                <div class="cms-form-group">
                    <label for="description">Project Description</label>
                    <textarea class="cms-textarea" id="description" name="description" placeholder="Write project description here..." style="min-height: 120px;">{{ old('description', $project->description) }}</textarea>
                </div>
            </div>

            <!-- Right Column: Media, Tags, Publishing & Preview -->
            <div class="admin-card cms-card-section" style="justify-content: space-between;">
                <div>
                    <h2 style="font-size: 1.15rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        🖼️ Media & Tags
                    </h2>

                    <!-- Thumbnail -->
                    <div class="cms-form-group">
                        <label>Thumbnail Photo</label>
                        <div style="display: flex; align-items: center; gap: 16px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 12px; border-radius: 12px;">
                            <img id="thumb-preview" src="{{ $project->thumbnail ? asset($project->thumbnail) : asset('images/projects/wildlife.png') }}" alt="Thumbnail Preview" style="width: 80px; height: 50px; border-radius: 6px; object-fit: cover; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2);">
                            <div>
                                <input type="file" name="thumbnail_file" id="thumbnail_file" style="display: none;" accept="image/*" onchange="previewThumb(this)">
                                <button type="button" class="action-btn" onclick="document.getElementById('thumbnail_file').click()" style="margin-bottom: 4px;">Change Thumbnail</button>
                                <div style="font-size: 0.7rem; color: rgba(255,255,255,0.4)">Max 5MB. PNG, JPG or WEBP.</div>
                            </div>
                        </div>
                    </div>

                    <!-- Gallery List -->
                    <div class="cms-form-group" style="margin-top: 12px;">
                        <label>Current Gallery Images (Check to Delete)</label>
                        <div class="media-preview-container">
                            @if(is_array($project->gallery_images) && count($project->gallery_images) > 0)
                                @foreach($project->gallery_images as $img)
                                    <div class="media-preview-item">
                                        <img src="{{ asset($img) }}" alt="Gallery Image">
                                        <label class="remove-check">
                                            <input type="checkbox" name="remove_gallery[]" value="{{ $img }}" style="cursor: pointer;">
                                            Delete
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <span style="color: rgba(255,255,255,0.4); font-size: 0.8rem; grid-column: 1/-1;">No images in gallery.</span>
                            @endif
                        </div>
                    </div>

                    <!-- Gallery Upload -->
                    <div class="cms-form-group" style="margin-top: 12px;">
                        <label>Add New Gallery Images</label>
                        <input class="cms-input" type="file" name="gallery_files[]" accept="image/*" multiple style="padding: 10px !important;">
                        <div style="font-size: 0.7rem; color: rgba(255,255,255,0.4); margin-top: 2px;">Hold Ctrl to choose multiple images. Max 5MB per file.</div>
                    </div>

                    <!-- Tags -->
                    <div class="cms-form-group" style="margin-top: 12px;">
                        <label>Centralized Tags</label>
                        <div style="max-height: 120px; overflow-y: auto; border: 1px solid rgba(255,255,255,0.08); padding: 12px; border-radius: 12px; background: rgba(0,0,0,0.15); display: flex; flex-direction: column; gap: 8px;">
                            @forelse($tags as $tag)
                                @php $checked = in_array($tag->id, $projectTagIds) ? 'checked' : ''; @endphp
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; user-select: none;">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ $checked }} style="width: 16px; height: 16px; accent-color: #7b61ff; cursor: pointer;">
                                    <span style="font-size: 0.85rem; color: rgba(255,255,255,0.85)">{{ $tag->name }}</span>
                                </label>
                            @empty
                                <span style="color: rgba(255,255,255,0.4); font-size: 0.82rem;">No tags available. Write new ones below.</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="cms-form-group" style="margin-top: 12px;">
                        <label for="new_tags">Create New Tags (comma-separated)</label>
                        <input class="cms-input" id="new_tags" type="text" name="new_tags" value="{{ old('new_tags') }}" placeholder="e.g. C#, Unity, WebGL">
                    </div>

                    <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 12px 0;">

                    <!-- Toggles -->
                    <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="featured" value="1" {{ old('featured', $project->featured) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: #7b61ff; cursor: pointer;">
                            <span style="font-size: 0.85rem; font-weight: 600; color: white;">Featured (Show on Homepage)</span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="archived" value="1" {{ old('archived', $project->archived) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: #7b61ff; cursor: pointer;">
                            <span style="font-size: 0.85rem; font-weight: 600; color: white;">Archive this project</span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $project->is_published) ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: #7b61ff; cursor: pointer;">
                            <span style="font-size: 0.85rem; font-weight: 600; color: white;">Published</span>
                        </label>
                    </div>

                    <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 12px 0;">

                    <!-- Dynamic Card Preview -->
                    <div class="cms-form-group">
                        <label>Live Preview (Homepage Style)</label>
                        <div style="background: #05050a; padding: 20px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.06); position: relative; overflow: hidden; isolation: isolate; margin-top: 6px;">
                            <div class="card" style="padding: 0; background: transparent; display: flex; gap: 16px; align-items: flex-start;">
                                <img id="preview-thumb-img" src="{{ $project->thumbnail ? asset($project->thumbnail) : asset('images/projects/wildlife.png') }}" alt="Project Image" class="project-image" style="width: 100px; height: 65px; border-radius: 8px; object-fit: cover;">
                                <div class="card-content" style="flex: 1;">
                                    <h3 id="preview-title" class="project-title" style="font-size: 0.95rem; font-weight: 700; color: white; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;"></h3>
                                    <div class="description-wrapper">
                                        <p id="preview-desc" class="description-text" style="font-size: 0.8rem; color: rgba(255,255,255,0.62); line-height: 1.5; margin-bottom: 10px;"></p>
                                    </div>
                                    <div class="tags" id="preview-tags"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 20px;">
                    <button type="submit" class="btn-primary-cms" style="flex: 1;">
                        Save Changes
                    </button>
                    <a href="{{ route('admin.projects.index') }}" class="btn-secondary-cms" style="text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

    <script>
        function updatePreview() {
            const titleVal = document.getElementById('title').value || 'Project Title';
            const descVal = document.getElementById('description').value || 'Project description...';
            
            document.getElementById('preview-title').innerHTML = titleVal + ' <span class="arrow" style="font-size:0.8rem;">↗</span>';
            document.getElementById('preview-desc').innerText = descVal;
            
            const tagsContainer = document.getElementById('preview-tags');
            tagsContainer.innerHTML = '';
            
            // Centralized checked tags
            const checkedTags = document.querySelectorAll('input[name="tags[]"]:checked');
            checkedTags.forEach(chk => {
                const labelText = chk.nextElementSibling.innerText;
                const span = document.createElement('span');
                span.innerText = labelText;
                tagsContainer.appendChild(span);
            });
            
            // New tags typed
            const newTags = document.getElementById('new_tags').value;
            if (newTags) {
                newTags.split(',').map(t => t.trim()).filter(t => t.length > 0).forEach(t => {
                    const span = document.createElement('span');
                    span.innerText = t;
                    tagsContainer.appendChild(span);
                });
            }
        }

        function previewThumb(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('thumb-preview').src = e.target.result;
                    document.getElementById('preview-thumb-img').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            ['title', 'description', 'new_tags'].forEach(id => {
                document.getElementById(id).addEventListener('input', updatePreview);
            });

            document.querySelectorAll('input[name="tags[]"]').forEach(chk => {
                chk.addEventListener('change', updatePreview);
            });

            updatePreview();
        });
    </script>
@endsection
