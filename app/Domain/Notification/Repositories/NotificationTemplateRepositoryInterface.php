<?php

namespace App\Domain\Notification\Repositories;

use App\Domain\Notification\Entities\NotificationTemplate;
use Illuminate\Database\Eloquent\Collection;

interface NotificationTemplateRepositoryInterface
{
    public function create(array $data): NotificationTemplate;
    
    public function findById(int $id): ?NotificationTemplate;
    
    public function findByName(string $name): ?NotificationTemplate;
    
    public function findActive(): Collection;
    
    public function findByCategory(string $category): Collection;
    
    public function findByType(string $type): Collection;
    
    public function findByCategoryAndType(string $category, string $type): Collection;
    
    public function update(NotificationTemplate $template, array $data): bool;
    
    public function delete(NotificationTemplate $template): bool;
} 