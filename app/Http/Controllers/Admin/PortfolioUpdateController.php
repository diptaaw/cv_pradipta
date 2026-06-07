<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use App\Models\PortfolioUpdate;
use App\Models\ActivityLog;
use App\Models\Notification;

class PortfolioUpdateController extends BaseAdminController
{
    public function index()
    {
        $updates = Notification::orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.updates.index', compact('updates'));
    }

    public function create()
    {
        return view('admin.updates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:100',
            'reference_type' => 'nullable|string|max:100',
            'reference_id' => 'nullable|integer',
            'is_pinned' => 'nullable|boolean',
            'is_read' => 'nullable|boolean',
        ]);

        $data['is_pinned'] = $request->boolean('is_pinned');
        $data['is_read'] = $request->boolean('is_read');

        if (empty($data['type'])) {
            $data['type'] = 'custom';
        }

        $notification = Notification::create($data);

        ActivityLog::log('Notification created', 'Created notification via Admin: ' . $notification->title);

        return redirect()->route('admin.updates.index')->with('success', 'Notification successfully added.');
    }

    public function edit(Notification $update)
    {
        return view('admin.updates.edit', compact('update'));
    }

    public function update(Request $request, Notification $update)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:100',
            'reference_type' => 'nullable|string|max:100',
            'reference_id' => 'nullable|integer',
            'is_pinned' => 'nullable|boolean',
            'is_read' => 'nullable|boolean',
        ]);

        $data['is_pinned'] = $request->boolean('is_pinned');
        $data['is_read'] = $request->boolean('is_read');

        $update->update($data);

        ActivityLog::log('Notification updated', 'Updated notification via Admin: ' . $update->title);

        return redirect()->route('admin.updates.index')->with('success', 'Notification successfully updated.');
    }

    public function destroy(Notification $update)
    {
        ActivityLog::log('Notification deleted', 'Deleted notification via Admin: ' . $update->title);

        $update->delete();

        return redirect()->route('admin.updates.index')->with('success', 'Notification successfully deleted.');
    }

    // Public API route for updates (fallback to keep old api endpoint compatible if needed)
    public function apiIndex()
    {
        $updates = Notification::orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($updates);
    }
}
