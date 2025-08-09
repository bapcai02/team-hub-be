<?php

namespace App\Application\Notification\Services;

use App\Domain\Notification\Entities\Notification;
use App\Domain\Notification\Entities\NotificationPreference;
use App\Domain\Notification\Entities\NotificationTemplate;
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
        $template->validateData($data);

        // Render template
        $title = $template->renderTitle($data);
        $message = $template->renderMessage($data);

        return $this->sendNotification([
            'type' => $template->type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'priority' => $template->priority,
            'recipients' => $recipients,
            'channel' => $template->getChannels()[0] ?? 'all',
            'category' => $template->category,
            'metadata' => [
                'template_id' => $template->id,
                'template_name' => $template->name,
            ],
        ]);
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

            $preferences = $this->preferenceRepository->findByUserAndCategory($user->id, $notification->category);
            
            if (!$preferences || !$preferences->isEnabled()) {
                continue;
            }

            // Check quiet hours
            if ($preferences->isInQuietHours() && !$notification->isUrgent()) {
                continue;
            }

            // Send through enabled channels
            foreach ($preferences->getEnabledChannels() as $channel) {
                if ($preferences->isChannelEnabled($channel)) {
                    $this->sendThroughChannel($notification, $user, $channel);
                }
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
                case Notification::TYPE_EMAIL:
                    $this->sendEmail($notification, $user);
                    break;
                case Notification::TYPE_PUSH:
                    $this->sendPushNotification($notification, $user);
                    break;
                case Notification::TYPE_SMS:
                    $this->sendSMS($notification, $user);
                    break;
                case Notification::TYPE_IN_APP:
                    $this->sendInAppNotification($notification, $user);
                    break;
            }

            $notification->markAsSent();
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
        // For now, just log the email
        Log::info("Email sent to {$user->email}: {$notification->title}");
    }

    /**
     * Send push notification
     */
    private function sendPushNotification(Notification $notification, User $user): void
    {
        // TODO: Implement push notification logic
        // For now, just log the push notification
        Log::info("Push notification sent to user {$user->id}: {$notification->title}");
    }

    /**
     * Send SMS notification
     */
    private function sendSMS(Notification $notification, User $user): void
    {
        // TODO: Implement SMS sending logic
        // For now, just log the SMS
        Log::info("SMS sent to user {$user->id}: {$notification->title}");
    }

    /**
     * Send in-app notification
     */
    private function sendInAppNotification(Notification $notification, User $user): void
    {
        // Create a copy of the notification for the specific user
        $this->notificationRepository->create([
            'type' => Notification::TYPE_IN_APP,
            'title' => $notification->title,
            'message' => $notification->message,
            'data' => $notification->data,
            'status' => Notification::STATUS_SENT,
            'priority' => $notification->priority,
            'sent_at' => now(),
            'recipients' => [$user->id],
            'channel' => Notification::TYPE_IN_APP,
            'category' => $notification->category,
            'action_url' => $notification->action_url,
            'metadata' => $notification->metadata,
        ]);
    }

    /**
     * Get user notifications
     */
    public function getUserNotifications(int $userId, array $filters = [])
    {
        return $this->notificationRepository->findByUser($userId, $filters);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $notificationId, int $userId): ?Notification
    {
        $success = $this->notificationRepository->markAsRead($notificationId, $userId);
        
        if ($success) {
            return $this->notificationRepository->findById($notificationId);
        }

        return null;
    }

    /**
     * Get notification statistics
     */
    public function getNotificationStats(int $userId = null): array
    {
        return $this->notificationRepository->getStats($userId);
    }

    /**
     * Retry failed notifications
     */
    public function retryFailedNotifications(): int
    {
        $failedNotifications = $this->notificationRepository->findFailed();

        foreach ($failedNotifications as $notification) {
            $this->processNotification($notification);
        }

        return $failedNotifications->count();
    }

    /**
     * Clean up old notifications
     */
    public function cleanupOldNotifications(int $days = 30): int
    {
        return $this->notificationRepository->cleanupOldNotifications($days);
    }

    /**
     * Get notification preferences for user
     */
    public function getUserPreferences(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return $this->preferenceRepository->findByUser($userId);
    }

    /**
     * Update notification preferences
     */
    public function updateUserPreferences(int $userId, array $data): NotificationPreference
    {
        return $this->preferenceRepository->updateOrCreate(
            [
                'user_id' => $userId,
                'category' => $data['category'],
            ],
            $data
        );
    }

    /**
     * Get notification templates
     */
    public function getNotificationTemplates(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        if (isset($filters['category']) && isset($filters['type'])) {
            return $this->templateRepository->findByCategoryAndType($filters['category'], $filters['type']);
        }

        if (isset($filters['category'])) {
            return $this->templateRepository->findByCategory($filters['category']);
        }

        if (isset($filters['type'])) {
            return $this->templateRepository->findByType($filters['type']);
        }

        return $this->templateRepository->findActive();
    }
} 