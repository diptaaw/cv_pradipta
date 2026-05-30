<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'thumbnail',
        'description',
        'technologies',
        'project_link',
        'github_link',
        'featured',
        'archived',
        'is_published',
        'position',
    ];

    protected $casts = [
        'technologies' => 'array',
        'featured' => 'boolean',
        'archived' => 'boolean',
        'is_published' => 'boolean',
    ];
}
