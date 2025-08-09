<?php

namespace App\Infrastructure\Repositories\Notification;

use App\Domain\Notification\Entities\NotificationPreference;
use App\Domain\Notification\Repositories\NotificationPreferenceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class NotificationPreferenceRepository implements NotificationPreferenceRepositoryInterface
{
    public function create(array $data): NotificationPreference
    {
        return NotificationPreference::create($data);
    }
    
    public function findById(int $id): ?NotificationPreference
    {
        return NotificationPreference::find($id);
    }
    
    public function findByUser(int $userId): Collection
    {
        return NotificationPreference::where('user_id', $userId)->get();
    }
    
    public function findByUserAndCategory(int $userId, string $category): ?NotificationPreference
    {
        return NotificationPreference::where('user_id', $userId)
            ->where('category', $category)
            ->active()
            ->first();
    }
    
    public function findActiveByUser(int $userId): Collection
    {
        return NotificationPreference::where('user_id', $userId)
            ->active()
            ->get();
    }
    
    public function updateOrCreate(array $criteria, array $data): NotificationPreference
    {
        return NotificationPreference::updateOrCreate($criteria, $data);
    }
    
    public function update(NotificationPreference $preference, array $data): bool
    {
        return $preference->update($data);
    }
    
    public function delete(NotificationPreference $preference): bool
    {
        return $preference->delete();
    }
} 