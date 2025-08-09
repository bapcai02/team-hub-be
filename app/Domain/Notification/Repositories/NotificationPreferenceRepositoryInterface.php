<?php

namespace App\Domain\Notification\Repositories;

use App\Domain\Notification\Entities\NotificationPreference;
use Illuminate\Database\Eloquent\Collection;

interface NotificationPreferenceRepositoryInterface
{
    public function create(array $data): NotificationPreference;
    
    public function findById(int $id): ?NotificationPreference;
    
    public function findByUser(int $userId): Collection;
    
    public function findByUserAndCategory(int $userId, string $category): ?NotificationPreference;
    
    public function findActiveByUser(int $userId): Collection;
    
    public function updateOrCreate(array $criteria, array $data): NotificationPreference;
    
    public function update(NotificationPreference $preference, array $data): bool;
    
    public function delete(NotificationPreference $preference): bool;
} 