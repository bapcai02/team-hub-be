<?php

namespace App\Application\Holiday\Services;

use App\Domain\Holiday\Repositories\HolidayRepositoryInterface;
use App\Models\Holiday;
use Illuminate\Database\Eloquent\Collection;

class HolidayService
{
    public function __construct(protected HolidayRepositoryInterface $holidayRepository) {}

    public function getAll(): Collection
    {
        return $this->holidayRepository->getAll();
    }

    public function getById(int $id): ?Holiday
    {
        return $this->holidayRepository->getById($id);
    }

    public function create(array $data): Holiday
    {
        return $this->holidayRepository->create($data);
    }

    public function update(int $id, array $data): ?Holiday
    {
        return $this->holidayRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->holidayRepository->delete($id);
    }

    public function getByYear(int $year): Collection
    {
        return $this->holidayRepository->getByYear($year);
    }

    public function getByType(string $type): Collection
    {
        return $this->holidayRepository->getByType($type);
    }

    public function getActiveHolidays(): Collection
    {
        return $this->holidayRepository->getActiveHolidays();
    }

    public function getHolidaysByDateRange(string $startDate, string $endDate): Collection
    {
        return $this->holidayRepository->getHolidaysByDateRange($startDate, $endDate);
    }

    public function isHoliday(string $date): bool
    {
        return $this->holidayRepository->isHoliday($date);
    }

    public function getHolidayByDate(string $date): ?Holiday
    {
        return $this->holidayRepository->getHolidayByDate($date);
    }

    public function getUpcomingHolidays(int $days = 30): Collection
    {
        $startDate = now()->format('Y-m-d');
        $endDate = now()->addDays($days)->format('Y-m-d');
        
        return $this->holidayRepository->getHolidaysByDateRange($startDate, $endDate);
    }

    public function getHolidaysForMonth(int $year, int $month): Collection
    {
        $startDate = "{$year}-{$month}-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        
        return $this->holidayRepository->getHolidaysByDateRange($startDate, $endDate);
    }
} 