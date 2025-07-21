<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    protected $fillable = [
        'user_id',
        'action',
        'target_table',
        'target_id',
        'data',
        'ip_address',
        'user_agent',
        'created_at',
    ];
    public $timestamps = false;
} 