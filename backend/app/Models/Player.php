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
        'semester_memberships', // Array of semester membership records
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
        'semester_memberships' => 'array',
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
     * Check if membership is active for current semester
     */
    public function hasActiveMembership(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Check semester-based membership
        if (!empty($this->semester_memberships)) {
            $now = new \DateTime();
            $currentYear = (int)$now->format('Y');
            $currentMonth = (int)$now->format('n');
            
            // Determine current semester: 1-6 = Semester 1, 7-12 = Semester 2
            $currentSemester = ($currentMonth >= 1 && $currentMonth <= 6) ? 'Semester 1' : 'Semester 2';
            
            // Check if user has active membership for current semester
            foreach ($this->semester_memberships as $membership) {
                if ($membership['semester'] === $currentSemester && 
                    $membership['year'] === $currentYear && 
                    $membership['status'] === 'active') {
                    return true;
                }
            }
        }
        
        // Fallback to old membership system if no semester memberships
        if ($this->membership_end_date) {
            return $this->membership_end_date->isFuture();
        }
        
        return false;
    }
}
