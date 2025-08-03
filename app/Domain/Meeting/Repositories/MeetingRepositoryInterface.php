<?php

namespace App\Domain\Meeting\Repositories;

use App\Models\Meeting;

interface MeetingRepositoryInterface
{
    public function getAllMeetings(array $filters = []): array;
    public function findById(int $id): ?Meeting;
    public function create(array $data): Meeting;
    public function update(int $id, array $data): ?Meeting;
    public function delete(int $id): bool;
} 