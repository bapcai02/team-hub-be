<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSkill extends Model
{
    protected $table = 'employee_skills';
    protected $fillable = [
        'employee_id',
        'skill_id',
        'level',
    ];
} 