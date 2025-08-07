<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestVisit extends Model
{
    use HasFactory;

    protected $table = 'guest_visits';

    protected $fillable = [
        'guest_id',
        'check_in',
        'check_out',
        'purpose',
        'description',
        'host_id',
        'status',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }
} 