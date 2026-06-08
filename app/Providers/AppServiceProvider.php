<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! function_exists('storage_url')) {
            function storage_url(?string $path): ?string
            {
                if (! $path) {
                    return null;
                }

                $path = Str::startsWith($path, 'storage/')
                    ? Str::replaceFirst('storage/', '', $path)
                    : $path;

                return Storage::url($path);
            }
        }
    }
}
