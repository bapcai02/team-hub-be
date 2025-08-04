<?php

namespace App\Infrastructure\Persistence\Meeting\Repositories;

use App\Domain\Meeting\Repositories\MeetingRepositoryInterface;
use App\Models\Meeting;
use Illuminate\Support\Facades\DB;

class MeetingRepository implements MeetingRepositoryInterface
{
    public function getAllMeetings(array $filters = []): array
    {
        $query = Meeting::with(['participants.user', 'creator']);
        
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['date_from'])) {
            $query->where('start_time', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->where('start_time', '<=', $filters['date_to']);
        }
        
        if (isset($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }
        
        return $query->orderBy('start_time', 'desc')->get()->toArray();
    }

    public function findById(int $id): ?Meeting
    {
        return Meeting::with(['participants.user', 'creator'])->find($id);
    }

    public function create(array $data): Meeting
    {
        return Meeting::create($data);
    }

    public function update(int $id, array $data): ?Meeting
    {
        $meeting = Meeting::find($id);
        if (!$meeting) {
            return null;
        }
        
        $meeting->update($data);
        return $meeting;
    }

    public function delete(int $id): bool
    {
        $meeting = Meeting::find($id);
        if (!$meeting) {
            return false;
        }
        
        return $meeting->delete();
    }
} 