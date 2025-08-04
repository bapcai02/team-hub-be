<?php

namespace App\Application\Meeting\Services;

use App\Domain\Meeting\Repositories\MeetingRepositoryInterface;
use App\Models\Meeting;
use App\Models\MeetingParticipant;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MeetingService
{
    public function __construct(protected MeetingRepositoryInterface $meetingRepository) {}

    public function getAllMeetings(array $filters = []): array
    {
        return $this->meetingRepository->getAllMeetings($filters);
    }

    public function createMeeting(array $data): Meeting
    {
        $data['created_by'] = Auth::id();
        $data['status'] = 'scheduled';
        
        $meeting = $this->meetingRepository->create($data);
        
        // Add creator as participant
        if ($meeting) {
            MeetingParticipant::create([
                'meeting_id' => $meeting->id,
                'user_id' => Auth::id(),
            ]);
        }
        
        return $meeting;
    }

    public function getMeetingById(int $id): ?Meeting
    {
        return $this->meetingRepository->findById($id);
    }

    public function updateMeeting(int $id, array $data): ?Meeting
    {
        return $this->meetingRepository->update($id, $data);
    }

    public function deleteMeeting(int $id): bool
    {
        return $this->meetingRepository->delete($id);
    }

    public function addParticipants(int $meetingId, array $userIds): array
    {
        $participants = [];
        
        foreach ($userIds as $userId) {
            $participant = MeetingParticipant::create([
                'meeting_id' => $meetingId,
                'user_id' => $userId,
                'status' => 'invited'
            ]);
            $participants[] = $participant;
        }
        
        return $participants;
    }

    public function removeParticipant(int $meetingId, int $userId): bool
    {
        return MeetingParticipant::where('meeting_id', $meetingId)
            ->where('user_id', $userId)
            ->delete() > 0;
    }

    public function startMeeting(int $id): ?Meeting
    {
        $meeting = $this->getMeetingById($id);
        if (!$meeting) {
            return null;
        }
        
        $meeting->update([
            'status' => 'ongoing'
        ]);
        
        return $meeting;
    }

    public function endMeeting(int $id): ?Meeting
    {
        $meeting = $this->getMeetingById($id);
        if (!$meeting) {
            return null;
        }
        
        $meeting->update([
            'status' => 'finished'
        ]);
        
        return $meeting;
    }

    public function cancelMeeting(int $id): ?Meeting
    {
        $meeting = $this->getMeetingById($id);
        if (!$meeting) {
            return null;
        }
        
        $meeting->update([
            'status' => 'cancelled'
        ]);
        
        return $meeting;
    }

    public function getMeetingStats(array $filters = []): array
    {
        $totalMeetings = Meeting::count();
        $scheduledMeetings = Meeting::where('status', 'scheduled')->count();
        $ongoingMeetings = Meeting::where('status', 'ongoing')->count();
        $finishedMeetings = Meeting::where('status', 'finished')->count();
        $cancelledMeetings = Meeting::where('status', 'cancelled')->count();
        
        $upcomingMeetings = Meeting::where('status', 'scheduled')
            ->where('start_time', '>', Carbon::now())
            ->count();
        
        return [
            'total' => $totalMeetings,
            'scheduled' => $scheduledMeetings,
            'ongoing' => $ongoingMeetings,
            'finished' => $finishedMeetings,
            'cancelled' => $cancelledMeetings,
            'upcoming' => $upcomingMeetings,
        ];
    }

    public function getMeetingCalendar(array $filters = []): array
    {
        $startDate = $filters['start_date'] ?? Carbon::now()->startOfMonth();
        $endDate = $filters['end_date'] ?? Carbon::now()->endOfMonth();
        
        $meetings = Meeting::whereBetween('start_time', [$startDate, $endDate])
            ->with(['participants.user', 'creator'])
            ->get();
        
        return $meetings->toArray();
    }

    public function getUpcomingMeetings(array $filters = []): array
    {
        $limit = $filters['limit'] ?? 10;
        
        $meetings = Meeting::where('status', 'scheduled')
            ->where('start_time', '>', Carbon::now())
            ->with(['participants.user', 'creator'])
            ->orderBy('start_time', 'asc')
            ->limit($limit)
            ->get();
        
        return $meetings->toArray();
    }

    public function getMyMeetings(array $filters = []): array
    {
        $userId = Auth::id();
        
        $meetings = Meeting::whereHas('participants', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->orWhere('created_by', $userId)
        ->with(['participants.user', 'creator'])
        ->orderBy('start_time', 'desc')
        ->get();
        
        return $meetings->toArray();
    }
} 