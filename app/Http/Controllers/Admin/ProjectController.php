<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Tag;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Support\Facades\Storage;

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

        return view('admin.projects.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail_file' => 'nullable|image|max:5120',
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
            'gallery_files' => 'nullable|array',
            'gallery_files.*' => 'image|max:5120',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail_file')) {
            $file = $request->file('thumbnail_file');
            $filename = time() . '_thumb_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/projects', $filename, 'public');
            $thumbnailPath = $path;
        }

        $galleryPaths = [];
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_gal_' . $file->getClientOriginalName();
                    $path = $file->storeAs('uploads/projects', $filename, 'public');
                    $galleryPaths[] = $path;
                }
            }
        }

        $project = Project::create([
            'title' => $request->input('title'),
            'thumbnail' => $thumbnailPath,
            'gallery_images' => $galleryPaths,
            'description' => $request->input('description'),
            'project_link' => $request->input('project_link'),
            'github_link' => $request->input('github_link'),
            'position' => $request->input('position', 0) ?? 0,
            'featured' => $request->boolean('featured'),
            'archived' => $request->boolean('archived'),
            'is_published' => $request->boolean('is_published'),
            'year' => $request->input('year'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'category' => $request->input('category'),
        ]);

        // Handle tags
        $tagIds = $request->input('tags', []);
        
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
        Notification::send('project_created', 'New project added', 'Created project: ' . $project->title, 'project', $project->id);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil ditambahkan.');
    }

    public function edit(Project $project)
    {
        $tags = Tag::all();
        $projectTagIds = $project->tags->pluck('id')->toArray();

        return view('admin.projects.edit', compact('project', 'tags', 'projectTagIds'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'thumbnail_file' => 'nullable|image|max:5120',
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
            'gallery_files' => 'nullable|array',
            'gallery_files.*' => 'image|max:5120',
        ]);

        // Handle thumbnail:
        if ($request->hasFile('thumbnail_file')) {
            // Delete old thumbnail if exists
            if ($project->thumbnail) {
                $oldPath = preg_replace('#^storage/#', '', $project->thumbnail);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $file = $request->file('thumbnail_file');
            $filename = time() . '_thumb_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/projects', $filename, 'public');
            $project->thumbnail = $path;
        }

        // Handle gallery images removal:
        $currentGallery = $project->gallery_images ?? [];
        $removeGallery = $request->input('remove_gallery', []);
        foreach ($removeGallery as $pathToRemove) {
            if (($key = array_search($pathToRemove, $currentGallery)) !== false) {
                unset($currentGallery[$key]);
                // Delete physical file
                $oldPath = preg_replace('#^storage/#', '', $pathToRemove);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        // Handle new gallery uploads:
        if ($request->hasFile('gallery_files')) {
            foreach ($request->file('gallery_files') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_gal_' . $file->getClientOriginalName();
                    $path = $file->storeAs('uploads/projects', $filename, 'public');
                    $currentGallery[] = $path;
                }
            }
        }
        $project->gallery_images = array_values($currentGallery);

        $project->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'project_link' => $request->input('project_link'),
            'github_link' => $request->input('github_link'),
            'position' => $request->input('position', 0) ?? 0,
            'featured' => $request->boolean('featured'),
            'archived' => $request->boolean('archived'),
            'is_published' => $request->boolean('is_published'),
            'year' => $request->input('year'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'category' => $request->input('category'),
        ]);

        // Handle tags
        $tagIds = $request->input('tags', []);
        
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
        Notification::send('project_updated', 'Project updated', 'Updated project: ' . $project->title, 'project', $project->id);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy(Project $project)
    {
        $title = $project->title;

        // Delete thumbnail from storage
        if ($project->thumbnail) {
            $oldPath = preg_replace('#^storage/#', '', $project->thumbnail);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        // Delete gallery images from storage
        if (is_array($project->gallery_images)) {
            foreach ($project->gallery_images as $pathToRemove) {
                $oldPath = preg_replace('#^storage/#', '', $pathToRemove);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
        }

        $project->delete();

        ActivityLog::log('Project deleted', 'Deleted project: ' . $title);
        Notification::send('project_deleted', 'Project removed', 'Deleted project: ' . $title);

        return redirect()->route('admin.projects.index')->with('success', 'Project berhasil dihapus.');
    }
}
