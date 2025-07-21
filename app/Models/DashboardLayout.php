<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DashboardLayout extends Model
{
    protected $table = 'dashboard_layouts';
    protected $fillable = [
        'user_id',
        'name',
        'layout_config',
        'is_default',
    ];
} 