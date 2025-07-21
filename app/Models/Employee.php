<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
    protected $table = 'employees';
    protected $fillable = [
        'user_id',
        'department_id',
        'position',
        'salary',
        'contract_type',
        'hire_date',
        'dob',
        'gender',
        'phone',
        'address',
    ];
} 