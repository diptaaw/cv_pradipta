@extends('admin.layout')

@section('title', 'Manage Settings')
@section('page-title', 'Settings')

@section('breadcrumb')
    <span>&rarr;</span> <span class="active">Settings</span>
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

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf

        <div class="cms-form-grid" style="grid-template-columns: 1.5fr 1fr;">
            <!-- Left Card: Metadata & SEO Configurations -->
            <div class="admin-card cms-card-section">
                <h2 style="font-size: 1.15rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    🔍 SEO & Metadata Settings
                </h2>

                <div class="cms-form-group">
                    <label for="site_title">Site Title</label>
                    <input class="cms-input" id="site_title" type="text" name="site_title" value="{{ old('site_title', \App\Models\SiteSetting::get('site_title', 'Pradipta Portfolio')) }}" required placeholder="e.g. Pradipta Adicandra Wicaksono — Portfolio">
                    <span style="font-size: 0.72rem; color: rgba(255,255,255,0.4)">Appears in the browser tab and search results.</span>
                </div>

                <div class="cms-form-group" style="margin-top: 12px;">
                    <label for="meta_description">Meta Description</label>
                    <textarea class="cms-textarea" id="meta_description" name="meta_description" required placeholder="Write a short summary of the site for search engines..." style="min-height: 120px;">{{ old('meta_description', \App\Models\SiteSetting::get('meta_description', 'Multimedia & Broadcasting student exploring visual storytelling, creative technology, and digital media production.')) }}</textarea>
                    <span style="font-size: 0.72rem; color: rgba(255,255,255,0.4)">Used by search engines to describe your site. Keep it under 160 characters for best display.</span>
                </div>

                <div style="margin-top: 24px; display: flex; gap: 12px;">
                    <button type="submit" class="btn-primary-cms" style="padding-left: 32px; padding-right: 32px;">
                        💾 Save Settings
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn-secondary-cms" style="text-decoration: none;">
                        Cancel
                    </a>
                </div>
            </div>

            <!-- Right Card: System Info -->
            <div class="admin-card cms-card-section" style="justify-content: space-between;">
                <div>
                    <h2 style="font-size: 1.15rem; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        ⚙️ System Information
                    </h2>

                    <div style="display: flex; flex-direction: column; gap: 14px; font-size: 0.88rem;">
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 8px;">
                            <span style="color: rgba(255,255,255,0.5);">CMS Version</span>
                            <span style="font-weight: 700; color: #a996ff;">v1.2.0</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 8px;">
                            <span style="color: rgba(255,255,255,0.5);">Framework</span>
                            <span style="font-family: monospace;">Laravel v{{ app()->version() }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 8px;">
                            <span style="color: rgba(255,255,255,0.5);">PHP Version</span>
                            <span style="font-family: monospace;">v{{ PHP_VERSION }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding-bottom: 8px;">
                            <span style="color: rgba(255,255,255,0.5);">Environment</span>
                            <span class="admin-badge" style="background: rgba(46,204,113,0.12); border: 1px solid rgba(46,204,113,0.18); color: #2ecc71;">{{ app()->environment() }}</span>
                        </div>
                    </div>
                </div>

                <div style="padding: 16px; background: rgba(123,97,255,0.05); border: 1px solid rgba(123,97,255,0.1); border-radius: 12px; font-size: 0.78rem; color: rgba(255,255,255,0.5); line-height: 1.4;">
                    🔒 SEO Title and Description configurations are automatically bound to the frontend layouts for maximum SEO efficiency.
                </div>
            </div>
        </div>
    </form>
@endsection
