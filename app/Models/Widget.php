<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $table = 'widgets';
    protected $fillable = [
        'user_id',
        'dashboard_id',
        'type',
        'config',
        'order',
    ];
} 