<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'guests';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'position',
        'address',
        'type',
        'status',
        'notes',
        'avatar',
        'first_visit_date',
        'last_visit_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'first_visit_date' => 'date',
        'last_visit_date' => 'date',
    ];

    public function visits()
    {
        return $this->hasMany(GuestVisit::class);
    }

    public function documents()
    {
        return $this->hasMany(GuestDocument::class);
    }

    public function contacts()
    {
        return $this->hasMany(GuestContact::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function primaryContact()
    {
        return $this->hasOne(GuestContact::class)->where('is_primary', true);
    }
} 