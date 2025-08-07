<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\Guest\Services\GuestService;
use App\Interfaces\Http\Requests\Guest\StoreGuestRequest;
use App\Interfaces\Http\Requests\Guest\UpdateGuestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GuestController
{
    public function __construct(protected GuestService $guestService) {}

    /**
     * Get all guests.
     */
    public function index()
    {
        try {
            $guests = $this->guestService->getAll();
            return ApiResponseHelper::responseApi(['guests' => $guests], 'guest_list_success');
        } catch (\Throwable $e) {
            Log::error('GuestController::index - Error getting all guests', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Create a new guest.
     */
    public function store(StoreGuestRequest $request)
    {
        try {
            $data = $request->validated();
            $guest = $this->guestService->create($data);
            return ApiResponseHelper::responseApi(['guest' => $guest], 'guest_create_success', 201);
        } catch (\Throwable $e) {
            Log::error('GuestController::store - Error creating guest', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get guest details by ID.
     */
    public function show($id)
    {
        try {
            $guest = $this->guestService->getById($id);
            if (!$guest) {
                return ApiResponseHelper::responseApi([], 'guest_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['guest' => $guest], 'guest_get_success');
        } catch (\Throwable $e) {
            Log::error('GuestController::show - Error getting guest details', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Update guest details.
     */
    public function update(UpdateGuestRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $guest = $this->guestService->update($id, $data);
            
            if (!$guest) {
                return ApiResponseHelper::responseApi([], 'guest_not_found', 404);
            }
            return ApiResponseHelper::responseApi(['guest' => $guest], 'guest_update_success');
        } catch (\Throwable $e) {
            Log::error('GuestController::update - Error updating guest', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Delete guest.
     */
    public function destroy($id)
    {
        try {
            $success = $this->guestService->delete($id);
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'guest_not_found', 404);
            }
            return ApiResponseHelper::responseApi([], 'guest_delete_success');
        } catch (\Throwable $e) {
            Log::error('GuestController::destroy - Error deleting guest', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get guests by type.
     */
    public function getByType(Request $request)
    {
        try {
            $type = $request->query('type');
            if (!$type) {
                return ApiResponseHelper::responseApi([], 'type_required', 400);
            }
            
            $guests = $this->guestService->getByType($type);
            return ApiResponseHelper::responseApi(['guests' => $guests], 'guest_type_success');
        } catch (\Throwable $e) {
            Log::error('GuestController::getByType - Error getting guests by type', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get guests by status.
     */
    public function getByStatus(Request $request)
    {
        try {
            $status = $request->query('status');
            if (!$status) {
                return ApiResponseHelper::responseApi([], 'status_required', 400);
            }
            
            $guests = $this->guestService->getByStatus($status);
            return ApiResponseHelper::responseApi(['guests' => $guests], 'guest_status_success');
        } catch (\Throwable $e) {
            Log::error('GuestController::getByStatus - Error getting guests by status', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Search guests.
     */
    public function search(Request $request)
    {
        try {
            $query = $request->query('q');
            if (!$query) {
                return ApiResponseHelper::responseApi([], 'query_required', 400);
            }
            
            $guests = $this->guestService->search($query);
            return ApiResponseHelper::responseApi(['guests' => $guests], 'guest_search_success');
        } catch (\Throwable $e) {
            Log::error('GuestController::search - Error searching guests', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get active guests.
     */
    public function getActiveGuests()
    {
        try {
            $guests = $this->guestService->getActiveGuests();
            return ApiResponseHelper::responseApi(['guests' => $guests], 'guest_active_success');
        } catch (\Throwable $e) {
            Log::error('GuestController::getActiveGuests - Error getting active guests', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get recent visits.
     */
    public function getRecentVisits(Request $request)
    {
        try {
            $limit = $request->query('limit', 10);
            $visits = $this->guestService->getRecentVisits($limit);
            return ApiResponseHelper::responseApi(['visits' => $visits], 'guest_visits_success');
        } catch (\Throwable $e) {
            Log::error('GuestController::getRecentVisits - Error getting recent visits', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
} 