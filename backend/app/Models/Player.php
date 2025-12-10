<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Player extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'players';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'membership_type', // regular, premium, trial
        'membership_start_date',
        'membership_end_date',
        'skill_level', // beginner, intermediate, advanced
        'emergency_contact_name',
        'emergency_contact_phone',
        'medical_conditions',
        'profile_image',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'membership_start_date' => 'datetime',
        'membership_end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the player.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the registrations for the player.
     */
    public function registrations()
    {
        return $this->hasMany(Registration::class, 'player_id');
    }

    /**
     * Check if membership is active
     */
    public function hasActiveMembership(): bool
    {
        return $this->is_active && 
               $this->membership_end_date && 
               $this->membership_end_date->isFuture();
    }
}
