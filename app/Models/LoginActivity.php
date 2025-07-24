<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginActivity extends Model
{
    protected $table = 'login_activities';
    protected $fillable = [
        'user_id',
        'ip_address',
        'device',
        'login_at',
        'logout_at',
    ];
    public $timestamps = false;
} 