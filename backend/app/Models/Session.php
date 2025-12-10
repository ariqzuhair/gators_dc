<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Session extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'type', // drop-in, training, tournament, special-event
        'date',
        'start_time',
        'end_time',
        'location',
        'max_participants',
        'current_participants',
        'price',
        'skill_level_required',
        'is_active',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'max_participants' => 'integer',
        'current_participants' => 'integer',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the registrations for the session.
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class, 'session_id');
    }

    /**
     * Get the admin who created the session.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Check if session is full
     */
    public function isFull(): bool
    {
        return $this->current_participants >= $this->max_participants;
    }

    /**
     * Check if registration is still open
     */
    public function isRegistrationOpen(): bool
    {
        return $this->is_active && 
               !$this->isFull() && 
               $this->date->isFuture();
    }
}
