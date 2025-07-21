<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    protected $table = 'webhooks';
    protected $fillable = [
        'name',
        'url',
        'event',
        'secret',
        'is_active',
        'created_by',
    ];
} 