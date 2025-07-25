<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $table = 'system_settings';
    protected $fillable = [
        'key',
        'value',
        'type',
        'updated_at',
    ];
    public $timestamps = false;
} 