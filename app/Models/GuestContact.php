<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestContact extends Model
{
    use HasFactory;

    protected $table = 'guest_contacts';

    protected $fillable = [
        'guest_id',
        'contact_name',
        'contact_email',
        'contact_phone',
        'contact_position',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
} 