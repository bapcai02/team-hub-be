<?php

namespace App\Infrastructure\Persistence\User\Repositories;

use App\Domain\User\Repositories\LeaveRepositoryInterface;
use App\Models\Leave;
use Illuminate\Support\Facades\DB;

class LeaveRepository implements LeaveRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function all(): array
    {
        return Leave::all()->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return Leave::find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        return Leave::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        $leave = Leave::find($id);
        if ($leave) {
            $leave->update($data);
        }
        return $leave;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id): bool
    {
        $leave = Leave::find($id);
        if ($leave) {
            return $leave->delete();
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function findByEmployeeId($employeeId): array
    {
        return Leave::where('employee_id', $employeeId)->get()->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getAllLeaves($status = null, $departmentId = null): array
    {
        $query = Leave::query();
        if ($status) {
            $query->where('status', $status);
        }
        if ($departmentId) {
            $query->whereHas('employee', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }
        return $query->get()->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function approve($id, array $data)
    {
        $leave = Leave::find($id);
        if ($leave) {
            $leave->update($data);
        }
        return $leave;
    }

    /**
     * {@inheritdoc}
     */
    public function getLeaveBalance($employeeId)
    {
        // Example: count approved leaves for the employee
        return Leave::where('employee_id', $employeeId)
            ->where('status', 'approved')
            ->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getLeaveCalendar($month, $year, $departmentId = null): array
    {
        $query = Leave::query();
        $query->whereMonth('start_date', $month)->whereYear('start_date', $year);
        if ($departmentId) {
            $query->whereHas('employee', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }
        return $query->get()->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getLeaveStats(): array
    {
        $totalLeaves = Leave::count();
        $pendingLeaves = Leave::where('status', 'pending')->count();
        $approvedLeaves = Leave::where('status', 'approved')->count();
        $rejectedLeaves = Leave::where('status', 'rejected')->count();
        $cancelledLeaves = Leave::where('status', 'cancelled')->count();

        // Department stats
        $departmentStats = Leave::join('employees', 'leaves.employee_id', '=', 'employees.id')
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->select('departments.name as department', DB::raw('count(*) as count'))
            ->groupBy('departments.name')
            ->get();

        // Leave type stats
        $typeStats = Leave::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get();

        return [
            'total' => $totalLeaves,
            'pending' => $pendingLeaves,
            'approved' => $approvedLeaves,
            'rejected' => $rejectedLeaves,
            'cancelled' => $cancelledLeaves,
            'departments' => $departmentStats,
            'types' => $typeStats,
        ];
    }
} 