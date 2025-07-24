<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    protected $table = 'integrations';
    protected $fillable = [
        'name',
        'type',
        'config',
        'status',
        'created_by',
    ];
} 