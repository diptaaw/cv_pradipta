@extends('admin.layout')

@section('title', 'Create Experience')
@section('page-title', 'New Experience')

@section('breadcrumb')
    <a href="{{ route('admin.experiences.index') }}">Experiences</a>
    <span>&rarr;</span> <span class="active">New Experience</span>
@endsection

@section('content')
    @if($errors->any())
        <div class="flash-message" style="margin-bottom: 24px; padding: 12px 16px; background: rgba(255,59,48,0.15); border: 1px solid rgba(255,59,48,0.25); color: #ff453a; border-radius: 10px; font-weight: 600;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.experiences.store') }}">
        @csrf

        <div class="cms-form-grid" style="grid-template-columns: 1.2fr 1fr; gap: 24px;">
            <!-- Left Column: Basic Information -->
            <div class="admin-card cms-card-section">
                <h2 style="font-size: 1.15rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    💼 Basic Information
                </h2>

                <div class="cms-form-group">
                    <label for="organization">Organization / Company Name</label>
                    <input class="cms-input" id="organization" type="text" name="organization" value="{{ old('organization') }}" required placeholder="e.g. HIMA Multimedia Broadcasting PENS">
                </div>

                <div class="cms-form-group">
                    <label for="title">Position / Role Title</label>
                    <input class="cms-input" id="title" type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Staff PSDM">
                </div>

                <div class="cms-form-group">
                    <label for="year">Display Period / Year</label>
                    <input class="cms-input" id="year" type="text" name="year" value="{{ old('year') }}" required placeholder="e.g. 2025 — PRESENT">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="cms-form-group">
                        <label for="start_date">Start Date</label>
                        <input class="cms-input" id="start_date" type="date" name="start_date" value="{{ old('start_date') }}">
                    </div>
                    <div class="cms-form-group">
                        <label for="end_date">End Date</label>
                        <input class="cms-input" id="end_date" type="date" name="end_date" value="{{ old('end_date') }}">
                    </div>
                </div>

                <div class="cms-form-group">
                    <label for="position">Display Order Position</label>
                    <input class="cms-input" id="position" type="number" name="position" value="{{ old('position', 0) }}" required>
                </div>

                <div class="cms-form-group">
                    <label for="description">Role Description / Contribution</label>
                    <textarea class="cms-textarea" id="description" name="description" placeholder="Write description of your roles and achievements..." style="min-height: 120px;">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Right Column: Tags, Publishing & Visual Preview -->
            <div class="admin-card cms-card-section" style="justify-content: space-between;">
                <div>
                    <h2 style="font-size: 1.15rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        🏷️ Tags & Publishing
                    </h2>

                    <div class="cms-form-group">
                        <label>Centralized Tags</label>
                        <div style="max-height: 140px; overflow-y: auto; border: 1px solid rgba(255,255,255,0.08); padding: 12px; border-radius: 12px; background: rgba(0,0,0,0.15); display: flex; flex-direction: column; gap: 8px;">
                            @forelse($tags as $tag)
                                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; user-select: none;">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" style="width: 16px; height: 16px; accent-color: #7b61ff; cursor: pointer;">
                                    <span style="font-size: 0.85rem; color: rgba(255,255,255,0.85)">{{ $tag->name }}</span>
                                </label>
                            @empty
                                <span style="color: rgba(255,255,255,0.4); font-size: 0.82rem;">No tags available. Write new ones below.</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="cms-form-group" style="margin-top: 12px;">
                        <label for="new_tags">Create New Tags (comma-separated)</label>
                        <input class="cms-input" id="new_tags" type="text" name="new_tags" value="{{ old('new_tags') }}" placeholder="e.g. Leadership, Coordination">
                    </div>

                    <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 12px 0;">

                    <div style="display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} style="width: 16px; height: 16px; accent-color: #7b61ff; cursor: pointer;">
                            <span style="font-size: 0.85rem; font-weight: 600; color: white;">Featured (Show on Homepage)</span>
                        </label>

                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="is_published" value="1" checked style="width: 16px; height: 16px; accent-color: #7b61ff; cursor: pointer;">
                            <span style="font-size: 0.85rem; font-weight: 600; color: white;">Published immediately</span>
                        </label>
                    </div>

                    <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 12px 0;">

                    <!-- Dynamic Card Preview -->
                    <div class="cms-form-group">
                        <label>Live Preview (Homepage Style)</label>
                        <div style="background: #05050a; padding: 20px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.06); position: relative; overflow: hidden; isolation: isolate; margin-top: 6px;">
                            <div class="card" style="padding: 0; background: transparent; display: flex; flex-direction: column; gap: 8px;">
                                <div class="card-year" id="preview-year" style="font-size: 0.78rem; font-weight: 700; color: rgba(255,255,255,0.42);"></div>
                                <div class="card-content">
                                    <h3 id="preview-title" style="font-size: 0.95rem; font-weight: 700; color: white; margin-bottom: 6px; line-height: 1.4;"></h3>
                                    <p id="preview-desc" style="font-size: 0.8rem; color: rgba(255,255,255,0.62); line-height: 1.5; margin-bottom: 10px;"></p>
                                    <div class="tags" id="preview-tags"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 20px;">
                    <button type="submit" class="btn-primary-cms" style="flex: 1;">
                        Create Experience
                    </button>
                    <a href="{{ route('admin.experiences.index') }}" class="btn-secondary-cms" style="text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

    <script>
        function updatePreview() {
            const titleVal = document.getElementById('title').value || 'Position Title';
            const orgVal = document.getElementById('organization').value || 'Organization Name';
            const yearVal = document.getElementById('year').value || 'Year / Period';
            const descVal = document.getElementById('description').value || 'Role description...';
            
            document.getElementById('preview-year').innerText = yearVal;
            document.getElementById('preview-title').innerText = titleVal + ' · ' + orgVal;
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

        document.addEventListener('DOMContentLoaded', () => {
            ['title', 'organization', 'year', 'description', 'new_tags'].forEach(id => {
                document.getElementById(id).addEventListener('input', updatePreview);
            });

            document.querySelectorAll('input[name="tags[]"]').forEach(chk => {
                chk.addEventListener('change', updatePreview);
            });

            updatePreview();
        });
    </script>
@endsection
