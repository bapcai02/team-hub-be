<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'employee_id',
        'pay_period_start',
        'pay_period_end',
        'basic_salary',
        'gross_salary',
        'net_salary',
        'total_allowances',
        'total_deductions',
        'overtime_pay',
        'bonus',
        'tax_amount',
        'insurance_amount',
        'working_days',
        'overtime_hours',
        'status',
        'payment_date',
        'notes',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'pay_period_start' => 'date',
        'pay_period_end' => 'date',
        'payment_date' => 'date',
        'approved_at' => 'datetime',
        'basic_salary' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'total_allowances' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'bonus' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'insurance_amount' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->where('pay_period_start', '>=', $startDate)
                    ->where('pay_period_end', '<=', $endDate);
    }

    public function scopeForEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'default',
            'approved' => 'processing',
            'paid' => 'success',
            'cancelled' => 'error',
            default => 'default',
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'approved' => 'Approved',
            'paid' => 'Paid',
            'cancelled' => 'Cancelled',
            default => 'Unknown',
        };
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'draft';
    }

    public function canBePaid(): bool
    {
        return $this->status === 'approved';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['draft', 'approved']);
    }
} 