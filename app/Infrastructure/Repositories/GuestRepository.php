<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Guest\Repositories\GuestRepositoryInterface;
use App\Models\Guest;
use App\Models\GuestVisit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GuestRepository implements GuestRepositoryInterface
{
    public function create(array $data): Guest
    {
        try {
            return Guest::create($data);
        } catch (\Exception $e) {
            Log::error('GuestRepository::create - Error creating guest', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function all()
    {
        try {
            return Guest::with(['contacts', 'visits' => function($query) {
                $query->latest()->limit(5);
            }])->get();
        } catch (\Exception $e) {
            Log::error('GuestRepository::all - Error getting all guests', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    public function find($id): ?Guest
    {
        try {
            return Guest::with(['contacts', 'visits', 'documents'])->find($id);
        } catch (\Exception $e) {
            Log::error('GuestRepository::find - Error finding guest', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function update($id, array $data): ?Guest
    {
        try {
            $guest = Guest::find($id);
            if ($guest) {
                $guest->update($data);
                return $guest->fresh();
            }
            return null;
        } catch (\Exception $e) {
            Log::error('GuestRepository::update - Error updating guest', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function delete($id): bool
    {
        try {
            $guest = Guest::find($id);
            return $guest ? $guest->delete() : false;
        } catch (\Exception $e) {
            Log::error('GuestRepository::delete - Error deleting guest', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function getByType($type): array
    {
        try {
            return Guest::where('type', $type)
                ->with(['contacts', 'visits' => function($query) {
                    $query->latest()->limit(3);
                }])
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::error('GuestRepository::getByType - Error getting guests by type', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function getByStatus($status): array
    {
        try {
            return Guest::where('status', $status)
                ->with(['contacts', 'visits' => function($query) {
                    $query->latest()->limit(3);
                }])
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::error('GuestRepository::getByStatus - Error getting guests by status', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function search($query): array
    {
        try {
            return Guest::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->orWhere('company', 'like', "%{$query}%")
                ->orWhere('phone', 'like', "%{$query}%")
                ->with(['contacts', 'visits' => function($query) {
                    $query->latest()->limit(3);
                }])
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::error('GuestRepository::search - Error searching guests', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function getActiveGuests(): array
    {
        try {
            return Guest::where('status', 'active')
                ->with(['contacts', 'visits' => function($query) {
                    $query->latest()->limit(3);
                }])
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::error('GuestRepository::getActiveGuests - Error getting active guests', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function getRecentVisits($limit = 10): array
    {
        try {
            return GuestVisit::with(['guest', 'host'])
                ->latest('check_in')
                ->limit($limit)
                ->get()
                ->toArray();
        } catch (\Exception $e) {
            Log::error('GuestRepository::getRecentVisits - Error getting recent visits', ['error' => $e->getMessage()]);
            return [];
        }
    }

    public function updateLastVisit($id): bool
    {
        try {
            $guest = Guest::find($id);
            if ($guest) {
                $guest->update(['last_visit_date' => Carbon::now()]);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('GuestRepository::updateLastVisit - Error updating last visit', ['error' => $e->getMessage()]);
            return false;
        }
    }
} 