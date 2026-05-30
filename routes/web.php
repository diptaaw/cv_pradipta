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

Route::get('/', function () {

    $about = Schema::hasTable('about_sections')
        ? AboutSection::where('is_published', true)->first()
        : null;

    $experiences = Schema::hasTable('experiences')
        ? Experience::where('is_published', true)
            ->orderBy('position')
            ->take(4)
            ->get()
        : collect([]);

    $projects = Schema::hasTable('projects')
        ? Project::where('is_published', true)
            ->orderBy('position')
            ->take(4)
            ->get()
        : collect([]);

    return view('home', compact('about', 'experiences', 'projects'));

});



Route::get('/projects', function () {

    return view('projects');

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
    Route::get('about', [AboutController::class, 'edit'])->name('about.edit');
    Route::put('about', [AboutController::class, 'update'])->name('about.update');
});


