<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';
    public $timestamps = false;
    protected $fillable = ['role_id', 'user_id'];
} 