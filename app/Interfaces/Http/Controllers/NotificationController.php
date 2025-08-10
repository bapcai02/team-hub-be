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
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => []
                ], 401);
            }

            $notifications = $this->notificationService->getUserNotifications($user->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Notifications retrieved successfully',
                'data' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving notifications: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => []
                ], 401);
            }

            $result = $this->notificationService->markAsRead($id, $user->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking notification as read: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => []
                ], 401);
            }

            $result = $this->notificationService->markAllAsRead($user->id);
            
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error marking all notifications as read: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get notification statistics
     */
    public function getNotificationStats(Request $request): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => []
                ], 401);
            }

            $stats = $this->notificationService->getNotificationStats($user->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification statistics retrieved successfully',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving notification statistics: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Send notification
     */
    public function sendNotification(Request $request): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => []
                ], 401);
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'type' => 'required|string|in:in_app,email,push',
                'recipient_ids' => 'required|array',
                'recipient_ids.*' => 'integer|exists:users,id',
                'priority' => 'nullable|string|in:low,medium,high,urgent',
                'category' => 'nullable|string|max:100',
                'data' => 'nullable|array'
            ]);

            $result = $this->notificationService->sendNotification($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error sending notification: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Send template notification
     */
    public function sendTemplateNotification(Request $request): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => []
                ], 401);
            }

            $validated = $request->validate([
                'template_id' => 'required|integer|exists:notification_templates,id',
                'recipient_ids' => 'required|array',
                'recipient_ids.*' => 'integer|exists:users,id',
                'data' => 'nullable|array'
            ]);

            $result = $this->notificationService->sendTemplateNotification($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Template notification sent successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error sending template notification: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get notification preferences
     */
    public function getNotificationPreferences(Request $request): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => []
                ], 401);
            }

            $preferences = $this->notificationService->getNotificationPreferences($user->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification preferences retrieved successfully',
                'data' => $preferences
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving notification preferences: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Update notification preferences
     */
    public function updateNotificationPreferences(Request $request): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => []
                ], 401);
            }

            $validated = $request->validate([
                'preferences' => 'required|array',
                'preferences.*.type' => 'required|string|in:in_app,email,push',
                'preferences.*.enabled' => 'required|boolean'
            ]);

            $result = $this->notificationService->updateNotificationPreferences($user->id, $validated['preferences']);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification preferences updated successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating notification preferences: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get notification templates
     */
    public function getNotificationTemplates(Request $request): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => []
                ], 401);
            }

            $templates = $this->notificationService->getNotificationTemplates();
            
            return response()->json([
                'success' => true,
                'message' => 'Notification templates retrieved successfully',
                'data' => $templates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving notification templates: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Create notification template
     */
    public function createNotificationTemplate(Request $request): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => []
                ], 401);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'content' => 'required|string',
                'type' => 'required|string|in:in_app,email,push',
                'variables' => 'nullable|array'
            ]);

            $result = $this->notificationService->createNotificationTemplate($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification template created successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating notification template: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Update notification template
     */
    public function updateNotificationTemplate(Request $request, $id): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => []
                ], 401);
            }

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'subject' => 'sometimes|string|max:255',
                'content' => 'sometimes|string',
                'type' => 'sometimes|string|in:in_app,email,push',
                'variables' => 'nullable|array'
            ]);

            $result = $this->notificationService->updateNotificationTemplate($id, $validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification template updated successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating notification template: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Delete notification template
     */
    public function deleteNotificationTemplate(Request $request, $id): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => []
                ], 401);
            }

            $result = $this->notificationService->deleteNotificationTemplate($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Notification template deleted successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting notification template: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get notification categories
     */
    public function getNotificationCategories(Request $request): JsonResponse
    {
        try {
            $categories = [
                'system' => 'System',
                'project' => 'Project',
                'task' => 'Task',
                'meeting' => 'Meeting',
                'reminder' => 'Reminder',
                'announcement' => 'Announcement',
                'update' => 'Update',
                'alert' => 'Alert'
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Notification categories retrieved successfully',
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving notification categories: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get notification channels
     */
    public function getNotificationChannels(Request $request): JsonResponse
    {
        try {
            $channels = [
                'in_app' => 'In-App',
                'email' => 'Email',
                'push' => 'Push Notification',
                'sms' => 'SMS'
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Notification channels retrieved successfully',
                'data' => $channels
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving notification channels: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get notification priorities
     */
    public function getNotificationPriorities(Request $request): JsonResponse
    {
        try {
            $priorities = [
                'low' => 'Low',
                'medium' => 'Medium',
                'high' => 'High',
                'urgent' => 'Urgent'
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Notification priorities retrieved successfully',
                'data' => $priorities
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving notification priorities: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
} 