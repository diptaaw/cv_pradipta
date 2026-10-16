<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
    ];

    public $timestamps = true;

    public static function get(string $key, $default = null)
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('site_settings')) {
                return $default;
            }

            $setting = static::where('key', $key)->first();

            return $setting ? $setting->value : $default;
        } catch (\Throwable $e) {
            return $default;
        }
    }
}
