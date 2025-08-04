<?php

namespace App\Interfaces\Http\Controllers;

use App\Application\Calendar\Services\CalendarService;
use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    protected $calendarService;

    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * Get calendar events for a specific date range
     */
    public function getEvents(Request $request): JsonResponse
    {
        try {
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $userId = Auth::id();

            $events = $this->calendarService->getEvents($userId, $startDate, $endDate);

            return ApiResponseHelper::success('calendar_events_retrieved', $events);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('Error retrieving calendar events: ' . $e->getMessage());
        }
    }

    /**
     * Create a new calendar event
     */
    public function createEvent(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'event_type' => 'required|in:meeting,task,reminder,other',
                'color' => 'nullable|string|max:7',
                'is_all_day' => 'boolean',
                'location' => 'nullable|string|max:255',
                'participant_ids' => 'nullable|array',
                'participant_ids.*' => 'integer|exists:users,id',
            ]);

            $userId = Auth::id();
            $event = $this->calendarService->createEvent($userId, $validated);

            return ApiResponseHelper::success('calendar_event_created', $event);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('Error creating calendar event: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing calendar event
     */
    public function updateEvent(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|required|date',
                'end_time' => 'sometimes|required|date|after:start_time',
                'event_type' => 'sometimes|required|in:meeting,task,reminder,other',
                'color' => 'nullable|string|max:7',
                'is_all_day' => 'boolean',
                'location' => 'nullable|string|max:255',
                'participant_ids' => 'nullable|array',
                'participant_ids.*' => 'integer|exists:users,id',
            ]);

            $userId = $request->user()->id;
            $event = $this->calendarService->updateEvent($id, $userId, $validated);

            return ApiResponseHelper::success('calendar_event_updated', $event);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('Error updating calendar event: ' . $e->getMessage());
        }
    }

    /**
     * Delete a calendar event
     */
    public function deleteEvent(int $id): JsonResponse
    {
        try {
            $userId = auth()->user()->id;
            $this->calendarService->deleteEvent($id, $userId);

            return ApiResponseHelper::success('calendar_event_deleted');
        } catch (\Exception $e) {
            return ApiResponseHelper::error('Error deleting calendar event: ' . $e->getMessage());
        }
    }

    /**
     * Get calendar statistics
     */
    public function getStats(): JsonResponse
    {
        try {
            $userId = auth()->id();
            $stats = $this->calendarService->getStats($userId);

            return ApiResponseHelper::success('calendar_stats_retrieved', $stats);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('Error retrieving calendar statistics: ' . $e->getMessage());
        }
    }

    /**
     * Get upcoming events
     */
    public function getUpcomingEvents(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $userId = auth()->id();

            $events = $this->calendarService->getUpcomingEvents($userId, $limit);

            return ApiResponseHelper::success('upcoming_events_retrieved', $events);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('Error retrieving upcoming events: ' . $e->getMessage());
        }
    }

    /**
     * Get events for today
     */
    public function getTodayEvents(): JsonResponse
    {
        try {
            $userId = auth()->id();
            $events = $this->calendarService->getTodayEvents($userId);

            return ApiResponseHelper::success('today_events_retrieved', $events);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('Error retrieving today\'s events: ' . $e->getMessage());
        }
    }

    /**
     * Get events by type
     */
    public function getEventsByType(Request $request): JsonResponse
    {
        try {
            $eventType = $request->get('event_type');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $userId = auth()->id();

            $events = $this->calendarService->getEventsByType($userId, $eventType, $startDate, $endDate);

            return ApiResponseHelper::success('events_by_type_retrieved', $events);
        } catch (\Exception $e) {
            return ApiResponseHelper::error('Error retrieving events by type: ' . $e->getMessage());
        }
    }
} 