@extends('admin.layout')

@section('title', 'Create Notification')
@section('page-title', 'New Notification')

@section('breadcrumb')
    <a href="{{ route('admin.updates.index') }}">Notifications</a>
    <span>&rarr;</span> <span class="active">New Notification</span>
@endsection

@section('content')
    @if($errors->any())
        <div class="flash-message" style="margin-bottom: 24px; padding: 12px 16px; background: rgba(255,59,48,0.15); border: 1px solid rgba(255,59,48,0.25); color: #ff453a; border-radius: 10px; font-weight: 600;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.updates.store') }}">
        @csrf

        <div class="cms-form-grid" style="grid-template-columns: 1.2fr 1fr; gap: 24px;">
            <!-- Left Column: Details -->
            <div class="admin-card cms-card-section">
                <h2 style="font-size: 1.15rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    📝 Notification Details
                </h2>

                <div class="cms-form-group">
                    <label for="title">Title</label>
                    <input class="cms-input" id="title" type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Added Interactive Wildlife Park">
                </div>

                <div class="cms-form-group">
                    <label for="type">Notification Type</label>
                    <select class="cms-input" id="type" name="type" required style="background: #0c0a18; color: white;">
                        <option value="custom" {{ old('type') == 'custom' ? 'selected' : '' }}>Custom Notification</option>
                        <option value="project_created" {{ old('type') == 'project_created' ? 'selected' : '' }}>Project Created</option>
                        <option value="project_updated" {{ old('type') == 'project_updated' ? 'selected' : '' }}>Project Updated</option>
                        <option value="project_deleted" {{ old('type') == 'project_deleted' ? 'selected' : '' }}>Project Deleted</option>
                        <option value="experience_created" {{ old('type') == 'experience_created' ? 'selected' : '' }}>Experience Created</option>
                        <option value="experience_updated" {{ old('type') == 'experience_updated' ? 'selected' : '' }}>Experience Updated</option>
                        <option value="experience_deleted" {{ old('type') == 'experience_deleted' ? 'selected' : '' }}>Experience Deleted</option>
                        <option value="resume_uploaded" {{ old('type') == 'resume_uploaded' ? 'selected' : '' }}>Resume Uploaded</option>
                        <option value="homepage_updated" {{ old('type') == 'homepage_updated' ? 'selected' : '' }}>Homepage Content Updated</option>
                        <option value="settings_updated" {{ old('type') == 'settings_updated' ? 'selected' : '' }}>Settings Updated</option>
                    </select>
                </div>

                <div class="cms-form-group">
                    <label for="description">Short Description / Summary (Optional)</label>
                    <textarea class="cms-textarea" id="description" name="description" rows="5" placeholder="e.g. New Unity project featuring dynamic weather, NPC systems and wildlife interactions." style="min-height: 120px;">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Right Column: Settings & Actions -->
            <div class="admin-card cms-card-section" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <h2 style="font-size: 1.15rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        ⚙️ Settings & References
                    </h2>

                    <div class="cms-form-group">
                        <label for="reference_type">Reference Model Type (Optional)</label>
                        <select class="cms-input" id="reference_type" name="reference_type" style="background: #0c0a18; color: white;">
                            <option value="" {{ old('reference_type') == '' ? 'selected' : '' }}>None</option>
                            <option value="project" {{ old('reference_type') == 'project' ? 'selected' : '' }}>project</option>
                            <option value="experience" {{ old('reference_type') == 'experience' ? 'selected' : '' }}>experience</option>
                            <option value="resume" {{ old('reference_type') == 'resume' ? 'selected' : '' }}>resume</option>
                        </select>
                        <p style="font-size: 0.8rem; color: rgba(255,255,255,0.4); margin-top: 4px;">
                            Determines where the user is redirected when clicking the notification.
                        </p>
                    </div>

                    <div class="cms-form-group">
                        <label for="reference_id">Reference Model ID (Optional)</label>
                        <input class="cms-input" id="reference_id" type="number" name="reference_id" value="{{ old('reference_id') }}" placeholder="e.g. 1">
                    </div>

                    <div class="cms-form-group" style="margin-top: 24px; margin-bottom: 20px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #7b61ff;">
                            <span style="font-size: 0.95rem; font-weight: 500;">Pin this notification (Show at top)</span>
                        </label>
                        <p style="font-size: 0.8rem; color: rgba(255,255,255,0.4); margin-left: 28px; margin-top: 4px;">
                            Pinned notifications are always displayed at the very top of the notification dropdown list.
                        </p>
                    </div>

                    <div class="cms-form-group" style="margin-bottom: 20px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="is_read" value="1" {{ old('is_read') ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #7b61ff;">
                            <span style="font-size: 0.95rem; font-weight: 500;">Mark as Read immediately</span>
                        </label>
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; justify-content: flex-end; gap: 12px;">
                    <a href="{{ route('admin.updates.index') }}" class="btn-secondary-cms" style="text-decoration: none;">Cancel</a>
                    <button type="submit" class="btn-primary-cms">Save Notification</button>
                </div>
            </div>
        </div>
    </form>
@endsection
