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

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'employee_skills', 'employee_id', 'skill_id')->withPivot('level');
    }
} 