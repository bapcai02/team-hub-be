<?php

namespace App\Infrastructure\Repositories\Notification;

use App\Domain\Notification\Entities\NotificationTemplate;
use App\Domain\Notification\Repositories\NotificationTemplateRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class NotificationTemplateRepository implements NotificationTemplateRepositoryInterface
{
    public function create(array $data): NotificationTemplate
    {
        return NotificationTemplate::create($data);
    }
    
    public function findById(int $id): ?NotificationTemplate
    {
        return NotificationTemplate::find($id);
    }
    
    public function findByName(string $name): ?NotificationTemplate
    {
        return NotificationTemplate::where('name', $name)
            ->active()
            ->first();
    }
    
    public function findActive(): Collection
    {
        return NotificationTemplate::active()->get();
    }
    
    public function findByCategory(string $category): Collection
    {
        return NotificationTemplate::byCategory($category)
            ->active()
            ->get();
    }
    
    public function findByType(string $type): Collection
    {
        return NotificationTemplate::byType($type)
            ->active()
            ->get();
    }
    
    public function findByCategoryAndType(string $category, string $type): Collection
    {
        return NotificationTemplate::byCategory($category)
            ->byType($type)
            ->active()
            ->get();
    }
    
    public function update(NotificationTemplate $template, array $data): bool
    {
        return $template->update($data);
    }
    
    public function delete(NotificationTemplate $template): bool
    {
        return $template->delete();
    }
} 