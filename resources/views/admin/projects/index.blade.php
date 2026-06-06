@extends('admin.layout')

@section('title', 'Manage Projects')
@section('page-title', 'Projects')

@section('breadcrumb')
    <span>&rarr;</span> <span class="active">Projects</span>
@endsection

@section('content')
    @if(session('success'))
        <div class="flash-message" style="margin-bottom: 24px; padding: 12px 16px; background: rgba(46,204,113,0.15); border: 1px solid rgba(46,204,113,0.25); color: #2ecc71; border-radius: 10px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-size: 1.15rem; margin: 0; color: rgba(255,255,255,0.7)">
            List of Portfolio Projects
        </h2>
        <a href="{{ route('admin.projects.create') }}" class="btn-primary-cms" style="text-decoration: none;">
            ➕ Add New Project
        </a>
    </div>

    @if($projects->isEmpty())
        <div class="admin-card" style="text-align: center; padding: 40px;">
            <p style="color: rgba(255,255,255,0.5); font-size: 0.95rem; margin-bottom: 16px;">
                No projects found. Create one to display it on the portfolio.
            </p>
            <a href="{{ route('admin.projects.create') }}" class="btn-primary-cms" style="text-decoration: none;">
                Create Your First Project
            </a>
        </div>
    @else
        <div class="admin-card" style="padding: 0; overflow: hidden;">
            <div class="cms-table-wrapper">
                <table class="cms-table">
                    <thead>
                        <tr>
                            <th>Thumbnail</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Technologies</th>
                            <th>Status</th>
                            <th>Order</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                            <tr>
                                <td style="width: 80px;">
                                    <img src="{{ $project->thumbnail ? asset($project->thumbnail) : asset('images/projects/wildlife.png') }}" alt="{{ $project->title }}" style="width: 60px; height: 40px; border-radius: 6px; object-fit: cover; border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.02);">
                                </td>
                                <td style="font-weight: 700;">
                                    {{ $project->title }}
                                </td>
                                <td>
                                    <span style="font-size: 0.82rem; color: rgba(255,255,255,0.6)">{{ $project->category ?? 'General' }}</span>
                                </td>
                                <td>
                                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                        @foreach($project->technologies ?? [] as $tech)
                                            <span class="admin-badge" style="background: rgba(123,97,255,0.1); border: 1px solid rgba(123,97,255,0.15); color: #c4b5fd; font-size: 0.72rem; padding: 2px 6px; border-radius: 6px;">{{ $tech }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    @if($project->is_published)
                                        <span class="admin-badge" style="background: rgba(46,204,113,0.12); border: 1px solid rgba(46,204,113,0.18); color: #2ecc71; font-size: 0.75rem;">Published</span>
                                    @else
                                        <span class="admin-badge" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.4); font-size: 0.75rem;">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-family: monospace; color: rgba(255,255,255,0.5)">{{ $project->position }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons" style="justify-content: flex-end;">
                                        <a href="{{ route('admin.projects.edit', $project) }}" class="action-btn">Edit</a>
                                        <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('Are you sure you want to delete this project?')" style="display:inline;">
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
