<?php

namespace App\Application\Notification\Services;

use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Models\NotificationTemplate;
use App\Domain\Notification\Repositories\NotificationRepositoryInterface;
use App\Domain\Notification\Repositories\NotificationPreferenceRepositoryInterface;
use App\Domain\Notification\Repositories\NotificationTemplateRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    protected $notificationRepository;
    protected $preferenceRepository;
    protected $templateRepository;

    public function __construct(
        NotificationRepositoryInterface $notificationRepository,
        NotificationPreferenceRepositoryInterface $preferenceRepository,
        NotificationTemplateRepositoryInterface $templateRepository
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->preferenceRepository = $preferenceRepository;
        $this->templateRepository = $templateRepository;
    }

    /**
     * Send a notification to multiple users
     */
    public function sendNotification(array $data): Notification
    {
        try {
            $notification = $this->notificationRepository->create([
                'type' => $data['type'] ?? Notification::TYPE_IN_APP,
                'title' => $data['title'],
                'message' => $data['message'],
                'data' => $data['data'] ?? [],
                'status' => Notification::STATUS_PENDING,
                'priority' => $data['priority'] ?? Notification::PRIORITY_NORMAL,
                'scheduled_at' => $data['scheduled_at'] ?? now(),
                'recipients' => $data['recipients'] ?? [],
                'channel' => $data['channel'] ?? 'all',
                'category' => $data['category'] ?? Notification::CATEGORY_SYSTEM,
                'action_url' => $data['action_url'] ?? null,
                'metadata' => $data['metadata'] ?? [],
            ]);

            // Process notification based on user preferences
            $this->processNotification($notification);

            return $notification;
        } catch (\Exception $e) {
            Log::error('Failed to create notification: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Send notification using template
     */
    public function sendTemplateNotification(string $templateName, array $recipients, array $data = []): Notification
    {
        $template = $this->templateRepository->findByName($templateName);

        if (!$template) {
            throw new \Exception("Template not found: {$templateName}");
        }

        // Validate required data
        $this->validateTemplateData($template, $data);

        // Render template
        $title = $this->renderTemplate($template->title_template, $data);
        $message = $this->renderTemplate($template->message_template, $data);

        return $this->sendNotification([
            'type' => $template->type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'priority' => $template->priority,
            'recipients' => $recipients,
            'channel' => $template->channels[0] ?? 'all',
            'category' => $template->category,
            'metadata' => [
                'template_id' => $template->id,
                'template_name' => $template->name,
            ],
        ]);
    }

    /**
     * Validate template data
     */
    private function validateTemplateData(NotificationTemplate $template, array $data): void
    {
        foreach ($template->variables as $variable) {
            if ($variable['required'] && !isset($data[$variable['key']])) {
                throw new \Exception("Required variable missing: {$variable['key']}");
            }
        }
    }

    /**
     * Render template with data
     */
    private function renderTemplate(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace("{{$key}}", $value, $template);
        }
        return $template;
    }

    /**
     * Process notification based on user preferences
     */
    private function processNotification(Notification $notification): void
    {
        $recipients = $notification->recipients ?? [];
        
        foreach ($recipients as $userId) {
            $user = User::find($userId);
            if (!$user) continue;

            $preferences = $this->getUserPreferences($user->id);
            
            // Check if user wants this type of notification
            $categoryPreference = $preferences->where('category', $notification->category)->first();
            
            if (!$categoryPreference || !$categoryPreference->is_active) {
                continue;
            }

            // Send through preferred channels
            foreach ($categoryPreference->channels as $channel) {
                $this->sendThroughChannel($notification, $user, $channel);
            }
        }
    }

    /**
     * Send notification through specific channel
     */
    private function sendThroughChannel(Notification $notification, User $user, string $channel): void
    {
        try {
            switch ($channel) {
                case 'email':
                    $this->sendEmail($notification, $user);
                    break;
                case 'push':
                    $this->sendPushNotification($notification, $user);
                    break;
                case 'sms':
                    $this->sendSMS($notification, $user);
                    break;
                case 'in_app':
                    $this->sendInAppNotification($notification, $user);
                    break;
            }
        } catch (\Exception $e) {
            Log::error("Failed to send notification through {$channel}: " . $e->getMessage());
            $notification->markAsFailed($e->getMessage());
        }
    }

    /**
     * Send email notification
     */
    private function sendEmail(Notification $notification, User $user): void
    {
        // TODO: Implement email sending logic
        Log::info("Sending email notification to {$user->email}: {$notification->title}");
        $notification->markAsSent();
    }

    /**
     * Send push notification
     */
    private function sendPushNotification(Notification $notification, User $user): void
    {
        // TODO: Implement push notification logic
        Log::info("Sending push notification to user {$user->id}: {$notification->title}");
        $notification->markAsSent();
    }

    /**
     * Send SMS notification
     */
    private function sendSMS(Notification $notification, User $user): void
    {
        // TODO: Implement SMS sending logic
        Log::info("Sending SMS notification to user {$user->id}: {$notification->title}");
        $notification->markAsSent();
    }

    /**
     * Send in-app notification
     */
    private function sendInAppNotification(Notification $notification, User $user): void
    {
        // For in-app notifications, we just mark as sent
        Log::info("Sending in-app notification to user {$user->id}: {$notification->title}");
        $notification->markAsSent();
    }

    /**
     * Get user notifications with filters
     */
    public function getUserNotifications(int $userId, array $filters = [])
    {
        $query = Notification::where(function($q) use ($userId) {
            $q->whereJsonContains('recipients', $userId)
              ->orWhere('user_id', $userId)
              ->orWhereNull('recipients'); // Include notifications with null recipients for now
        });

        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['unread']) && $filters['unread']) {
            $query->where('is_read', false);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId, int $userId): ?Notification
    {
        $notification = Notification::whereJsonContains('recipients', $userId)
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return $notification;
        }

        return null;
    }

    /**
     * Get notification statistics
     */
    public function getNotificationStats(int $userId = null): array
    {
        $query = Notification::query();
        
        if ($userId) {
            $query->where(function($q) use ($userId) {
                $q->whereJsonContains('recipients', $userId)
                  ->orWhere('user_id', $userId)
                  ->orWhereNull('recipients'); // Include notifications with null recipients for now
            });
        }

        return [
            'total' => $query->count(),
            'sent' => $query->where('status', Notification::STATUS_SENT)->count(),
            'pending' => $query->where('status', Notification::STATUS_PENDING)->count(),
            'failed' => $query->where('status', Notification::STATUS_FAILED)->count(),
            'unread' => $query->where('is_read', false)->count(),
        ];
    }

    /**
     * Retry failed notifications
     */
    public function retryFailedNotifications(): int
    {
        $failedNotifications = Notification::where('status', Notification::STATUS_FAILED)
            ->where('retry_count', '<', 3)
            ->get();

        $retriedCount = 0;
        foreach ($failedNotifications as $notification) {
            try {
                $this->processNotification($notification);
                $retriedCount++;
            } catch (\Exception $e) {
                Log::error("Failed to retry notification {$notification->id}: " . $e->getMessage());
            }
        }

        return $retriedCount;
    }

    /**
     * Cleanup old notifications
     */
    public function cleanupOldNotifications(int $days = 30): int
    {
        $cutoffDate = now()->subDays($days);
        
        return Notification::where('created_at', '<', $cutoffDate)
            ->where('is_read', true)
            ->delete();
    }

    /**
     * Get user notification preferences
     */
    public function getUserPreferences(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return NotificationPreference::where('user_id', $userId)->get();
    }

    /**
     * Update user notification preferences
     */
    public function updateUserPreferences(int $userId, array $data): NotificationPreference
    {
        $preference = NotificationPreference::updateOrCreate(
            [
                'user_id' => $userId,
                'category' => $data['category'],
            ],
            [
                'channels' => $data['channels'],
                'frequency' => $data['frequency'],
                'quiet_hours' => $data['quiet_hours'] ?? null,
                'is_active' => $data['is_active'],
            ]
        );

        return $preference;
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead(int $userId): array
    {
        $updated = \App\Models\Notification::whereJsonContains('recipients', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return [
            'updated_count' => $updated,
            'message' => "Marked {$updated} notifications as read"
        ];
    }

    /**
     * Get notification preferences for a user
     */
    public function getNotificationPreferences(int $userId): array
    {
        $preferences = \App\Models\NotificationPreference::where('user_id', $userId)->get();
        
        return [
            'preferences' => $preferences,
            'default_preferences' => [
                'in_app' => true,
                'email' => true,
                'push' => false,
                'sms' => false
            ]
        ];
    }

    /**
     * Update notification preferences for a user
     */
    public function updateNotificationPreferences(int $userId, array $preferences): array
    {
        foreach ($preferences as $preference) {
            \App\Models\NotificationPreference::updateOrCreate(
                [
                    'user_id' => $userId,
                    'type' => $preference['type']
                ],
                [
                    'enabled' => $preference['enabled']
                ]
            );
        }

        return [
            'message' => 'Preferences updated successfully',
            'preferences' => $this->getNotificationPreferences($userId)
        ];
    }

    /**
     * Create a new notification template
     */
    public function createNotificationTemplate(array $data): \App\Models\NotificationTemplate
    {
        return \App\Models\NotificationTemplate::create([
            'name' => $data['name'],
            'subject' => $data['subject'],
            'content' => $data['content'],
            'type' => $data['type'],
            'variables' => $data['variables'] ?? []
        ]);
    }

    /**
     * Update an existing notification template
     */
    public function updateNotificationTemplate(int $id, array $data): \App\Models\NotificationTemplate
    {
        $template = \App\Models\NotificationTemplate::findOrFail($id);
        $template->update($data);
        return $template;
    }

    /**
     * Delete a notification template
     */
    public function deleteNotificationTemplate(int $id): array
    {
        $template = \App\Models\NotificationTemplate::findOrFail($id);
        $template->delete();

        return [
            'message' => 'Template deleted successfully',
            'deleted_template' => $template
        ];
    }

    /**
     * Get notification templates
     */
    public function getNotificationTemplates(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = \App\Models\NotificationTemplate::query();

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        return $query->get();
    }
} 