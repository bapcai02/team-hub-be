<?php

namespace App\Interfaces\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Application\Holiday\Services\HolidayService;
use App\Interfaces\Http\Requests\Holiday\StoreHolidayRequest;
use App\Interfaces\Http\Requests\Holiday\UpdateHolidayRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HolidayController
{
    public function __construct(protected HolidayService $holidayService) {}

    /**
     * Get all holidays.
     */
    public function index(Request $request)
    {
        try {
            $filters = $request->all();
            $holidays = $this->holidayService->getAll();
            
            return ApiResponseHelper::responseApi(['holidays' => $holidays], 'holiday_list_success');
        } catch (\Exception $e) {
            Log::error('HolidayController::index - Error getting holidays', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Create a new holiday.
     */
    public function store(StoreHolidayRequest $request)
    {
        try {
            $data = $request->validated();
            $holiday = $this->holidayService->create($data);
            
            return ApiResponseHelper::responseApi(['holiday' => $holiday], 'holiday_create_success', 201);
        } catch (\Exception $e) {
            Log::error('HolidayController::store - Error creating holiday', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get holiday details by ID.
     */
    public function show($id)
    {
        try {
            $holiday = $this->holidayService->getById($id);
            if (!$holiday) {
                return ApiResponseHelper::responseApi([], 'holiday_not_found', 404);
            }
            
            return ApiResponseHelper::responseApi(['holiday' => $holiday], 'holiday_get_success');
        } catch (\Exception $e) {
            Log::error('HolidayController::show - Error getting holiday details', [
                'error' => $e->getMessage(),
                'holiday_id' => $id
            ]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Update holiday.
     */
    public function update(UpdateHolidayRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $holiday = $this->holidayService->update($id, $data);
            
            if (!$holiday) {
                return ApiResponseHelper::responseApi([], 'holiday_not_found', 404);
            }
            
            return ApiResponseHelper::responseApi(['holiday' => $holiday], 'holiday_update_success');
        } catch (\Exception $e) {
            Log::error('HolidayController::update - Error updating holiday', [
                'error' => $e->getMessage(),
                'holiday_id' => $id
            ]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Delete holiday.
     */
    public function destroy($id)
    {
        try {
            $success = $this->holidayService->delete($id);
            if (!$success) {
                return ApiResponseHelper::responseApi([], 'holiday_not_found', 404);
            }
            
            return ApiResponseHelper::responseApi([], 'holiday_delete_success');
        } catch (\Exception $e) {
            Log::error('HolidayController::destroy - Error deleting holiday', [
                'error' => $e->getMessage(),
                'holiday_id' => $id
            ]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get holidays by year.
     */
    public function getByYear(Request $request)
    {
        try {
            $year = $request->get('year', date('Y'));
            $holidays = $this->holidayService->getByYear($year);
            return ApiResponseHelper::responseApi(['holidays' => $holidays], 'holiday_year_success');
        } catch (\Exception $e) {
            Log::error('HolidayController::getByYear - Error getting holidays by year', [
                'error' => $e->getMessage(),
                'year' => $request->get('year')
            ]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get holidays by type.
     */
    public function getByType(Request $request)
    {
        try {
            $type = $request->get('type', 'national');
            $holidays = $this->holidayService->getByType($type);
            return ApiResponseHelper::responseApi(['holidays' => $holidays], 'holiday_type_success');
        } catch (\Exception $e) {
            Log::error('HolidayController::getByType - Error getting holidays by type', [
                'error' => $e->getMessage(),
                'type' => $request->get('type')
            ]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get active holidays.
     */
    public function getActive(Request $request)
    {
        try {
            $holidays = $this->holidayService->getActiveHolidays();
            return ApiResponseHelper::responseApi(['holidays' => $holidays], 'holiday_active_success');
        } catch (\Exception $e) {
            Log::error('HolidayController::getActive - Error getting active holidays', [
                'error' => $e->getMessage()
            ]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Get upcoming holidays.
     */
    public function getUpcoming(Request $request)
    {
        try {
            $days = $request->get('days', 30);
            $holidays = $this->holidayService->getUpcomingHolidays($days);
            return ApiResponseHelper::responseApi(['holidays' => $holidays], 'holiday_upcoming_success');
        } catch (\Exception $e) {
            Log::error('HolidayController::getUpcoming - Error getting upcoming holidays', [
                'error' => $e->getMessage(),
                'days' => $request->get('days')
            ]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }

    /**
     * Check if a date is a holiday.
     */
    public function checkHoliday(Request $request)
    {
        try {
            $date = $request->get('date', now()->format('Y-m-d'));
            $isHoliday = $this->holidayService->isHoliday($date);
            $holiday = $this->holidayService->getHolidayByDate($date);
            
            return ApiResponseHelper::responseApi([
                'is_holiday' => $isHoliday,
                'holiday' => $holiday
            ], 'holiday_check_success');
        } catch (\Exception $e) {
            Log::error('HolidayController::checkHoliday - Error checking holiday', [
                'error' => $e->getMessage(),
                'date' => $request->get('date')
            ]);
            return ApiResponseHelper::responseApi([], 'internal_error', 500);
        }
    }
} 