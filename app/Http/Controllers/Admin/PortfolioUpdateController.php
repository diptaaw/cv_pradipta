<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use App\Models\PortfolioUpdate;
use App\Models\ActivityLog;

class PortfolioUpdateController extends BaseAdminController
{
    public function index()
    {
        $updates = PortfolioUpdate::orderBy('is_pinned', 'desc')
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
            'description' => 'required|string',
            'date' => 'required|string|max:100',
            'is_pinned' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ]);

        $data['is_pinned'] = $request->boolean('is_pinned');
        $data['is_published'] = $request->boolean('is_published');

        $update = PortfolioUpdate::create($data);

        ActivityLog::log('Portfolio Update created', 'Created update: ' . $update->title);

        return redirect()->route('admin.updates.index')->with('success', 'Update successfully added.');
    }

    public function edit(PortfolioUpdate $update)
    {
        return view('admin.updates.edit', compact('update'));
    }

    public function update(Request $request, PortfolioUpdate $update)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|string|max:100',
            'is_pinned' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ]);

        $data['is_pinned'] = $request->boolean('is_pinned');
        $data['is_published'] = $request->boolean('is_published');

        $update->update($data);

        ActivityLog::log('Portfolio Update updated', 'Updated update: ' . $update->title);

        return redirect()->route('admin.updates.index')->with('success', 'Update successfully updated.');
    }

    public function destroy(PortfolioUpdate $update)
    {
        ActivityLog::log('Portfolio Update deleted', 'Deleted update: ' . $update->title);

        $update->delete();

        return redirect()->route('admin.updates.index')->with('success', 'Update successfully deleted.');
    }

    // Public API route for updates
    public function apiIndex()
    {
        $updates = PortfolioUpdate::where('is_published', true)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($updates);
    }
}
