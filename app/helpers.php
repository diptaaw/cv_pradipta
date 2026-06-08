<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
