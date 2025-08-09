<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractParty extends Model
{
    protected $fillable = [
        'contract_id',
        'party_type',
        'name',
        'email',
        'phone',
        'address',
        'company_name',
        'tax_number',
        'representative_name',
        'representative_title',
        'is_primary',
        'additional_info',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'additional_info' => 'array',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(BusinessContract::class, 'contract_id');
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    public function scopeByType($query, $partyType)
    {
        return $query->where('party_type', $partyType);
    }
} 