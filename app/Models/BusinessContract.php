<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BusinessContract extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'contract_number',
        'title',
        'description',
        'type',
        'template_id',
        'client_id',
        'employee_id',
        'value',
        'currency',
        'start_date',
        'end_date',
        'status',
        'signature_status',
        'terms',
        'signatures',
        'file_path',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'terms' => 'array',
        'signatures' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'value' => 'decimal:2',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(ContractTemplate::class, 'template_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function parties(): HasMany
    {
        return $this->hasMany(ContractParty::class, 'contract_id');
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(ContractSignature::class, 'contract_id');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeBySignatureStatus($query, $signatureStatus)
    {
        return $query->where('signature_status', $signatureStatus);
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('end_date', '<=', now()->addDays($days))
                    ->where('status', 'active');
    }

    public function generateContractNumber()
    {
        $prefix = 'CON';
        $year = date('Y');
        $month = date('m');
        $count = static::whereYear('created_at', $year)
                      ->whereMonth('created_at', $month)
                      ->count() + 1;
        
        return sprintf('%s-%s%s-%04d', $prefix, $year, $month, $count);
    }
} 