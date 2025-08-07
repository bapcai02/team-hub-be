<?php

namespace App\Application\Guest\Services;

use App\Domain\Guest\Repositories\GuestRepositoryInterface;
use App\Models\Guest;
use Illuminate\Support\Facades\Auth;

class GuestService
{
    public function __construct(protected GuestRepositoryInterface $guestRepository) {}

    public function getAll()
    {
        return $this->guestRepository->all();
    }

    public function getById($id)
    {
        return $this->guestRepository->find($id);
    }

    public function create(array $data): Guest
    {
        $data['created_by'] = Auth::id();
        return $this->guestRepository->create($data);
    }

    public function update($id, array $data): ?Guest
    {
        $data['updated_by'] = Auth::id();
        return $this->guestRepository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->guestRepository->delete($id);
    }

    public function getByType($type): array
    {
        return $this->guestRepository->getByType($type);
    }

    public function getByStatus($status): array
    {
        return $this->guestRepository->getByStatus($status);
    }

    public function search($query): array
    {
        return $this->guestRepository->search($query);
    }

    public function getActiveGuests(): array
    {
        return $this->guestRepository->getActiveGuests();
    }

    public function getRecentVisits($limit = 10): array
    {
        return $this->guestRepository->getRecentVisits($limit);
    }

    public function updateLastVisit($id): bool
    {
        return $this->guestRepository->updateLastVisit($id);
    }
} 