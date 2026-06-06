<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Media;
use App\Models\ActivityLog;

class ProjectController extends BaseAdminController
{
    public function index()
    {
        $projects = Project::orderBy('position')->get();

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $tags = Tag::all();
        $mediaList = Media::latest()->get();

        return view('admin.projects.create', compact('tags', 'mediaList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'project_link' => 'nullable|url',
            'github_link' => 'nullable|url',
            'position' => 'nullable|integer',
            'featured' => 'nullable|boolean',
            'archived' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
            'year' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category' => 'nullable|string|max:255',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'string',
        ]);

        $data['featured'] = $request->boolean('featured');
        $data['archived'] = $request->boolean('archived');
        $data['is_published'] = $request->boolean('is_published');
        $data['gallery_images'] = $request->input('gallery_images', []);

        $project = Project::create($data);

        // Handle tags
        $tagIds = $request->input('tags', []);
        
        // Handle new tags typed in text field
        if ($request->filled('new_tags')) {
            $newTagsArray = array_filter(array_map('trim', explode(',', $request->input('new_tags'))));
            foreach ($newTagsArray as $newTagName) {
                $slug = Tag::normalize($newTagName);
                $tag = Tag::firstOrCreate(['slug' => $slug], ['name' => $newTagName]);
                $tagIds[] = $tag->id;
            }
        }

        $project->tags()->sync($tagIds);

        ActivityLog::log('Project created', 'Created project: ' . $project->title);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil ditambahkan.');
    }

    public function edit(Project $project)
    {
        $tags = Tag::all();
        $mediaList = Media::latest()->get();
        $projectTagIds = $project->tags->pluck('id')->toArray();

        return view('admin.projects.edit', compact('project', 'tags', 'mediaList', 'projectTagIds'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'project_link' => 'nullable|url',
            'github_link' => 'nullable|url',
            'position' => 'nullable|integer',
            'featured' => 'nullable|boolean',
            'archived' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
            'year' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category' => 'nullable|string|max:255',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'string',
        ]);

        $data['featured'] = $request->boolean('featured');
        $data['archived'] = $request->boolean('archived');
        $data['is_published'] = $request->boolean('is_published');
        $data['gallery_images'] = $request->input('gallery_images', []);

        $project->update($data);

        // Handle tags
        $tagIds = $request->input('tags', []);
        
        // Handle new tags typed in text field
        if ($request->filled('new_tags')) {
            $newTagsArray = array_filter(array_map('trim', explode(',', $request->input('new_tags'))));
            foreach ($newTagsArray as $newTagName) {
                $slug = Tag::normalize($newTagName);
                $tag = Tag::firstOrCreate(['slug' => $slug], ['name' => $newTagName]);
                $tagIds[] = $tag->id;
            }
        }

        $project->tags()->sync($tagIds);

        ActivityLog::log('Project edited', 'Edited project: ' . $project->title);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        $title = $project->title;
        $project->delete();

        ActivityLog::log('Project deleted', 'Deleted project: ' . $title);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil dihapus.');
    }
}
