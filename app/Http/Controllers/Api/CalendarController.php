<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use App\Domain\Calendar\Repositories\CalendarEventRepositoryInterface;
use App\Domain\Calendar\Entities\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CalendarController extends Controller
{
    public function __construct(
        private CalendarEventRepositoryInterface $calendarEventRepository
    ) {}

    /**
     * Get all calendar events
     */
    public function index(Request $request): JsonResponse
    {
        $userId = Auth::id();
        
        $query = $this->calendarEventRepository->getByDateRange(
            $userId,
            now()->subMonths(6),
            now()->addMonths(6)
        );

        return response()->json([
            'success' => true,
            'data' => $query
        ]);
    }

    /**
     * Get a specific calendar event
     */
    public function show(int $id): JsonResponse
    {
        $event = $this->calendarEventRepository->findById($id);
        
        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    /**
     * Create a new calendar event
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'event_type' => 'required|in:meeting,task,reminder,other',
            'color' => 'nullable|string|max:7',
            'is_all_day' => 'boolean',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['user_id'] = Auth::id();

        $event = $this->calendarEventRepository->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'data' => $event
        ], 201);
    }

    /**
     * Update a calendar event
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $event = $this->calendarEventRepository->findById($id);
        
        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        if ($event->userId !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|required|date|after:start_time',
            'event_type' => 'sometimes|required|in:meeting,task,reminder,other',
            'color' => 'nullable|string|max:7',
            'is_all_day' => 'boolean',
            'location' => 'nullable|string|max:255',
            'status' => 'sometimes|required|in:scheduled,ongoing,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $event = $this->calendarEventRepository->update($id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'data' => $event
        ]);
    }

    /**
     * Delete a calendar event
     */
    public function destroy(int $id): JsonResponse
    {
        $event = $this->calendarEventRepository->findById($id);
        
        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        if ($event->userId !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $this->calendarEventRepository->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully'
        ]);
    }

    /**
     * Get upcoming events
     */
    public function upcoming(): JsonResponse
    {
        $userId = Auth::id();
        $events = $this->calendarEventRepository->getUpcoming($userId, 10);

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get events by type
     */
    public function byType(string $type): JsonResponse
    {
        $userId = Auth::id();
        $events = $this->calendarEventRepository->getByTypeAndDateRange(
            $userId,
            $type,
            now()->subMonths(1),
            now()->addMonths(1)
        );

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get events by date range
     */
    public function byDateRange(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $userId = Auth::id();
        $data = $validator->validated();
        
        $events = $this->calendarEventRepository->getByDateRange(
            $userId,
            $data['start_date'],
            $data['end_date']
        );

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Search events
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $userId = Auth::id();
        $query = $request->get('q');
        
        // For now, return all events and filter in frontend
        // In production, you'd implement proper search
        $events = $this->calendarEventRepository->getByDateRange(
            $userId,
            now()->subMonths(6),
            now()->addMonths(6)
        );

        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }
} 