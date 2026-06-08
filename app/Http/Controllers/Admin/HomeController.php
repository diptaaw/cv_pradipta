<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AboutSection;
use App\Models\SiteSetting;
use App\Models\ActivityLog;
use App\Models\Notification;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function edit()
    {
        $about = AboutSection::first();
        if (!$about) {
            $about = AboutSection::create([
                'headline' => 'Pradipta Adicandra Wicaksono',
                'subheadline' => 'Multimedia & Broadcasting Engineer',
                'short_intro' => 'Exploring the intersection of visuals, storytelling, and digital experiences.',
                'paragraphs' => [],
                'profile_image' => 'images/ui/avatar.png',
                'is_published' => true,
            ]);
        }

        $socials = [
            'github' => SiteSetting::get('social_github', ''),
            'instagram' => SiteSetting::get('social_instagram', ''),
            'linkedin' => SiteSetting::get('social_linkedin', ''),
            'email' => SiteSetting::get('social_email', ''),
        ];

        $footer = [
            'designed_text' => SiteSetting::get('footer_designed_text', 'Designed and developed by yours truly with Figma and Visual Studio Code. Created as a digital space to showcase multimedia production, visual storytelling, and creative technology projects.'),
            'copyright_text' => SiteSetting::get('footer_copyright_text', '© 2026 Pradipta Adicandra Wicaksono'),
        ];

        return view('admin.about.edit', compact('about', 'socials', 'footer'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'headline' => 'required|string|max:255',
            'subheadline' => 'required|string|max:255',
            'short_intro' => 'nullable|string',
            'profile_image' => 'nullable|image|max:5120', // Max 5MB
            'paragraphs' => 'nullable|array',
            'paragraphs.*' => 'nullable|string',
            'social_github' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'social_linkedin' => 'nullable|url|max:255',
            'social_email' => 'nullable|string|max:255',
            'footer_designed_text' => 'nullable|string|max:1000',
            'footer_copyright_text' => 'nullable|string|max:255',
        ]);

        $about = AboutSection::first();
        if (!$about) {
            $about = new AboutSection();
        }

        $about->headline = $request->input('headline');
        $about->subheadline = $request->input('subheadline');
        $about->short_intro = $request->input('short_intro');
        $about->is_published = $request->boolean('is_published', true);

        if ($request->hasFile('profile_image')) {
            // Delete old profile image if exists and is not the default
            if ($about->profile_image && $about->profile_image !== 'images/ui/avatar.png') {
                $oldPath = preg_replace('#^storage/#', '', $about->profile_image);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $file = $request->file('profile_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads/about', $filename, 'public');
            $about->profile_image = $path;
        }

        // Clean paragraphs
        $paragraphs = array_filter(array_map('trim', $request->input('paragraphs', [])));
        $about->paragraphs = array_values($paragraphs);
        $about->save();

        // Save Social Links
        SiteSetting::updateOrCreate(['key' => 'social_github'], ['value' => $request->input('social_github') ?? '']);
        SiteSetting::updateOrCreate(['key' => 'social_instagram'], ['value' => $request->input('social_instagram') ?? '']);
        SiteSetting::updateOrCreate(['key' => 'social_linkedin'], ['value' => $request->input('social_linkedin') ?? '']);
        SiteSetting::updateOrCreate(['key' => 'social_email'], ['value' => $request->input('social_email') ?? '']);

        // Save Footer Links
        SiteSetting::updateOrCreate(['key' => 'footer_designed_text'], ['value' => $request->input('footer_designed_text') ?? '']);
        SiteSetting::updateOrCreate(['key' => 'footer_copyright_text'], ['value' => $request->input('footer_copyright_text') ?? '']);

        ActivityLog::log('Home Content edited', 'Updated Left/Right panels, Social Links, and Footer.');
        Notification::send('homepage_updated', 'Homepage content updated', 'Updated Left/Right panels, Social Links, and Footer.');

        return redirect()->route('admin.about.edit')->with('success', 'Home Content berhasil diperbarui.');
    }
}
