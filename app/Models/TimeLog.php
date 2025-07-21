<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    protected $table = 'time_logs';
    protected $fillable = [
        'employee_id',
        'check_in',
        'check_out',
        'date',
    ];
} 