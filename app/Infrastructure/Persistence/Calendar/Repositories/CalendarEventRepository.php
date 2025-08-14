<?php

namespace App\Infrastructure\Persistence\Calendar\Repositories;

use App\Models\CalendarEvent as CalendarEventModel;
use App\Domain\Calendar\Entities\CalendarEvent;
use App\Domain\Calendar\Repositories\CalendarEventRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CalendarEventRepository implements CalendarEventRepositoryInterface
{
    public function findById(int $id): ?CalendarEvent
    {
        $model = CalendarEventModel::with(['user', 'participants'])->find($id);
        return $model ? CalendarEvent::fromModel($model) : null;
    }

    public function create(array $data): CalendarEvent
    {
        $model = CalendarEventModel::create($data);
        return CalendarEvent::fromModel($model);
    }

    public function update(int $id, array $data): CalendarEvent
    {
        $model = CalendarEventModel::findOrFail($id);
        $model->update($data);
        return CalendarEvent::fromModel($model->fresh());
    }

    public function delete(int $id): bool
    {
        $model = CalendarEventModel::findOrFail($id);
        return $model->delete();
    }

    public function getEventsByDateRange(int $userId, Carbon $startDate, Carbon $endDate): Collection
    {
        return CalendarEventModel::where('user_id', $userId)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->with(['user', 'participants'])
            ->orderBy('start_time')
            ->get()
            ->map(fn($model) => CalendarEvent::fromModel($model));
    }

    public function getByTypeAndDateRange(int $userId, string $eventType, Carbon $startDate, Carbon $endDate): Collection
    {
        return CalendarEventModel::where('user_id', $userId)
            ->where('event_type', $eventType)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->with(['user', 'participants'])
            ->orderBy('start_time')
            ->get()
            ->map(fn($model) => CalendarEvent::fromModel($model));
    }

    public function getUpcoming(int $userId, int $limit = 10): Collection
    {
        return CalendarEventModel::where('user_id', $userId)
            ->where('start_time', '>', Carbon::now())
            ->with(['user', 'participants'])
            ->orderBy('start_time')
            ->limit($limit)
            ->get()
            ->map(fn($model) => CalendarEvent::fromModel($model));
    }

    public function getByDateRange(int $userId, Carbon $startDate, Carbon $endDate): Collection
    {
        return CalendarEventModel::where('user_id', $userId)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->with(['user', 'participants'])
            ->orderBy('start_time')
            ->get()
            ->map(fn($model) => CalendarEvent::fromModel($model));
    }

    public function countByUserId(int $userId): int
    {
        return CalendarEventModel::where('user_id', $userId)->count();
    }

    public function countByDateRange(int $userId, Carbon $startDate, Carbon $endDate): int
    {
        return CalendarEventModel::where('user_id', $userId)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->count();
    }

    public function countUpcoming(int $userId, Carbon $fromDate): int
    {
        return CalendarEventModel::where('user_id', $userId)
            ->where('start_time', '>', $fromDate)
            ->count();
    }

    public function countByType(int $userId): array
    {
        return CalendarEventModel::where('user_id', $userId)
            ->select('event_type', DB::raw('count(*) as count'))
            ->groupBy('event_type')
            ->pluck('count', 'event_type')
            ->toArray();
    }

    public function addParticipant(int $eventId, int $userId): bool
    {
        $event = CalendarEventModel::findOrFail($eventId);
        $event->participants()->attach($userId);
        return true;
    }

    public function removeAllParticipants(int $eventId): bool
    {
        $event = CalendarEventModel::findOrFail($eventId);
        $event->participants()->detach();
        return true;
    }

    public function getTodayEvents(int $userId): Collection
    {
        return CalendarEventModel::where('user_id', $userId)
            ->whereDate('start_time', Carbon::today())
            ->with(['user', 'participants'])
            ->orderBy('start_time')
            ->get()
            ->map(fn($model) => CalendarEvent::fromModel($model));
    }

    public function getThisMonthEvents(int $userId): Collection
    {
        return CalendarEventModel::where('user_id', $userId)
            ->whereMonth('start_time', Carbon::now()->month)
            ->whereYear('start_time', Carbon::now()->year)
            ->with(['user', 'participants'])
            ->orderBy('start_time')
            ->get()
            ->map(fn($model) => CalendarEvent::fromModel($model));
    }
} 