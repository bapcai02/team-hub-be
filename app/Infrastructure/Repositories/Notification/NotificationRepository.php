<?php

namespace App\Infrastructure\Repositories\Notification;

use App\Domain\Notification\Entities\Notification;
use App\Domain\Notification\Repositories\NotificationRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationRepository implements NotificationRepositoryInterface
{
    public function create(array $data): Notification
    {
        return Notification::create($data);
    }
    
    public function findById(int $id): ?Notification
    {
        return Notification::find($id);
    }
    
    public function findByUser(int $userId, array $filters = []): LengthAwarePaginator
    {
        $query = Notification::whereJsonContains('recipients', $userId);

        if (isset($filters['category'])) {
            $query->byCategory($filters['category']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['unread'])) {
            $query->unread();
        }

        return $query->orderBy('created_at', 'desc')->paginate(20);
    }
    
    public function findPending(): \Illuminate\Database\Eloquent\Collection
    {
        return Notification::pending()->get();
    }
    
    public function findScheduled(): \Illuminate\Database\Eloquent\Collection
    {
        return Notification::scheduled()->get();
    }
    
    public function findFailed(): \Illuminate\Database\Eloquent\Collection
    {
        return Notification::where('status', Notification::STATUS_FAILED)
            ->where('retry_count', '<', 3)
            ->get();
    }
    
    public function update(Notification $notification, array $data): bool
    {
        return $notification->update($data);
    }
    
    public function delete(Notification $notification): bool
    {
        return $notification->delete();
    }
    
    public function markAsRead(int $notificationId, int $userId): bool
    {
        $notification = Notification::where('id', $notificationId)
            ->whereJsonContains('recipients', $userId)
            ->first();

        if ($notification) {
            return $notification->markAsRead();
        }

        return false;
    }
    
    public function markAllAsRead(int $userId, ?string $category = null): int
    {
        $query = Notification::whereJsonContains('recipients', $userId)
            ->where('is_read', false);

        if ($category) {
            $query->where('category', $category);
        }

        return $query->update(['is_read' => true]);
    }
    
    public function getStats(int $userId = null): array
    {
        $query = Notification::query();

        if ($userId) {
            $query->whereJsonContains('recipients', $userId);
        }

        return [
            'total' => $query->count(),
            'sent' => $query->where('status', Notification::STATUS_SENT)->count(),
            'pending' => $query->where('status', Notification::STATUS_PENDING)->count(),
            'failed' => $query->where('status', Notification::STATUS_FAILED)->count(),
            'unread' => $query->unread()->count(),
        ];
    }
    
    public function cleanupOldNotifications(int $days = 30): int
    {
        $cutoffDate = now()->subDays($days);
        
        return Notification::where('created_at', '<', $cutoffDate)
            ->where('status', Notification::STATUS_SENT)
            ->delete();
    }
} 