<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favorites';
    protected $fillable = [
        'user_id',
        'type',
        'target_id',
        'created_at',
    ];
    public $timestamps = false;
} 