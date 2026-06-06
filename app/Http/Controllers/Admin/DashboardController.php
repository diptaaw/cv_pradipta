<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use App\Models\ResumeFile;
use App\Models\ActivityLog;

class DashboardController extends BaseAdminController
{
    public function index()
    {
        $publishedResume = ResumeFile::where('is_published', true)->latest()->first();

        return view('admin.dashboard', [
            'experienceCount' => Experience::count(),
            'projectCount' => Project::count(),
            'tagCount' => Tag::count(),
            'adminCount' => User::count(),
            'publishedResume' => $publishedResume,
            'recentActivities' => ActivityLog::with('user')->latest()->limit(10)->get(),
        ]);
    }
}
