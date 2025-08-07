<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Holiday\Repositories\HolidayRepositoryInterface;
use App\Models\Holiday;
use Illuminate\Database\Eloquent\Collection;

class HolidayRepository implements HolidayRepositoryInterface
{
    public function getAll(): Collection
    {
        return Holiday::all();
    }

    public function getById(int $id): ?Holiday
    {
        return Holiday::find($id);
    }

    public function create(array $data): Holiday
    {
        return Holiday::create($data);
    }

    public function update(int $id, array $data): ?Holiday
    {
        $holiday = Holiday::find($id);
        if ($holiday) {
            $holiday->update($data);
            return $holiday->fresh();
        }
        return null;
    }

    public function delete(int $id): bool
    {
        return Holiday::destroy($id) > 0;
    }

    public function getByYear(int $year): Collection
    {
        return Holiday::whereYear('date', $year)->get();
    }

    public function getByType(string $type): Collection
    {
        return Holiday::where('type', $type)->get();
    }

    public function getActiveHolidays(): Collection
    {
        return Holiday::where('is_active', true)->get();
    }

    public function getHolidaysByDateRange(string $startDate, string $endDate): Collection
    {
        return Holiday::whereBetween('date', [$startDate, $endDate])->get();
    }

    public function isHoliday(string $date): bool
    {
        return Holiday::where('date', $date)
            ->where('is_active', true)
            ->exists();
    }

    public function getHolidayByDate(string $date): ?Holiday
    {
        return Holiday::where('date', $date)
            ->where('is_active', true)
            ->first();
    }
} 