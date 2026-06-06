@extends('admin.layout')

@section('title', 'Manage Experiences')
@section('page-title', 'Experiences')

@section('breadcrumb')
    <span>&rarr;</span> <span class="active">Experiences</span>
@endsection

@section('content')
    @if(session('success'))
        <div class="flash-message" style="margin-bottom: 24px; padding: 12px 16px; background: rgba(46,204,113,0.12); border: 1px solid rgba(46,204,113,0.20); color: #2ecc71; border-radius: 12px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="section-header">
        <h2>Professional & Organizational Experiences</h2>
        <a href="{{ route('admin.experiences.create') }}" class="btn-primary-cms" style="text-decoration: none;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Experience
        </a>
    </div>

    @if($experiences->isEmpty())
        <div class="admin-card" style="text-align: center; padding: 48px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 16px;"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
            <p style="color: rgba(255,255,255,0.4); font-size: 0.92rem; margin-bottom: 16px;">
                No experiences found. Create one to display on the portfolio.
            </p>
            <a href="{{ route('admin.experiences.create') }}" class="btn-primary-cms" style="text-decoration: none;">
                Create Your First Experience
            </a>
        </div>
    @else
        <div class="admin-card" style="padding: 0; overflow: hidden;">
            <div class="cms-table-wrapper">
                <table class="cms-table">
                    <thead>
                        <tr>
                            <th>Role Title</th>
                            <th>Organization</th>
                            <th>Timeline</th>
                            <th>Tags</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($experiences as $experience)
                            <tr>
                                <td style="font-weight: 700;">{{ $experience->title }}</td>
                                <td>
                                    <span style="font-weight: 600;">{{ $experience->organization }}</span>
                                </td>
                                <td>
                                    <span style="font-size: 0.82rem; color: rgba(255,255,255,0.5)">{{ $experience->year }}</span>
                                </td>
                                <td>
                                    <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                        @foreach($experience->tags()->get() as $tag)
                                            <span class="admin-badge" style="background: rgba(123,97,255,0.08); border: 1px solid rgba(123,97,255,0.12); color: #c4b5fd; font-size: 0.70rem; padding: 2px 7px; border-radius: 6px;">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    @if($experience->is_published)
                                        <span class="admin-badge" style="background: rgba(46,204,113,0.10); border: 1px solid rgba(46,204,113,0.15); color: #2ecc71;">Published</span>
                                    @else
                                        <span class="admin-badge" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); color: rgba(255,255,255,0.35);">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-family: monospace; color: rgba(255,255,255,0.4); font-size: 0.82rem;">{{ $experience->position }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons" style="justify-content: flex-end;">
                                        <a href="{{ route('admin.experiences.edit', $experience) }}" class="action-btn">Edit</a>
                                        <form method="POST" action="{{ route('admin.experiences.destroy', $experience) }}" onsubmit="return confirm('Are you sure you want to delete this experience?')" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
