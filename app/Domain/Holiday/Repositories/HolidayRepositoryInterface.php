<?php

namespace App\Domain\Holiday\Repositories;

use App\Models\Holiday;
use Illuminate\Database\Eloquent\Collection;

interface HolidayRepositoryInterface
{
    public function getAll(): Collection;
    public function getById(int $id): ?Holiday;
    public function create(array $data): Holiday;
    public function update(int $id, array $data): ?Holiday;
    public function delete(int $id): bool;
    public function getByYear(int $year): Collection;
    public function getByType(string $type): Collection;
    public function getActiveHolidays(): Collection;
    public function getHolidaysByDateRange(string $startDate, string $endDate): Collection;
    public function isHoliday(string $date): bool;
    public function getHolidayByDate(string $date): ?Holiday;
} 