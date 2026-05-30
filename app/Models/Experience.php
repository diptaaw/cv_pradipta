<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'organization',
        'year',
        'description',
        'tags',
        'position',
        'featured',
        'is_published',
    ];

    protected $casts = [
        'tags' => 'array',
        'featured' => 'boolean',
        'is_published' => 'boolean',
    ];
}
