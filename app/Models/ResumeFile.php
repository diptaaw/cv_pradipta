<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeFile extends Model
{
    use HasFactory;

    protected $table = 'resume_files';

    protected $fillable = [
        'file_path',
        'title',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($resume) {
            if ($resume->is_published) {
                // Ensure only one active PDF is published at a time
                static::where('id', '!=', $resume->id)->update(['is_published' => false]);
            }
        });
    }
}
