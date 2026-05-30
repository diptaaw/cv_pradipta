<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'headline',
        'subheadline',
        'paragraphs',
        'profile_image',
        'short_intro',
        'is_published',
    ];

    protected $casts = [
        'paragraphs' => 'array',
        'is_published' => 'boolean',
    ];
}
