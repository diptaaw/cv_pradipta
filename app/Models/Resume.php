<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'title',
        'notes',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
