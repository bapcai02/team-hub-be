<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $table = 'rewards';
    protected $fillable = [
        'employee_id',
        'title',
        'reason',
        'amount',
        'date_awarded',
    ];
} 