<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;
    protected $table = 'projects';
    protected $fillable = [
        'name',
        'description',
        'owner_id',
        'start_date',
        'end_date',
        'status',
        'total_amount',
        'document',
    ];
} 