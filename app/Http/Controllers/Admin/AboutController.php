<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use App\Models\AboutSection;

class AboutController extends BaseAdminController
{
    public function edit()
    {
        $about = AboutSection::first();

        return view('admin.about.edit', compact('about'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'headline' => 'nullable|string|max:255',
            'subheadline' => 'nullable|string|max:255',
            'short_intro' => 'nullable|string|max:255',
            'profile_image' => 'nullable|string|max:255',
            'paragraphs' => 'nullable|string',
            'is_published' => 'nullable|boolean',
        ]);

        $data['paragraphs'] = $data['paragraphs']
            ? array_filter(array_map('trim', explode("\n", $data['paragraphs'])))
            : [];

        $about = AboutSection::first();

        if (!$about) {
            $about = new AboutSection();
        }

        $about->fill($data);
        $about->save();

        return redirect()->route('admin.about.edit')->with('success', 'Konten About berhasil diperbarui.');
    }
}
