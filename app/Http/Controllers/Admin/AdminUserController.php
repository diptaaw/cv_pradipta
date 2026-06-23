<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    protected function authorizeSuperAdmin()
    {
        if (!auth()->user() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Hanya Super Admin yang dapat mengelola administrator.');
        }
    }

    public function index()
    {
        $this->authorizeSuperAdmin();

        $admins = User::with('role')->get();

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        $this->authorizeSuperAdmin();

        $roles = Role::all();

        return view('admin.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'nullable|boolean',
            'avatar' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $avatarPath = $file->storeAs('uploads/avatars', $filename, config('filesystems.default'));
        }

        $admin = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role_id' => $request->input('role_id'),
            'is_active' => $request->boolean('is_active', true),
            'avatar' => $avatarPath,
        ]);

        ActivityLog::log('Admin added', 'Added new admin: ' . $admin->email);
        Notification::send('admin_created', 'New admin added', 'Added new admin: ' . $admin->name, 'admin', $admin->id);

        return redirect()->route('admin.admins.index')->with('success', 'Admin "' . $admin->name . '" berhasil ditambahkan.');
    }

    public function edit(User $admin)
    {
        $this->authorizeSuperAdmin();

        $roles = Role::all();

        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    public function update(Request $request, User $admin)
    {
        $this->authorizeSuperAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
            'is_active' => 'nullable|boolean',
            'avatar' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // Prevent self-disable or self-role change
        $isSelf = auth()->id() === $admin->id;
        $is_active = $isSelf ? $admin->is_active : $request->boolean('is_active', true);
        $role_id = $isSelf ? $admin->role_id : $request->input('role_id');

        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role_id' => $role_id,
            'is_active' => $is_active,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        // Handle Avatar File Upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($admin->avatar) {
                if (Storage::disk(config('filesystems.default'))->exists($admin->avatar)) {
                    Storage::disk(config('filesystems.default'))->delete($admin->avatar);
                }
            }
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['avatar'] = $file->storeAs('uploads/avatars', $filename, config('filesystems.default'));
        }

        $admin->update($data);

        ActivityLog::log('Admin edited', 'Updated admin user: ' . $admin->email);
        Notification::send('admin_updated', 'Admin updated', 'Updated admin user: ' . $admin->name, 'admin', $admin->id);

        return redirect()->route('admin.admins.index')->with('success', 'Admin "' . $admin->name . '" berhasil diperbarui.');
    }

    public function destroy(User $admin)
    {
        $this->authorizeSuperAdmin();

        if (auth()->id() === $admin->id) {
            return redirect()->route('admin.admins.index')->withErrors(['self_delete' => 'Anda tidak bisa menghapus akun Anda sendiri.']);
        }

        // Delete avatar if exists
        if ($admin->avatar) {
            if (Storage::disk(config('filesystems.default'))->exists($admin->avatar)) {
                Storage::disk(config('filesystems.default'))->delete($admin->avatar);
            }
        }

        $email = $admin->email;
        $admin->delete();

        ActivityLog::log('Admin removed', 'Removed admin: ' . $email);

        return redirect()->route('admin.admins.index')->with('success', 'Admin "' . $email . '" berhasil dihapus.');
    }
}
