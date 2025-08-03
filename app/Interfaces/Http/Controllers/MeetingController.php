<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Meeting\Services\MeetingService;
use App\Interfaces\Http\Requests\Meeting\CreateMeetingRequest;
use App\Interfaces\Http\Requests\Meeting\UpdateMeetingRequest;
use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MeetingController
{
    public function __construct(protected MeetingService $meetingService) {}

    public function index(Request $request)
    {
        try {
            $meetings = $this->meetingService->getAllMeetings($request->all());
            return ApiResponseHelper::responseApi([
                'meetings' => $meetings,
            ], 'meetings_retrieved');
        } catch (\Throwable $e) {
            Log::error('MeetingController::index - Error retrieving meetings', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function store(CreateMeetingRequest $request)
    {
        try {
            $meeting = $this->meetingService->createMeeting($request->validated());
            return ApiResponseHelper::responseApi([
                'meeting' => $meeting,
            ], 'meeting_created', 201);
        } catch (\Throwable $e) {
            Log::error('MeetingController::store - Error creating meeting', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function show($id)
    {
        try {
            $meeting = $this->meetingService->getMeetingById($id);
            if (!$meeting) {
                return ApiResponseHelper::responseApi([], 'meeting_not_found', 404);
            }
            return ApiResponseHelper::responseApi([
                'meeting' => $meeting,
            ], 'meeting_retrieved');
        } catch (\Throwable $e) {
            Log::error('MeetingController::show - Error retrieving meeting', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function update(UpdateMeetingRequest $request, $id)
    {
        try {
            $meeting = $this->meetingService->updateMeeting($id, $request->validated());
            if (!$meeting) {
                return ApiResponseHelper::responseApi([], 'meeting_not_found', 404);
            }
            return ApiResponseHelper::responseApi([
                'meeting' => $meeting,
            ], 'meeting_updated');
        } catch (\Throwable $e) {
            Log::error('MeetingController::update - Error updating meeting', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function destroy($id)
    {
        try {
            $success = $this->meetingService->deleteMeeting($id);
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'meeting_not_found', 404);
            }
            return ApiResponseHelper::responseApi([], 'meeting_deleted');
        } catch (\Throwable $e) {
            Log::error('MeetingController::destroy - Error deleting meeting', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function addParticipants(Request $request, $id)
    {
        try {
            $participants = $this->meetingService->addParticipants($id, $request->input('user_ids', []));
            return ApiResponseHelper::responseApi([
                'participants' => $participants,
            ], 'participants_added');
        } catch (\Throwable $e) {
            Log::error('MeetingController::addParticipants - Error adding participants', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function removeParticipant($id, $userId)
    {
        try {
            $success = $this->meetingService->removeParticipant($id, $userId);
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'participant_not_found', 404);
            }
            return ApiResponseHelper::responseApi([], 'participant_removed');
        } catch (\Throwable $e) {
            Log::error('MeetingController::removeParticipant - Error removing participant', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function startMeeting($id)
    {
        try {
            $meeting = $this->meetingService->startMeeting($id);
            if (!$meeting) {
                return ApiResponseHelper::responseApi([], 'meeting_not_found', 404);
            }
            return ApiResponseHelper::responseApi([
                'meeting' => $meeting,
            ], 'meeting_started');
        } catch (\Throwable $e) {
            Log::error('MeetingController::startMeeting - Error starting meeting', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function endMeeting($id)
    {
        try {
            $meeting = $this->meetingService->endMeeting($id);
            if (!$meeting) {
                return ApiResponseHelper::responseApi([], 'meeting_not_found', 404);
            }
            return ApiResponseHelper::responseApi([
                'meeting' => $meeting,
            ], 'meeting_ended');
        } catch (\Throwable $e) {
            Log::error('MeetingController::endMeeting - Error ending meeting', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function cancelMeeting($id)
    {
        try {
            $meeting = $this->meetingService->cancelMeeting($id);
            if (!$meeting) {
                return ApiResponseHelper::responseApi([], 'meeting_not_found', 404);
            }
            return ApiResponseHelper::responseApi([
                'meeting' => $meeting,
            ], 'meeting_cancelled');
        } catch (\Throwable $e) {
            Log::error('MeetingController::cancelMeeting - Error cancelling meeting', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function getStats(Request $request)
    {
        try {
            $stats = $this->meetingService->getMeetingStats($request->all());
            return ApiResponseHelper::responseApi([
                'stats' => $stats,
            ], 'stats_retrieved');
        } catch (\Throwable $e) {
            Log::error('MeetingController::getStats - Error retrieving stats', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function getCalendar(Request $request)
    {
        try {
            $calendar = $this->meetingService->getMeetingCalendar($request->all());
            return ApiResponseHelper::responseApi([
                'calendar' => $calendar,
            ], 'calendar_retrieved');
        } catch (\Throwable $e) {
            Log::error('MeetingController::getCalendar - Error retrieving calendar', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function getUpcoming(Request $request)
    {
        try {
            $meetings = $this->meetingService->getUpcomingMeetings($request->all());
            return ApiResponseHelper::responseApi([
                'meetings' => $meetings,
            ], 'upcoming_meetings_retrieved');
        } catch (\Throwable $e) {
            Log::error('MeetingController::getUpcoming - Error retrieving upcoming meetings', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    public function getMyMeetings(Request $request)
    {
        try {
            $meetings = $this->meetingService->getMyMeetings($request->all());
            return ApiResponseHelper::responseApi([
                'meetings' => $meetings,
            ], 'my_meetings_retrieved');
        } catch (\Throwable $e) {
            Log::error('MeetingController::getMyMeetings - Error retrieving my meetings', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
} 