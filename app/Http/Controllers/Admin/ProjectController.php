<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends BaseAdminController
{
    public function index()
    {
        $projects = Project::orderBy('position')->get();

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'technologies' => 'nullable|string',
            'project_link' => 'nullable|url',
            'github_link' => 'nullable|url',
            'position' => 'nullable|integer',
            'featured' => 'nullable|boolean',
            'archived' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ]);

        $data['technologies'] = $data['technologies'] ? array_filter(array_map('trim', explode(',', $data['technologies']))) : [];
        $data['featured'] = $request->boolean('featured');
        $data['archived'] = $request->boolean('archived');
        $data['is_published'] = $request->boolean('is_published');

        Project::create($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil ditambahkan.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'technologies' => 'nullable|string',
            'project_link' => 'nullable|url',
            'github_link' => 'nullable|url',
            'position' => 'nullable|integer',
            'featured' => 'nullable|boolean',
            'archived' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ]);

        $data['technologies'] = $data['technologies'] ? array_filter(array_map('trim', explode(',', $data['technologies']))) : [];
        $data['featured'] = $request->boolean('featured');
        $data['archived'] = $request->boolean('archived');
        $data['is_published'] = $request->boolean('is_published');

        $project->update($data);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil dihapus.');
    }
}
