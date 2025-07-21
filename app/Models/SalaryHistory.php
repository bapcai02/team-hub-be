<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryHistory extends Model
{
    protected $table = 'salary_histories';
    protected $fillable = [
        'employee_id',
        'effective_date',
        'old_salary',
        'new_salary',
        'reason',
        'created_by',
    ];
} 