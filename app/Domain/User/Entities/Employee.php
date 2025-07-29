<?php

namespace App\Domain\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'position',
        'salary',
        'contract_type',
        'hire_date',
        'dob',
        'gender',
        'phone',
        'address',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'dob' => 'date',
        'salary' => 'decimal:2',
    ];

    /**
     * Get the user associated with the employee.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Get the department of this employee.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Department::class);
    }

    /**
     * Get the time logs for this employee.
     */
    public function timeLogs(): HasMany
    {
        return $this->hasMany(\App\Models\TimeLog::class);
    }

    /**
     * Get the leave requests for this employee.
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(\App\Models\Leave::class);
    }

    /**
     * Get the payroll records for this employee.
     */
    public function payrolls(): HasMany
    {
        return $this->hasMany(\App\Models\Payroll::class);
    }

    /**
     * Get the contracts for this employee.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(\App\Models\Contract::class);
    }

    /**
     * Get the salary histories for this employee.
     */
    public function salaryHistories(): HasMany
    {
        return $this->hasMany(\App\Models\SalaryHistory::class);
    }

    /**
     * Get the evaluations for this employee.
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(\App\Models\EmployeeEvaluation::class);
    }

    /**
     * Get the skills for this employee.
     */
    public function skills(): HasMany
    {
        return $this->hasMany(\App\Models\EmployeeSkill::class);
    }

    /**
     * Get the full name of the employee.
     */
    public function getFullNameAttribute(): string
    {
        return $this->user ? $this->user->name : 'Unknown';
    }

    /**
     * Get the employee's age.
     */
    public function getAgeAttribute(): int
    {
        return $this->dob ? $this->dob->age : 0;
    }

    /**
     * Get the employee's years of service.
     */
    public function getYearsOfServiceAttribute(): int
    {
        return $this->hire_date ? $this->hire_date->diffInYears(now()) : 0;
    }

    /**
     * Check if employee is active.
     */
    public function isActive(): bool
    {
        return $this->user && $this->user->status === 'active';
    }

    /**
     * Check if employee is terminated.
     */
    public function isTerminated(): bool
    {
        return $this->user && $this->user->status === 'suspended';
    }
} 