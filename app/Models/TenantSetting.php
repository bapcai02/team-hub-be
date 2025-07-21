<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantSetting extends Model
{
    protected $table = 'tenant_settings';
    protected $fillable = [
        'tenant_id',
        'key',
        'value',
        'type',
        'updated_at',
    ];
    public $timestamps = false;
} 