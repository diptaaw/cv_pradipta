<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use App\Models\AboutSection;
use App\Models\Experience;
use App\Models\Project;
use App\Models\SocialLink;
use App\Models\Resume;

class DashboardController extends BaseAdminController
{
    public function index()
    {
        return view('admin.dashboard', [
            'experienceCount' => Experience::count(),
            'projectCount' => Project::count(),
            'aboutCount' => AboutSection::count(),
            'socialLinkCount' => SocialLink::count(),
            'resumeCount' => Resume::count(),
            'recentExperiences' => Experience::latest()->limit(3)->get(),
            'recentProjects' => Project::latest()->limit(3)->get(),
        ]);
    }
}
