<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceHistory extends Model
{
    use HasFactory;

    protected $table = 'device_history';

    protected $fillable = [
        'device_id',
        'action',
        'user_id',
        'details',
    ];

    /**
     * Get the device that owns the history.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
} 