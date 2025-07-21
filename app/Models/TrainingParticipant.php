<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingParticipant extends Model
{
    protected $table = 'training_participants';
    protected $fillable = [
        'training_id',
        'employee_id',
        'status',
        'score',
    ];
} 