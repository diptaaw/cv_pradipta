<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use App\Models\Experience;

class ExperienceController extends BaseAdminController
{
    public function index()
    {
        $experiences = Experience::orderBy('position')->get();

        return view('admin.experiences.index', compact('experiences'));
    }

    public function create()
    {
        return view('admin.experiences.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'nullable|string',
            'position' => 'nullable|integer',
            'featured' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ]);

        $data['tags'] = $data['tags'] ? array_filter(array_map('trim', explode(',', $data['tags']))) : [];
        $data['featured'] = $request->boolean('featured');
        $data['is_published'] = $request->boolean('is_published');

        Experience::create($data);

        return redirect()->route('admin.experiences.index')->with('success', 'Pengalaman berhasil ditambahkan.');
    }

    public function edit(Experience $experience)
    {
        return view('admin.experiences.edit', compact('experience'));
    }

    public function update(Request $request, Experience $experience)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'nullable|string',
            'position' => 'nullable|integer',
            'featured' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ]);

        $data['tags'] = $data['tags'] ? array_filter(array_map('trim', explode(',', $data['tags']))) : [];
        $data['featured'] = $request->boolean('featured');
        $data['is_published'] = $request->boolean('is_published');

        $experience->update($data);

        return redirect()->route('admin.experiences.index')->with('success', 'Pengalaman berhasil diperbarui.');
    }

    public function destroy(Experience $experience)
    {
        $experience->delete();

        return redirect()->route('admin.experiences.index')->with('success', 'Pengalaman berhasil dihapus.');
    }
}
