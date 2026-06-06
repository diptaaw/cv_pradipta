@extends('admin.layout')

@section('title', 'Manage Tags')
@section('page-title', 'Tags')

@section('breadcrumb')
    <span>&rarr;</span> <span class="active">Tags</span>
@endsection

@section('content')
    @if(session('success'))
        <div class="flash-message" style="margin-bottom: 24px; padding: 12px 16px; background: rgba(46,204,113,0.15); border: 1px solid rgba(46,204,113,0.25); color: #2ecc71; border-radius: 10px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: flex; gap: 16px; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap;">
        <!-- Search bar -->
        <form method="GET" action="{{ route('admin.tags.index') }}" style="display: flex; gap: 8px; flex-grow: 1; max-width: 400px;">
            <input class="cms-input" type="text" name="q" value="{{ request('q') }}" placeholder="Search tags by name...">
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
            @if(request('direction'))
                <input type="hidden" name="direction" value="{{ request('direction') }}">
            @endif
            <button type="submit" class="action-btn" style="padding: 12px 16px; font-weight: 700;">Search</button>
            @if(request('q'))
                <a href="{{ route('admin.tags.index') }}" class="action-btn" style="padding: 12px 16px; text-decoration: none; display: flex; align-items: center; justify-content: center;">Clear</a>
            @endif
        </form>

        <!-- Sort controls -->
        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <span style="font-size: 0.82rem; color: rgba(255,255,255,0.5); font-weight: 700; text-transform: uppercase;">Sort:</span>
            
            <a href="{{ route('admin.tags.index', ['q' => request('q'), 'sort' => 'name', 'direction' => (request('sort') === 'name' && request('direction') === 'asc') ? 'desc' : 'asc']) }}" class="action-btn {{ request('sort', 'name') === 'name' ? 'active' : '' }}" style="text-decoration: none;">
                Name {{ request('sort', 'name') === 'name' ? (request('direction', 'asc') === 'asc' ? '▲' : '▼') : '' }}
            </a>
            
            <a href="{{ route('admin.tags.index', ['q' => request('q'), 'sort' => 'projects', 'direction' => (request('sort') === 'projects' && request('direction', 'desc') === 'desc') ? 'asc' : 'desc']) }}" class="action-btn {{ request('sort') === 'projects' ? 'active' : '' }}" style="text-decoration: none;">
                Projects {{ request('sort') === 'projects' ? (request('direction', 'desc') === 'desc' ? '▼' : '▲') : '' }}
            </a>
            
            <a href="{{ route('admin.tags.index', ['q' => request('q'), 'sort' => 'experiences', 'direction' => (request('sort') === 'experiences' && request('direction', 'desc') === 'desc') ? 'asc' : 'desc']) }}" class="action-btn {{ request('sort') === 'experiences' ? 'active' : '' }}" style="text-decoration: none;">
                Experiences {{ request('sort') === 'experiences' ? (request('direction', 'desc') === 'desc' ? '▼' : '▲') : '' }}
            </a>

            <a href="{{ route('admin.tags.index', ['q' => request('q'), 'sort' => 'total', 'direction' => (request('sort') === 'total' && request('direction', 'desc') === 'desc') ? 'asc' : 'desc']) }}" class="action-btn {{ request('sort') === 'total' ? 'active' : '' }}" style="text-decoration: none;">
                Total Usage {{ request('sort') === 'total' ? (request('direction', 'desc') === 'desc' ? '▼' : '▲') : '' }}
            </a>
        </div>

        <a href="{{ route('admin.tags.create') }}" class="btn-primary-cms" style="text-decoration: none;">
            ➕ Add Tag
        </a>
    </div>

    @if($tags->isEmpty())
        <div class="admin-card" style="text-align: center; padding: 40px;">
            <p style="color: rgba(255,255,255,0.5); font-size: 0.95rem; margin-bottom: 16px;">
                No tags found. Add a tag to link it to projects or experiences.
            </p>
            <a href="{{ route('admin.tags.create') }}" class="btn-primary-cms" style="text-decoration: none;">
                Create Your First Tag
            </a>
        </div>
    @else
        <div class="admin-card" style="padding: 0; overflow: hidden;">
            <div class="cms-table-wrapper">
                <table class="cms-table">
                    <thead>
                        <tr>
                            <th>Tag Name</th>
                            <th>Projects Usage</th>
                            <th>Experiences Usage</th>
                            <th>Total Usage</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tags as $tag)
                            @php
                                $totalUsage = $tag->projects_count + $tag->experiences_count;
                            @endphp
                            <tr>
                                <td>
                                    <span class="admin-badge" style="background: rgba(123,97,255,0.12); border: 1px solid rgba(123,97,255,0.2); color: #c4b5fd; font-weight: 700; padding: 4px 10px; border-radius: 8px;">
                                        {{ $tag->name }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-family: monospace; color: rgba(255,255,255,0.7)">{{ $tag->projects_count }}</span>
                                </td>
                                <td>
                                    <span style="font-family: monospace; color: rgba(255,255,255,0.7)">{{ $tag->experiences_count }}</span>
                                </td>
                                <td>
                                    <span style="font-weight: 700; color: {{ $totalUsage > 0 ? '#a996ff' : 'rgba(255,255,255,0.3)' }}">
                                        {{ $totalUsage }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons" style="justify-content: flex-end;">
                                        <a href="{{ route('admin.tags.edit', $tag) }}" class="action-btn">Edit</a>
                                        <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" onsubmit="return confirm('Are you sure you want to delete this tag? All links to Projects and Experiences will be removed.')" style="display:inline;">
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
