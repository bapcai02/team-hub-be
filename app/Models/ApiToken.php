<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiToken extends Model
{
    use SoftDeletes;
    protected $table = 'api_tokens';
    protected $fillable = [
        'user_id',
        'token',
        'last_used_at',
    ];
} 