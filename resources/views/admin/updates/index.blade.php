@extends('admin.layout')

@section('title', 'Manage Notifications')
@section('page-title', "Notifications / Activity Feed")

@section('breadcrumb')
    <span>&rarr;</span> <span class="active">Notifications</span>
@endsection

@section('content')
    @if(session('success'))
        <div class="flash-message" style="margin-bottom: 24px; padding: 12px 16px; background: rgba(46,204,113,0.12); border: 1px solid rgba(46,204,113,0.20); color: #2ecc71; border-radius: 12px; font-weight: 600;">
             {{ session('success') }}
        </div>
    @endif

    <div class="section-header">
        <h2>Notifications & Activity Logs</h2>
        <a href="{{ route('admin.updates.create') }}" class="btn-primary-cms" style="text-decoration: none;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Notification
        </a>
    </div>

    @if($updates->isEmpty())
        <div class="admin-card" style="text-align: center; padding: 48px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto 16px;"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path></svg>
            <p style="color: rgba(255,255,255,0.4); font-size: 0.92rem; margin-bottom: 16px;">
                No notifications found. Create one manually or perform CMS actions to trigger logs.
            </p>
            <a href="{{ route('admin.updates.create') }}" class="btn-primary-cms" style="text-decoration: none;">
                Create Custom Notification
            </a>
        </div>
    @else
        <div class="admin-card" style="padding: 0; overflow: hidden;">
            <div class="cms-table-wrapper">
                <table class="cms-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Reference</th>
                            <th>Pinned</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($updates as $update)
                            <tr>
                                <td style="font-weight: 700;">{{ $update->title }}</td>
                                <td style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; color: rgba(255,255,255,0.6);" title="{{ $update->description }}">
                                    {{ $update->description ?: '-' }}
                                </td>
                                <td>
                                    <span class="admin-badge" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.8); text-transform: uppercase; font-size: 0.75rem;">
                                        {{ str_replace('_', ' ', $update->type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($update->reference_type)
                                        <span style="font-size: 0.82rem; color: rgba(255,255,255,0.6);">
                                            {{ $update->reference_type }} #{{ $update->reference_id }}
                                        </span>
                                    @else
                                        <span style="color: rgba(255,255,255,0.25); font-size: 0.8rem;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($update->is_pinned)
                                        <span class="admin-badge" style="background: rgba(241,196,15,0.10); border: 1px solid rgba(241,196,15,0.15); color: #f1c40f;">Pinned</span>
                                    @else
                                        <span style="color: rgba(255,255,255,0.2); font-size: 0.8rem;">No</span>
                                    @endif
                                </td>
                                <td>
                                    @if($update->is_read)
                                        <span class="admin-badge" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); color: rgba(255,255,255,0.4);">Read</span>
                                    @else
                                        <span class="admin-badge" style="background: rgba(169, 150, 255, 0.12); border: 1px solid rgba(169, 150, 255, 0.25); color: #a996ff; font-weight: bold;">Unread</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-size: 0.82rem; color: rgba(255,255,255,0.5)">{{ $update->created_at ? $update->created_at->format('M d, Y H:i') : '-' }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons" style="justify-content: flex-end;">
                                        <a href="{{ route('admin.updates.edit', $update) }}" class="action-btn">Edit</a>
                                        <form method="POST" action="{{ route('admin.updates.destroy', $update) }}" onsubmit="return confirm('Are you sure you want to delete this notification?')" style="display:inline;">
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
