<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'details',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            $log->created_at = $log->freshTimestamp();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($action, $details = null)
    {
        return static::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'details' => $details ? (is_array($details) ? json_encode($details) : $details) : null,
        ]);
    }
}
