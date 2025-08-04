<?php

namespace App\Services;

use App\Models\SalaryComponent;
use Illuminate\Support\Facades\DB;

class SalaryComponentService
{
    public function getAllSalaryComponents(array $filters = [])
    {
        $query = SalaryComponent::query();

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->ordered()->paginate(15);
    }

    public function getSalaryComponentById(int $id)
    {
        if ($id <= 0) {
            throw new \Exception('Invalid salary component ID');
        }
        
        return SalaryComponent::findOrFail($id);
    }

    public function createSalaryComponent(array $data)
    {
        return SalaryComponent::create($data);
    }

    public function updateSalaryComponent(int $id, array $data)
    {
        if ($id <= 0) {
            throw new \Exception('Invalid salary component ID');
        }
        
        $component = SalaryComponent::findOrFail($id);
        $component->update($data);
        return $component;
    }

    public function deleteSalaryComponent(int $id)
    {
        if ($id <= 0) {
            throw new \Exception('Invalid salary component ID');
        }
        
        $component = SalaryComponent::findOrFail($id);

        // Check if component is being used in payroll items
        if ($component->payrollItems()->exists()) {
            throw new \Exception('Cannot delete salary component that is being used in payrolls');
        }

        return $component->delete();
    }

    public function getByType(string $type)
    {
        return SalaryComponent::active()->where('type', $type)->ordered()->get();
    }

    public function toggleActive(int $id)
    {
        if ($id <= 0) {
            throw new \Exception('Invalid salary component ID');
        }
        
        $component = SalaryComponent::findOrFail($id);
        $component->update(['is_active' => !$component->is_active]);
        return $component;
    }

    public function getActiveComponents()
    {
        return SalaryComponent::active()->ordered()->get();
    }

    public function getComponentsByType(string $type)
    {
        return SalaryComponent::active()->where('type', $type)->ordered()->get();
    }
} 