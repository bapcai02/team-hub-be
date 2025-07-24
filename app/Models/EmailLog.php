<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $table = 'email_logs';
    protected $fillable = [
        'to',
        'subject',
        'body',
        'status',
        'sent_at',
        'error_message',
    ];
    public $timestamps = false;
} 