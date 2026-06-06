<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tag) {
            $tag->slug = static::normalize($tag->name);
        });

        static::updating(function ($tag) {
            $tag->slug = static::normalize($tag->name);
        });
    }

    public static function normalize($name)
    {
        return Str::lower(trim($name));
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_tags');
    }

    public function experiences()
    {
        return $this->belongsToMany(Experience::class, 'experience_tags');
    }

    public function getUsageCountAttribute()
    {
        return $this->projects()->count() + $this->experiences()->count();
    }
}
