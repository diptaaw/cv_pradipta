<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'description',
        'reference_type',
        'reference_id',
        'is_read',
        'is_pinned',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_pinned' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Create a new notification record.
     *
     * @param string      $type           Notification type slug (e.g. 'project_created')
     * @param string      $title          Human-readable action title
     * @param string|null $description    Optional detail text
     * @param string|null $referenceType  Referenced model type (e.g. 'project', 'experience')
     * @param int|null    $referenceId    Referenced model ID
     * @param bool        $isPinned       Whether this notification is pinned
     * @return static
     */
    public static function send(
        string $type,
        string $title,
        ?string $description = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
        bool $isPinned = false
    ): static {
        return static::create([
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'is_pinned' => $isPinned,
        ]);
    }
}
