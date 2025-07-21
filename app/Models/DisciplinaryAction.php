<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisciplinaryAction extends Model
{
    protected $table = 'disciplinary_actions';
    protected $fillable = [
        'employee_id',
        'type',
        'reason',
        'date',
    ];
} 