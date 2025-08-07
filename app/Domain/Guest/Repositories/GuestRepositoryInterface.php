<?php

namespace App\Domain\Guest\Repositories;

use App\Models\Guest;

interface GuestRepositoryInterface
{
    public function create(array $data): Guest;
    public function all();
    public function find($id): ?Guest;
    public function update($id, array $data): ?Guest;
    public function delete($id): bool;
    public function getByType($type): array;
    public function getByStatus($status): array;
    public function search($query): array;
    public function getActiveGuests(): array;
    public function getRecentVisits($limit = 10): array;
    public function updateLastVisit($id): bool;
} 