<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * GET /api/notifications
     *
     * Returns latest notifications. Pinned first, then newest.
     * Supports ?after_id= for polling and ?page= for pagination.
     */
    public function index(Request $request)
    {
        $perPage = 10;

        // Polling mode: return only notifications newer than a given ID
        if ($request->filled('after_id')) {
            $notifications = Notification::where('id', '>', $request->input('after_id'))
                ->orderBy('is_pinned', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            return response()->json($notifications);
        }

        // Pagination mode
        $notifications = Notification::orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json($notifications);
    }

    /**
     * GET /api/notifications/unread-count
     *
     * Returns the count of unread notifications.
     */
    public function unreadCount()
    {
        $count = Notification::where('is_read', false)->count();

        return response()->json(['count' => $count]);
    }

    /**
     * POST /api/notifications/mark-read
     *
     * Mark all notifications as read.
     */
    public function markAllRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    /**
     * POST /api/notifications/{id}/mark-read
     *
     * Mark a single notification as read.
     */
    public function markRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
