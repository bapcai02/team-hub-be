<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEvaluation extends Model
{
    protected $table = 'employee_evaluations';
    protected $fillable = [
        'employee_id',
        'evaluator_id',
        'period',
        'score',
        'feedback',
    ];
} 