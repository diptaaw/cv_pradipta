@extends('admin.layout')

@section('title', 'Edit Update')
@section('page-title', 'Edit Update')

@section('breadcrumb')
    <a href="{{ route('admin.updates.index') }}">Updates</a>
    <span>&rarr;</span> <span class="active">Edit Update</span>
@endsection

@section('content')
    @if($errors->any())
        <div class="flash-message" style="margin-bottom: 24px; padding: 12px 16px; background: rgba(255,59,48,0.15); border: 1px solid rgba(255,59,48,0.25); color: #ff453a; border-radius: 10px; font-weight: 600;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.updates.update', $update) }}">
        @csrf
        @method('PUT')

        <div class="cms-form-grid" style="grid-template-columns: 1.2fr 1fr; gap: 24px;">
            <!-- Left Column: Details -->
            <div class="admin-card cms-card-section">
                <h2 style="font-size: 1.15rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    📝 Update Details
                </h2>

                <div class="cms-form-group">
                    <label for="title">Update Title</label>
                    <input class="cms-input" id="title" type="text" name="title" value="{{ old('title', $update->title) }}" required>
                </div>

                <div class="cms-form-group">
                    <label for="date">Display Date / Period</label>
                    <input class="cms-input" id="date" type="text" name="date" value="{{ old('date', $update->date) }}" required>
                </div>

                <div class="cms-form-group">
                    <label for="description">Short Description / Summary</label>
                    <textarea class="cms-textarea" id="description" name="description" rows="5" required style="min-height: 120px;">{{ old('description', $update->description) }}</textarea>
                </div>
            </div>

            <!-- Right Column: Settings & Actions -->
            <div class="admin-card cms-card-section" style="display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <h2 style="font-size: 1.15rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        ⚙️ Settings
                    </h2>

                    <div class="cms-form-group" style="margin-bottom: 20px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned', $update->is_pinned) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #7b61ff;">
                            <span style="font-size: 0.95rem; font-weight: 500;">Pin this update (Show at top)</span>
                        </label>
                        <p style="font-size: 0.8rem; color: rgba(255,255,255,0.4); margin-left: 28px; margin-top: 4px;">
                            Pinned updates are always displayed at the very top of the notification dropdown list.
                        </p>
                    </div>

                    <div class="cms-form-group" style="margin-bottom: 20px;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $update->is_published) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #7b61ff;">
                            <span style="font-size: 0.95rem; font-weight: 500;">Publish update</span>
                        </label>
                        <p style="font-size: 0.8rem; color: rgba(255,255,255,0.4); margin-left: 28px; margin-top: 4px;">
                            If unchecked, this update will save as a draft and remain hidden from visitors.
                        </p>
                    </div>
                </div>

                <div class="form-actions" style="margin-top: 24px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.06); display: flex; justify-content: flex-end; gap: 12px;">
                    <a href="{{ route('admin.updates.index') }}" class="btn-secondary-cms" style="text-decoration: none;">Cancel</a>
                    <button type="submit" class="btn-primary-cms">Save Changes</button>
                </div>
            </div>
        </div>
    </form>
@endsection
