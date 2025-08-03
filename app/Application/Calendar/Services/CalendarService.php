<?php

namespace App\Application\Calendar\Services;

use App\Domain\Calendar\Entities\CalendarEvent;
use App\Domain\Calendar\Repositories\CalendarEventRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CalendarService
{
    protected $calendarEventRepository;

    public function __construct(CalendarEventRepositoryInterface $calendarEventRepository)
    {
        $this->calendarEventRepository = $calendarEventRepository;
    }

    /**
     * Get calendar events for a specific date range
     */
    public function getEvents(int $userId, ?string $startDate = null, ?string $endDate = null): Collection
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();

        return $this->calendarEventRepository->getEventsByDateRange($userId, $startDate, $endDate);
    }

    /**
     * Create a new calendar event
     */
    public function createEvent(int $userId, array $data): CalendarEvent
    {
        $eventData = [
            'user_id' => $userId,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'start_time' => Carbon::parse($data['start_time']),
            'end_time' => Carbon::parse($data['end_time']),
            'event_type' => $data['event_type'],
            'color' => $data['color'] ?? $this->getDefaultColor($data['event_type']),
            'is_all_day' => $data['is_all_day'] ?? false,
            'location' => $data['location'] ?? null,
        ];

        $event = $this->calendarEventRepository->create($eventData);

        // Add participants if provided
        if (!empty($data['participant_ids'])) {
            $this->addParticipants($event->id, $data['participant_ids']);
        }

        return $event;
    }

    /**
     * Update an existing calendar event
     */
    public function updateEvent(int $eventId, int $userId, array $data): CalendarEvent
    {
        $event = $this->calendarEventRepository->findById($eventId);

        if (!$event || $event->user_id !== $userId) {
            throw new \Exception('Event not found or access denied');
        }

        $updateData = [];
        
        if (isset($data['title'])) {
            $updateData['title'] = $data['title'];
        }
        if (isset($data['description'])) {
            $updateData['description'] = $data['description'];
        }
        if (isset($data['start_time'])) {
            $updateData['start_time'] = Carbon::parse($data['start_time']);
        }
        if (isset($data['end_time'])) {
            $updateData['end_time'] = Carbon::parse($data['end_time']);
        }
        if (isset($data['event_type'])) {
            $updateData['event_type'] = $data['event_type'];
        }
        if (isset($data['color'])) {
            $updateData['color'] = $data['color'];
        }
        if (isset($data['is_all_day'])) {
            $updateData['is_all_day'] = $data['is_all_day'];
        }
        if (isset($data['location'])) {
            $updateData['location'] = $data['location'];
        }

        $event = $this->calendarEventRepository->update($eventId, $updateData);

        // Update participants if provided
        if (isset($data['participant_ids'])) {
            $this->updateParticipants($eventId, $data['participant_ids']);
        }

        return $event;
    }

    /**
     * Delete a calendar event
     */
    public function deleteEvent(int $eventId, int $userId): bool
    {
        $event = $this->calendarEventRepository->findById($eventId);

        if (!$event || $event->user_id !== $userId) {
            throw new \Exception('Event not found or access denied');
        }

        return $this->calendarEventRepository->delete($eventId);
    }

    /**
     * Get calendar statistics
     */
    public function getStats(int $userId): array
    {
        $today = Carbon::now();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $totalEvents = $this->calendarEventRepository->countByUserId($userId);
        $todayEvents = $this->calendarEventRepository->countByDateRange($userId, $today->startOfDay(), $today->endOfDay());
        $monthEvents = $this->calendarEventRepository->countByDateRange($userId, $startOfMonth, $endOfMonth);
        $upcomingEvents = $this->calendarEventRepository->countUpcoming($userId, $today);

        $eventsByType = $this->calendarEventRepository->countByType($userId);

        return [
            'total' => $totalEvents,
            'today' => $todayEvents,
            'this_month' => $monthEvents,
            'upcoming' => $upcomingEvents,
            'by_type' => $eventsByType,
        ];
    }

    /**
     * Get upcoming events
     */
    public function getUpcomingEvents(int $userId, int $limit = 10): Collection
    {
        return $this->calendarEventRepository->getUpcoming($userId, $limit);
    }

    /**
     * Get events for today
     */
    public function getTodayEvents(int $userId): Collection
    {
        $today = Carbon::now();
        return $this->calendarEventRepository->getByDateRange(
            $userId, 
            $today->startOfDay(), 
            $today->endOfDay()
        );
    }

    /**
     * Get events by type
     */
    public function getEventsByType(int $userId, string $eventType, ?string $startDate = null, ?string $endDate = null): Collection
    {
        $startDate = $startDate ? Carbon::parse($startDate) : Carbon::now()->startOfMonth();
        $endDate = $endDate ? Carbon::parse($endDate) : Carbon::now()->endOfMonth();

        return $this->calendarEventRepository->getByTypeAndDateRange($userId, $eventType, $startDate, $endDate);
    }

    /**
     * Add participants to an event
     */
    private function addParticipants(int $eventId, array $participantIds): void
    {
        foreach ($participantIds as $participantId) {
            $this->calendarEventRepository->addParticipant($eventId, $participantId);
        }
    }

    /**
     * Update participants for an event
     */
    private function updateParticipants(int $eventId, array $participantIds): void
    {
        // Remove all existing participants
        $this->calendarEventRepository->removeAllParticipants($eventId);
        
        // Add new participants
        $this->addParticipants($eventId, $participantIds);
    }

    /**
     * Get default color for event type
     */
    private function getDefaultColor(string $eventType): string
    {
        return match ($eventType) {
            'meeting' => '#4285f4',
            'task' => '#34a853',
            'reminder' => '#fbbc04',
            'other' => '#ea4335',
            default => '#4285f4',
        };
    }
} 