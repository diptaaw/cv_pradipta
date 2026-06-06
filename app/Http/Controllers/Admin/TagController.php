<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount(['projects', 'experiences'])->get();

        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $normalizedSlug = Tag::normalize($request->input('name'));

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($normalizedSlug) {
                    if (Tag::where('slug', $normalizedSlug)->exists()) {
                        $fail('Tag dengan nama "' . $value . '" sudah ada (tidak sensitif huruf besar/kecil).');
                    }
                },
            ],
        ]);

        $tag = Tag::create([
            'name' => trim($request->input('name')),
            'slug' => $normalizedSlug,
        ]);

        ActivityLog::log('Tag created', 'Created tag: ' . $tag->name);

        return redirect()->route('admin.tags.index')->with('success', 'Tag "' . $tag->name . '" berhasil ditambahkan.');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $normalizedSlug = Tag::normalize($request->input('name'));

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($normalizedSlug, $tag) {
                    if (Tag::where('slug', $normalizedSlug)->where('id', '!=', $tag->id)->exists()) {
                        $fail('Tag dengan nama "' . $value . '" sudah ada (tidak sensitif huruf besar/kecil).');
                    }
                },
            ],
        ]);

        $oldName = $tag->name;
        $tag->update([
            'name' => trim($request->input('name')),
            'slug' => $normalizedSlug,
        ]);

        ActivityLog::log('Tag updated', 'Updated tag from "' . $oldName . '" to "' . $tag->name . '"');

        return redirect()->route('admin.tags.index')->with('success', 'Tag berhasil diperbarui.');
    }

    public function destroy(Tag $tag)
    {
        $name = $tag->name;
        $tag->delete();

        ActivityLog::log('Tag deleted', 'Deleted tag: ' . $name);

        return redirect()->route('admin.tags.index')->with('success', 'Tag "' . $name . '" berhasil dihapus.');
    }
}
