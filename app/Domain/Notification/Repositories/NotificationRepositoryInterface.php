<?php

namespace App\Domain\Notification\Repositories;

use App\Domain\Notification\Entities\Notification;
use Illuminate\Pagination\LengthAwarePaginator;

interface NotificationRepositoryInterface
{
    public function create(array $data): Notification;
    
    public function findById(int $id): ?Notification;
    
    public function findByUser(int $userId, array $filters = []): LengthAwarePaginator;
    
    public function findPending(): \Illuminate\Database\Eloquent\Collection;
    
    public function findScheduled(): \Illuminate\Database\Eloquent\Collection;
    
    public function findFailed(): \Illuminate\Database\Eloquent\Collection;
    
    public function update(Notification $notification, array $data): bool;
    
    public function delete(Notification $notification): bool;
    
    public function markAsRead(int $notificationId, int $userId): bool;
    
    public function markAllAsRead(int $userId, ?string $category = null): int;
    
    public function getStats(int $userId = null): array;
    
    public function cleanupOldNotifications(int $days = 30): int;
} 