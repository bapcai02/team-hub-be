<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollItem extends Model
{
    protected $fillable = [
        'payroll_id',
        'salary_component_id',
        'component_name',
        'type',
        'amount',
        'rate',
        'quantity',
        'description',
        'is_taxable',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'rate' => 'decimal:2',
        'quantity' => 'decimal:2',
        'is_taxable' => 'boolean',
    ];

    // Relationships
    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function salaryComponent()
    {
        return $this->belongsTo(SalaryComponent::class);
    }
}
