<?php

namespace App\Domain\Calendar\Repositories;

use App\Domain\Calendar\Entities\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface CalendarEventRepositoryInterface
{
    /**
     * Find event by ID
     */
    public function findById(int $id): ?CalendarEvent;

    /**
     * Create a new event
     */
    public function create(array $data): CalendarEvent;

    /**
     * Update an existing event
     */
    public function update(int $id, array $data): CalendarEvent;

    /**
     * Delete an event
     */
    public function delete(int $id): bool;

    /**
     * Get events by date range for a user
     */
    public function getEventsByDateRange(int $userId, Carbon $startDate, Carbon $endDate): Collection;

    /**
     * Get events by type and date range
     */
    public function getByTypeAndDateRange(int $userId, string $eventType, Carbon $startDate, Carbon $endDate): Collection;

    /**
     * Get upcoming events
     */
    public function getUpcoming(int $userId, int $limit = 10): Collection;

    /**
     * Get events by date range
     */
    public function getByDateRange(int $userId, Carbon $startDate, Carbon $endDate): Collection;

    /**
     * Count events by user ID
     */
    public function countByUserId(int $userId): int;

    /**
     * Count events by date range
     */
    public function countByDateRange(int $userId, Carbon $startDate, Carbon $endDate): int;

    /**
     * Count upcoming events
     */
    public function countUpcoming(int $userId, Carbon $fromDate): int;

    /**
     * Count events by type
     */
    public function countByType(int $userId): array;

    /**
     * Add participant to event
     */
    public function addParticipant(int $eventId, int $userId): bool;

    /**
     * Remove all participants from event
     */
    public function removeAllParticipants(int $eventId): bool;

    /**
     * Get events for today
     */
    public function getTodayEvents(int $userId): Collection;

    /**
     * Get events for this month
     */
    public function getThisMonthEvents(int $userId): Collection;
} 