<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioUpdate extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date',
        'is_pinned',
        'is_published',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_published' => 'boolean',
    ];
}
