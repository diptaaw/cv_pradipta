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
        'gallery_images',
        'year',
        'start_date',
        'end_date',
        'category',
        'description',
        'project_link',
        'github_link',
        'featured',
        'archived',
        'is_published',
        'position',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'featured' => 'boolean',
        'archived' => 'boolean',
        'is_published' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'project_tags');
    }

    public function getTechnologiesAttribute()
    {
        return $this->tags->pluck('name')->toArray();
    }
}
