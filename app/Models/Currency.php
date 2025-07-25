<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'is_default',
    ];
} 