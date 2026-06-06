<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use App\Models\ResumeFile;
use App\Models\ActivityLog;
use App\Models\AboutSection;

class DashboardController extends BaseAdminController
{
    public function index()
    {
        $publishedResume = ResumeFile::where('is_published', true)->latest()->first();
        $about = AboutSection::first();
        
        $aboutStatus = 'Not Configured';
        if ($about) {
            $aboutStatus = $about->is_published ? 'Published' : 'Draft';
        }

        return view('admin.dashboard', [
            'experienceCount' => Experience::count(),
            'projectCount' => Project::count(),
            'tagCount' => Tag::count(),
            'adminCount' => User::count(),
            'publishedResume' => $publishedResume,
            'recentActivities' => ActivityLog::with('user')->latest()->limit(8)->get(),
            'aboutStatus' => $aboutStatus,
            'featuredProjectsCount' => Project::where('featured', true)->count(),
            'featuredExperiencesCount' => Experience::where('featured', true)->count(),
        ]);
    }
}
