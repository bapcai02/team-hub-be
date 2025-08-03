<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'category_id',
        'model',
        'serial_number',
        'status',
        'assigned_to',
        'location',
        'department',
        'purchase_date',
        'warranty_expiry',
        'specifications',
        'notes',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'specifications' => 'array',
    ];

    /**
     * Get the user assigned to this device.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the category of this device.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(DeviceCategory::class, 'category_id');
    }

    /**
     * Get the device history.
     */
    public function history(): HasMany
    {
        return $this->hasMany(DeviceHistory::class);
    }

    /**
     * Scope for available devices.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope for devices in use.
     */
    public function scopeInUse($query)
    {
        return $query->where('status', 'in_use');
    }

    /**
     * Scope for devices in maintenance.
     */
    public function scopeInMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    /**
     * Scope for retired devices.
     */
    public function scopeRetired($query)
    {
        return $query->where('status', 'retired');
    }

    /**
     * Scope for devices by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for devices by department.
     */
    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    /**
     * Check if device is available.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Check if device is assigned.
     */
    public function isAssigned(): bool
    {
        return !is_null($this->assigned_to);
    }

    /**
     * Check if device warranty is expired.
     */
    public function isWarrantyExpired(): bool
    {
        return $this->warranty_expiry->isPast();
    }

    /**
     * Get days until warranty expires.
     */
    public function daysUntilWarrantyExpires(): int
    {
        return $this->warranty_expiry->diffInDays(now());
    }
} 