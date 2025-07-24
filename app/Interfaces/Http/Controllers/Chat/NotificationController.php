<?php

namespace App\Interfaces\Http\Controllers\Chat;

use Illuminate\Http\Request;
use App\Interfaces\Http\Requests\Chat\ReadNotificationRequest;

class NotificationController
{
    /**
     * List notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        // TODO: Replace with actual notification fetching logic (e.g., from DB or Kafka)
        $userId = $request->user()->id;
        // Example: $notifications = Notification::where('user_id', $userId)->latest()->get();
        $notifications = [];
        return response()->json(['notifications' => $notifications], 200);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(ReadNotificationRequest $request, $id)
    {
        // TODO: Replace with actual logic to mark notification as read
        // Example: $notification = Notification::where('id', $id)->where('user_id', $request->user()->id)->first();
        // if ($notification) { $notification->read_at = now(); $notification->save(); }
        return response()->json(['message' => 'Notification marked as read'], 200);
    }
} 