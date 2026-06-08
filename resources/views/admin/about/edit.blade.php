@extends('admin.layout')

@section('title', 'Manage Home Content')
@section('page-title', 'Home Content')

@section('breadcrumb')
    <span>&rarr;</span> <span class="active">Home Content</span>
@endsection

@section('content')
    @if(session('success'))
        <div class="flash-message" style="margin-bottom: 24px; padding: 12px 16px; background: rgba(46,204,113,0.15); border: 1px solid rgba(46,204,113,0.25); color: #2ecc71; border-radius: 10px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="flash-message" style="margin-bottom: 24px; padding: 12px 16px; background: rgba(255,59,48,0.15); border: 1px solid rgba(255,59,48,0.25); color: #ff453a; border-radius: 10px; font-weight: 600;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.about.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="cms-form-grid">
            <!-- Left Column: Personal info, Socials, Profile Photo -->
            <div class="admin-card cms-card-section">
                <h2 style="font-size: 1.15rem; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    👤 Personal Profile Details
                </h2>

                <div class="cms-form-group">
                    <label for="headline">Name / Headline</label>
                    <input class="cms-input" id="headline" type="text" name="headline" value="{{ old('headline', $about->headline ?? '') }}" required placeholder="e.g. Pradipta Adicandra Wicaksono">
                </div>

                <div class="cms-form-group">
                    <label for="subheadline">Position / Role Title</label>
                    <input class="cms-input" id="subheadline" type="text" name="subheadline" value="{{ old('subheadline', $about->subheadline ?? '') }}" required placeholder="e.g. Multimedia & Broadcasting Engineer">
                </div>

                <div class="cms-form-group">
                    <label for="short_intro">Short Introduction</label>
                    <textarea class="cms-textarea" id="short_intro" name="short_intro" placeholder="Introduce yourself in one or two short sentences..." style="min-height: 80px;">{{ old('short_intro', $about->short_intro ?? '') }}</textarea>
                </div>

                <div class="cms-form-group">
                    <label>Profile Photo</label>
                    <div style="display: flex; align-items: center; gap: 16px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 12px; border-radius: 12px;">
                        <img id="photo-preview" src="{{ $about->profile_image && $about->profile_image !== 'images/ui/avatar.png' ? storage_url($about->profile_image) : asset('images/ui/avatar.png') }}" alt="Profile Image" style="width: 64px; height: 64px; border-radius: 12px; object-fit: cover; border: 1px solid rgba(255,255,255,0.1);">
                        <div>
                            <input type="file" name="profile_image" id="profile_image" style="display: none;" accept="image/*" onchange="previewImage(this)">
                            <button type="button" class="action-btn" onclick="document.getElementById('profile_image').click()" style="margin-bottom: 4px;">Choose Photo</button>
                            <div style="font-size: 0.72rem; color: rgba(255,255,255,0.4)">Max 5MB. PNG, JPG or WEBP.</div>
                        </div>
                    </div>
                </div>

                <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 12px 0;">

                <h2 style="font-size: 1.15rem; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    🔗 Social Media Links
                </h2>

                <div class="cms-form-group">
                    <label for="social_github">GitHub URL</label>
                    <input class="cms-input" id="social_github" type="url" name="social_github" value="{{ old('social_github', $socials['github'] ?? '') }}" placeholder="https://github.com/username">
                </div>

                <div class="cms-form-group">
                    <label for="social_instagram">Instagram URL</label>
                    <input class="cms-input" id="social_instagram" type="url" name="social_instagram" value="{{ old('social_instagram', $socials['instagram'] ?? '') }}" placeholder="https://instagram.com/username">
                </div>

                <div class="cms-form-group">
                    <label for="social_linkedin">LinkedIn URL</label>
                    <input class="cms-input" id="social_linkedin" type="url" name="social_linkedin" value="{{ old('social_linkedin', $socials['linkedin'] ?? '') }}" placeholder="https://linkedin.com/in/username">
                </div>

                <div class="cms-form-group">
                    <label for="social_email">Contact Email</label>
                    <input class="cms-input" id="social_email" type="text" name="social_email" value="{{ old('social_email', $socials['email'] ?? '') }}" placeholder="mailto:yourname@email.com or plain email">
                </div>

                <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 12px 0;">

                <h2 style="font-size: 1.15rem; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    📝 Footer Text
                </h2>

                <div class="cms-form-group">
                    <label for="footer_designed_text">Designed & Developed Text</label>
                    <textarea class="cms-textarea" id="footer_designed_text" name="footer_designed_text" placeholder="Designed and developed by..." style="min-height: 80px;">{{ old('footer_designed_text', $footer['designed_text'] ?? '') }}</textarea>
                </div>

                <div class="cms-form-group">
                    <label for="footer_copyright_text">Copyright Text</label>
                    <input class="cms-input" id="footer_copyright_text" type="text" name="footer_copyright_text" value="{{ old('footer_copyright_text', $footer['copyright_text'] ?? '') }}" placeholder="© 2026 Pradipta Adicandra Wicaksono">
                </div>
            </div>

            <!-- Right Column: About Paragraphs & Publication -->
            <div class="admin-card cms-card-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <h2 style="font-size: 1.15rem; display: flex; align-items: center; gap: 8px; margin: 0;">
                        📝 Biography Content (Right Column)
                    </h2>
                    <button type="button" class="action-btn" onclick="addParagraph('')" style="background: rgba(123,97,255,0.15); border-color: rgba(123,97,255,0.25); color: #c4b5fd;">
                        ➕ Add Paragraph
                    </button>
                </div>

                <div id="paragraphs-container">
                    @if(isset($about) && is_array($about->paragraphs))
                        @foreach($about->paragraphs as $index => $paragraph)
                            <div class="paragraph-box">
                                <textarea name="paragraphs[]" placeholder="Write biography paragraph here..." required>{{ $paragraph }}</textarea>
                                <div class="paragraph-controls">
                                    <button type="button" class="paragraph-btn" onclick="moveUp(this)" title="Move Up">▲</button>
                                    <button type="button" class="paragraph-btn" onclick="moveDown(this)" title="Move Down">▼</button>
                                    <button type="button" class="paragraph-btn remove" onclick="removeParagraph(this)" title="Remove Paragraph">✕</button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 12px 0;">

                <div class="cms-form-group">
                    <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $about->is_published ?? true) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: #7b61ff; cursor: pointer;">
                        <span style="font-size: 0.88rem; font-weight: 600; color: white;">Publish this content to Homepage</span>
                    </label>
                </div>

                <div style="margin-top: 20px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-primary-cms" style="flex: 1;">
                        💾 Save Changes
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn-secondary-cms" style="text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photo-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function addParagraph(text = '') {
            const container = document.getElementById('paragraphs-container');
            const item = document.createElement('div');
            item.className = 'paragraph-box';
            item.innerHTML = `
                <textarea name="paragraphs[]" placeholder="Write biography paragraph here..." required>${text}</textarea>
                <div class="paragraph-controls">
                    <button type="button" class="paragraph-btn" onclick="moveUp(this)" title="Move Up">▲</button>
                    <button type="button" class="paragraph-btn" onclick="moveDown(this)" title="Move Down">▼</button>
                    <button type="button" class="paragraph-btn remove" onclick="removeParagraph(this)" title="Remove Paragraph">✕</button>
                </div>
            `;
            container.appendChild(item);
            item.querySelector('textarea').focus();
        }

        function removeParagraph(btn) {
            const box = btn.closest('.paragraph-box');
            if (box) {
                box.remove();
            }
        }

        function moveUp(btn) {
            const box = btn.closest('.paragraph-box');
            const prev = box.previousElementSibling;
            if (prev && prev.classList.contains('paragraph-box')) {
                box.parentNode.insertBefore(box, prev);
            }
        }

        function moveDown(btn) {
            const box = btn.closest('.paragraph-box');
            const next = box.nextElementSibling;
            if (next && next.classList.contains('paragraph-box')) {
                box.parentNode.insertBefore(next, box);
            }
        }

        // Add an initial empty paragraph block if none exist
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('paragraphs-container');
            if (container.children.length === 0) {
                addParagraph('');
            }
        });
    </script>
@endsection
