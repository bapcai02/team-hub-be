<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $table = 'tenants';
    protected $fillable = [
        'name',
        'code',
        'database_schema',
        'is_active',
    ];
} 