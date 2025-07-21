<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $table = 'payrolls';
    protected $fillable = [
        'employee_id',
        'month',
        'base_salary',
        'allowance',
        'deduction',
        'net_salary',
        'status',
        'generated_at',
    ];
} 