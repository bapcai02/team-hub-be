<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $table = 'trainings';
    protected $fillable = [
        'title',
        'description',
        'trainer_id',
        'start_date',
        'end_date',
        'location',
    ];
} 