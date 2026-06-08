@extends('admin.layout')

@section('title', 'Manage Admin Users')
@section('page-title', 'Admin Users')

@section('breadcrumb')
    <span>&rarr;</span> <span class="active">Admin Users</span>
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

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-size: 1.15rem; margin: 0; color: rgba(255,255,255,0.7)">
            System Administrators
        </h2>
        <a href="{{ route('admin.admins.create') }}" class="btn-primary-cms" style="text-decoration: none;">
            ➕ Add Admin
        </a>
    </div>

    <div class="admin-card" style="padding: 0; overflow: hidden;">
        <div class="cms-table-wrapper">
            <table class="cms-table">
                <thead>
                    <tr>
                        <th>Admin Info</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $admin)
                        <tr>
                            <td>
                                <div class="cms-avatar-cell">
                                    <img src="{{ $admin->avatar ? storage_url($admin->avatar) : asset('images/ui/avatar.png') }}" alt="{{ $admin->name }} avatar">
                                    <span style="font-weight: 700;">{{ $admin->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span style="font-family: monospace; color: rgba(255,255,255,0.7)">{{ $admin->email }}</span>
                            </td>
                            <td>
                                @if($admin->isSuperAdmin())
                                    <span class="admin-badge" style="background: rgba(123,97,255,0.12); border: 1px solid rgba(123,97,255,0.2); color: #c4b5fd; font-size: 0.72rem;">Super Admin</span>
                                @else
                                    <span class="admin-badge" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: rgba(255,255,255,0.7); font-size: 0.72rem;">Admin</span>
                                @endif
                            </td>
                            <td>
                                @if($admin->is_active)
                                    <span class="admin-badge" style="background: rgba(46,204,113,0.12); border: 1px solid rgba(46,204,113,0.18); color: #2ecc71; font-size: 0.72rem;">Active</span>
                                @else
                                    <span class="admin-badge" style="background: rgba(255,59,48,0.12); border: 1px solid rgba(255,59,48,0.18); color: #ff453a; font-size: 0.72rem;">Disabled</span>
                                @endif
                            </td>
                            <td>
                                <span style="font-size: 0.82rem; color: rgba(255,255,255,0.5)">
                                    {{ $admin->last_login_at ? $admin->last_login_at->format('M d, Y H:i') : 'Never' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons" style="justify-content: flex-end;">
                                    <a href="{{ route('admin.admins.edit', $admin) }}" class="action-btn">Edit</a>
                                    @if(auth()->id() !== $admin->id)
                                        <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" onsubmit="return confirm('Are you sure you want to permanently delete this admin?')" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn danger">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
