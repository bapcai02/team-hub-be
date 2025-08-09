<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Notification\Services\NotificationService;
use App\Domain\Notification\Entities\Notification;
use App\Domain\Notification\Entities\NotificationPreference;
use App\Domain\Notification\Entities\NotificationTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get user notifications
     */
    public function getUserNotifications(Request $request): JsonResponse
    {
        $filters = $request->only(['category', 'status', 'unread']);
        $notifications = $this->notificationService->getUserNotifications(Auth::id(), $filters);

        return response()->json([
            'success' => true,
            'data' => $notifications
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        $notification = $this->notificationService->markAsRead($id, Auth::id());

        return response()->json([
            'success' => true,
            'data' => $notification
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $category = $request->get('category');

        $query = Notification::whereJsonContains('recipients', $userId)
            ->where('is_read', false);

        if ($category) {
            $query->where('category', $category);
        }

        $query->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Get notification statistics
     */
    public function getNotificationStats(): JsonResponse
    {
        $stats = $this->notificationService->getNotificationStats(Auth::id());

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Send notification
     */
    public function sendNotification(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'integer|exists:users,id',
            'type' => 'string|in:email,push,sms,in_app',
            'priority' => 'string|in:low,normal,high,urgent',
            'category' => 'string',
            'action_url' => 'nullable|url',
            'scheduled_at' => 'nullable|date',
        ]);

        $notification = $this->notificationService->sendNotification($request->all());

        return response()->json([
            'success' => true,
            'data' => $notification,
            'message' => 'Notification sent successfully'
        ]);
    }

    /**
     * Send template notification
     */
    public function sendTemplateNotification(Request $request): JsonResponse
    {
        $request->validate([
            'template_name' => 'required|string',
            'recipients' => 'required|array',
            'recipients.*' => 'integer|exists:users,id',
            'data' => 'array',
        ]);

        $notification = $this->notificationService->sendTemplateNotification(
            $request->template_name,
            $request->recipients,
            $request->data ?? []
        );

        return response()->json([
            'success' => true,
            'data' => $notification,
            'message' => 'Template notification sent successfully'
        ]);
    }

    /**
     * Get notification preferences
     */
    public function getNotificationPreferences(): JsonResponse
    {
        $preferences = $this->notificationService->getUserPreferences(Auth::id());

        return response()->json([
            'success' => true,
            'data' => $preferences
        ]);
    }

    /**
     * Update notification preferences
     */
    public function updateNotificationPreferences(Request $request): JsonResponse
    {
        $request->validate([
            'category' => 'required|string',
            'channels' => 'array',
            'channels.*' => 'string|in:email,push,sms,in_app',
            'frequency' => 'array',
            'quiet_hours' => 'array',
            'is_active' => 'boolean',
        ]);

        $preference = $this->notificationService->updateUserPreferences(Auth::id(), $request->all());

        return response()->json([
            'success' => true,
            'data' => $preference,
            'message' => 'Notification preferences updated successfully'
        ]);
    }

    /**
     * Get notification templates
     */
    public function getNotificationTemplates(Request $request): JsonResponse
    {
        $filters = $request->only(['category', 'type']);
        $templates = $this->notificationService->getNotificationTemplates($filters);

        return response()->json([
            'success' => true,
            'data' => $templates
        ]);
    }

    /**
     * Create notification template
     */
    public function createNotificationTemplate(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|unique:notification_templates,name',
            'category' => 'required|string',
            'type' => 'required|string|in:email,push,sms,in_app',
            'title_template' => 'required|string',
            'message_template' => 'required|string',
            'variables' => 'array',
            'channels' => 'array',
            'priority' => 'string|in:low,normal,high,urgent',
        ]);

        $template = NotificationTemplate::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $template,
            'message' => 'Notification template created successfully'
        ]);
    }

    /**
     * Update notification template
     */
    public function updateNotificationTemplate(Request $request, $id): JsonResponse
    {
        $template = NotificationTemplate::findOrFail($id);

        $request->validate([
            'name' => 'string|unique:notification_templates,name,' . $id,
            'category' => 'string',
            'type' => 'string|in:email,push,sms,in_app',
            'title_template' => 'string',
            'message_template' => 'string',
            'variables' => 'array',
            'channels' => 'array',
            'priority' => 'string|in:low,normal,high,urgent',
            'is_active' => 'boolean',
        ]);

        $template->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $template,
            'message' => 'Notification template updated successfully'
        ]);
    }

    /**
     * Delete notification template
     */
    public function deleteNotificationTemplate($id): JsonResponse
    {
        $template = NotificationTemplate::findOrFail($id);
        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification template deleted successfully'
        ]);
    }

    /**
     * Get notification categories
     */
    public function getNotificationCategories(): JsonResponse
    {
        $categories = [
            Notification::CATEGORY_SYSTEM => 'System',
            Notification::CATEGORY_PROJECT => 'Project',
            Notification::CATEGORY_FINANCE => 'Finance',
            Notification::CATEGORY_HR => 'HR',
            Notification::CATEGORY_DEVICE => 'Device',
            Notification::CATEGORY_CONTRACT => 'Contract',
        ];

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get notification channels
     */
    public function getNotificationChannels(): JsonResponse
    {
        $channels = [
            Notification::TYPE_EMAIL => 'Email',
            Notification::TYPE_PUSH => 'Push Notification',
            Notification::TYPE_SMS => 'SMS',
            Notification::TYPE_IN_APP => 'In-App',
        ];

        return response()->json([
            'success' => true,
            'data' => $channels
        ]);
    }

    /**
     * Get notification priorities
     */
    public function getNotificationPriorities(): JsonResponse
    {
        $priorities = [
            Notification::PRIORITY_LOW => 'Low',
            Notification::PRIORITY_NORMAL => 'Normal',
            Notification::PRIORITY_HIGH => 'High',
            Notification::PRIORITY_URGENT => 'Urgent',
        ];

        return response()->json([
            'success' => true,
            'data' => $priorities
        ]);
    }
} 