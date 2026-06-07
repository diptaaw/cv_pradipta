<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use App\Models\Experience;
use App\Models\Tag;
use App\Models\ActivityLog;
use App\Models\Notification;

class ExperienceController extends BaseAdminController
{
    public function index()
    {
        $experiences = Experience::orderBy('position')->get();

        return view('admin.experiences.index', compact('experiences'));
    }

    public function create()
    {
        $tags = Tag::all();

        return view('admin.experiences.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'position' => 'nullable|integer',
            'featured' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ]);

        $data['featured'] = $request->boolean('featured');
        $data['is_published'] = $request->boolean('is_published');

        $experience = Experience::create($data);

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

        $experience->tags()->sync($tagIds);

        ActivityLog::log('Experience created', 'Created experience: ' . $experience->title);
        Notification::send('experience_created', 'New experience added', 'Created experience: ' . $experience->title, 'experience', $experience->id);

        return redirect()->route('admin.experiences.index')->with('success', 'Pengalaman berhasil ditambahkan.');
    }

    public function edit(Experience $experience)
    {
        $tags = Tag::all();
        $experienceTagIds = $experience->tags ? $experience->tags->pluck('id')->toArray() : [];

        return view('admin.experiences.edit', compact('experience', 'tags', 'experienceTagIds'));
    }

    public function update(Request $request, Experience $experience)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'position' => 'nullable|integer',
            'featured' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ]);

        $data['featured'] = $request->boolean('featured');
        $data['is_published'] = $request->boolean('is_published');

        $experience->update($data);

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

        $experience->tags()->sync($tagIds);

        ActivityLog::log('Experience edited', 'Edited experience: ' . $experience->title);
        Notification::send('experience_updated', 'Experience updated', 'Updated experience: ' . $experience->title, 'experience', $experience->id);

        return redirect()->route('admin.experiences.index')->with('success', 'Pengalaman berhasil diperbarui.');
    }

    public function destroy(Experience $experience)
    {
        $title = $experience->title;
        $experience->delete();

        ActivityLog::log('Experience deleted', 'Deleted experience: ' . $title);
        Notification::send('experience_deleted', 'Experience removed', 'Deleted experience: ' . $title);

        return redirect()->route('admin.experiences.index')->with('success', 'Pengalaman berhasil dihapus.');
    }
}
