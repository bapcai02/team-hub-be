<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SalaryComponent extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'calculation_type',
        'amount',
        'percentage',
        'formula',
        'is_taxable',
        'is_active',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'percentage' => 'decimal:2',
        'is_taxable' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function payrollItems()
    {
        return $this->hasMany(PayrollItem::class);
    }

    // Scopes
    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    public function scopeByType(Builder $query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeTaxable(Builder $query)
    {
        return $query->where('is_taxable', true);
    }

    public function scopeNonTaxable(Builder $query)
    {
        return $query->where('is_taxable', false);
    }
}
