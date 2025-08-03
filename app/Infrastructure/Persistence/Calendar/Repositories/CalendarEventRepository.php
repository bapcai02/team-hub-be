<?php

namespace App\Infrastructure\Persistence\Calendar\Repositories;

use App\Domain\Calendar\Entities\CalendarEvent;
use App\Domain\Calendar\Repositories\CalendarEventRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CalendarEventRepository implements CalendarEventRepositoryInterface
{
    public function findById(int $id): ?CalendarEvent
    {
        return CalendarEvent::with(['user', 'participants'])->find($id);
    }

    public function create(array $data): CalendarEvent
    {
        return CalendarEvent::create($data);
    }

    public function update(int $id, array $data): CalendarEvent
    {
        $event = CalendarEvent::findOrFail($id);
        $event->update($data);
        return $event->fresh();
    }

    public function delete(int $id): bool
    {
        $event = CalendarEvent::findOrFail($id);
        return $event->delete();
    }

    public function getEventsByDateRange(int $userId, Carbon $startDate, Carbon $endDate): Collection
    {
        return CalendarEvent::where('user_id', $userId)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->with(['user', 'participants'])
            ->orderBy('start_time')
            ->get();
    }

    public function getByTypeAndDateRange(int $userId, string $eventType, Carbon $startDate, Carbon $endDate): Collection
    {
        return CalendarEvent::where('user_id', $userId)
            ->where('event_type', $eventType)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->with(['user', 'participants'])
            ->orderBy('start_time')
            ->get();
    }

    public function getUpcoming(int $userId, int $limit = 10): Collection
    {
        return CalendarEvent::where('user_id', $userId)
            ->where('start_time', '>', Carbon::now())
            ->with(['user', 'participants'])
            ->orderBy('start_time')
            ->limit($limit)
            ->get();
    }

    public function getByDateRange(int $userId, Carbon $startDate, Carbon $endDate): Collection
    {
        return CalendarEvent::where('user_id', $userId)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->with(['user', 'participants'])
            ->orderBy('start_time')
            ->get();
    }

    public function countByUserId(int $userId): int
    {
        return CalendarEvent::where('user_id', $userId)->count();
    }

    public function countByDateRange(int $userId, Carbon $startDate, Carbon $endDate): int
    {
        return CalendarEvent::where('user_id', $userId)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->count();
    }

    public function countUpcoming(int $userId, Carbon $fromDate): int
    {
        return CalendarEvent::where('user_id', $userId)
            ->where('start_time', '>', $fromDate)
            ->count();
    }

    public function countByType(int $userId): array
    {
        return CalendarEvent::where('user_id', $userId)
            ->select('event_type', DB::raw('count(*) as count'))
            ->groupBy('event_type')
            ->pluck('count', 'event_type')
            ->toArray();
    }

    public function addParticipant(int $eventId, int $userId): bool
    {
        $event = CalendarEvent::findOrFail($eventId);
        $event->participants()->attach($userId);
        return true;
    }

    public function removeAllParticipants(int $eventId): bool
    {
        $event = CalendarEvent::findOrFail($eventId);
        $event->participants()->detach();
        return true;
    }

    public function getTodayEvents(int $userId): Collection
    {
        return CalendarEvent::where('user_id', $userId)
            ->whereDate('start_time', Carbon::today())
            ->with(['user', 'participants'])
            ->orderBy('start_time')
            ->get();
    }

    public function getThisMonthEvents(int $userId): Collection
    {
        return CalendarEvent::where('user_id', $userId)
            ->whereMonth('start_time', Carbon::now()->month)
            ->whereYear('start_time', Carbon::now()->year)
            ->with(['user', 'participants'])
            ->orderBy('start_time')
            ->get();
    }
} 