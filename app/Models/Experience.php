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
        'start_date',
        'end_date',
        'description',
        'position',
        'featured',
        'is_published',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'is_published' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'experience_tags');
    }

}
