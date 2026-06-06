<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Middleware\EnsureAdmin;
use App\Models\AboutSection;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Resume;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Route::get('/', function () {

    $about = Schema::hasTable('about_sections')
        ? AboutSection::where('is_published', true)->first()
        : null;

    $experiences = Schema::hasTable('experiences')
        ? Experience::where('is_published', true)
            ->where('featured', true)
            ->orderBy('position')
            ->take(5)
            ->get()
        : collect([]);

    $projects = Schema::hasTable('projects')
        ? Project::where('is_published', true)
            ->where('featured', true)
            ->orderBy('position')
            ->take(5)
            ->get()
        : collect([]);

    return view('home', compact('about', 'experiences', 'projects'));

});



Route::get('/projects', function () {

    return view('projects');

});

Route::get('/archive', function () {

    $projects = Schema::hasTable('projects')
        ? Project::where('is_published', true)
            ->orderBy('position')
            ->latest('updated_at')
            ->get()
        : collect([]);

    return view('archive', compact('projects'));

});

Route::get('/resume', function () {

    $resume = Schema::hasTable('resume_files')
        ? \App\Models\ResumeFile::where('is_published', true)
            ->latest('updated_at')
            ->first()
        : null;

    $resumeUrl = null;

    if ($resume && $resume->file_path) {
        $path = $resume->file_path;

        $resumeUrl = Str::startsWith($path, ['http://', 'https://', '/'])
            ? $path
            : Storage::url($path);
    }

    return view('resume', compact('resume', 'resumeUrl'));

});


Route::get('/api/archive/projects', function (\Illuminate\Http\Request $request) {
    $query = \App\Models\Project::where('is_published', true)->orderBy('position');

    if ($request->filled('q')) {
        $search = $request->input('q');
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    if ($request->filled('category')) {
        $query->where('category', $request->input('category'));
    }

    if ($request->filled('year')) {
        $query->where('year', $request->input('year'));
    }

    if ($request->filled('tag')) {
        $tagValue = $request->input('tag');
        $query->whereHas('tags', function ($q) use ($tagValue) {
            $q->where('slug', \App\Models\Tag::normalize($tagValue))
              ->orWhere('name', $tagValue);
        });
    }

    $projects = $query->with('tags')->get();

    // Trigger technologies accessor
    $projects->each(function ($p) {
        $p->technologies = $p->technologies;
    });

    $categories = \App\Models\Project::where('is_published', true)
        ->whereNotNull('category')
        ->where('category', '!=', '')
        ->distinct()
        ->orderBy('category')
        ->pluck('category');

    $years = \App\Models\Project::where('is_published', true)
        ->whereNotNull('year')
        ->where('year', '!=', '')
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year');

    $tags = \App\Models\Tag::whereHas('projects', function ($q) {
            $q->where('is_published', true);
        })
        ->distinct()
        ->orderBy('name')
        ->pluck('name');

    return response()->json([
        'success' => true,
        'projects' => $projects,
        'filters' => [
            'categories' => $categories,
            'years' => $years,
            'tags' => $tags,
        ]
    ]);
});


/* -----------------------------
   Admin routes
   ----------------------------- */
Route::get('/admin/login', [AuthController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'authenticate'])->name('admin.login.attempt');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::middleware([EnsureAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('experiences', ExperienceController::class)->except(['show']);
    Route::resource('projects', ProjectController::class)->except(['show']);
    Route::resource('tags', \App\Http\Controllers\Admin\TagController::class)->except(['show']);
    Route::resource('media', \App\Http\Controllers\Admin\MediaController::class)->only(['index', 'store', 'destroy']);
    Route::resource('resumes', \App\Http\Controllers\Admin\ResumeFileController::class)->except(['show']);
    Route::post('resumes/{resume}/publish', [\App\Http\Controllers\Admin\ResumeFileController::class, 'publish'])->name('resumes.publish');
    Route::post('resumes/{resume}/unpublish', [\App\Http\Controllers\Admin\ResumeFileController::class, 'unpublish'])->name('resumes.unpublish');
    Route::resource('admins', \App\Http\Controllers\Admin\AdminUserController::class)->except(['show']);
    Route::get('about', [AboutController::class, 'edit'])->name('about.edit');
    Route::put('about', [AboutController::class, 'update'])->name('about.update');
});
