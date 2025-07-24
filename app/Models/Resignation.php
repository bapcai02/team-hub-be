<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resignation extends Model
{
    protected $table = 'resignations';
    protected $fillable = [
        'employee_id',
        'reason',
        'resignation_date',
        'last_working_day',
        'status',
    ];
} 