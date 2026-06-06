<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'file_path',
        'filename',
        'mime_type',
        'size',
    ];

    public function getUrlAttribute()
    {
        return Storage::url($this->file_path);
    }
}
