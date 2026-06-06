@extends('admin.layout')

@section('title', 'Create Admin User')
@section('page-title', 'New Admin')

@section('breadcrumb')
    <a href="{{ route('admin.admins.index') }}">Admin Users</a>
    <span>&rarr;</span> <span class="active">New Admin</span>
@endsection

@section('content')
    @if($errors->any())
        <div class="flash-message" style="margin-bottom: 24px; padding: 12px 16px; background: rgba(255,59,48,0.15); border: 1px solid rgba(255,59,48,0.25); color: #ff453a; border-radius: 10px; font-weight: 600;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.admins.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="cms-form-grid">
            <!-- Left Column: Credentials -->
            <div class="admin-card cms-card-section">
                <h2 style="font-size: 1.15rem; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    🔑 Account Credentials
                </h2>

                <div class="cms-form-group">
                    <label for="name">Full Name</label>
                    <input class="cms-input" id="name" type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. John Doe">
                </div>

                <div class="cms-form-group">
                    <label for="email">Email Address</label>
                    <input class="cms-input" id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. johndoe@example.com">
                </div>

                <div class="cms-form-group">
                    <label for="password">Password</label>
                    <input class="cms-input" id="password" type="password" name="password" required placeholder="Minimum 6 characters">
                </div>

                <div class="cms-form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input class="cms-input" id="password_confirmation" type="password" name="password_confirmation" required placeholder="Retype password">
                </div>
            </div>

            <!-- Right Column: Settings & Avatar -->
            <div class="admin-card cms-card-section">
                <h2 style="font-size: 1.15rem; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    ⚙️ Role & Profile Photo
                </h2>

                <div class="cms-form-group">
                    <label for="role_id">System Role</label>
                    <select class="cms-select" id="role_id" name="role_id" required>
                        <option value="">-- Choose Role --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="cms-form-group">
                    <label>Profile Avatar</label>
                    <div style="display: flex; align-items: center; gap: 16px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 12px; border-radius: 12px;">
                        <img id="avatar-preview" src="{{ asset('images/ui/avatar.png') }}" alt="Avatar Preview" style="width: 64px; height: 64px; border-radius: 12px; object-fit: cover; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2);">
                        <div>
                            <input type="file" name="avatar" id="avatar" style="display: none;" accept="image/*" onchange="previewAvatar(this)">
                            <button type="button" class="action-btn" onclick="document.getElementById('avatar').click()" style="margin-bottom: 4px;">Choose Avatar</button>
                            <div style="font-size: 0.7rem; color: rgba(255,255,255,0.4)">Max 2MB. PNG, JPG or WEBP.</div>
                        </div>
                    </div>
                </div>

                <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 12px 0;">

                <div class="cms-form-group" style="margin-bottom: 16px;">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                        <input type="checkbox" name="is_active" value="1" checked style="width: 18px; height: 18px; accent-color: #7b61ff; cursor: pointer;">
                        <span style="font-size: 0.88rem; font-weight: 600; color: white;">Account Active</span>
                    </label>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" class="btn-primary-cms" style="flex: 1;">
                        ➕ Create Admin
                    </button>
                    <a href="{{ route('admin.admins.index') }}" class="btn-secondary-cms" style="text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

    <script>
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
