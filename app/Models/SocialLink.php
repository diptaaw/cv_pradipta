<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform',
        'url',
        'icon',
        'position',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
