<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractSignature extends Model
{
    protected $fillable = [
        'contract_id',
        'signer_id',
        'signer_name',
        'signer_email',
        'signer_title',
        'signature_type',
        'signature_data',
        'ip_address',
        'user_agent',
        'signed_at',
        'metadata',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(BusinessContract::class, 'contract_id');
    }

    public function signer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signer_id');
    }

    public function scopeByType($query, $signatureType)
    {
        return $query->where('signature_type', $signatureType);
    }

    public function scopeBySigner($query, $signerId)
    {
        return $query->where('signer_id', $signerId);
    }
} 