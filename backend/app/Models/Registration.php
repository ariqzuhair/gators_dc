<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Registration extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'registrations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'player_id',
        'session_id',
        'status', // registered, cancelled, completed, no-show
        'payment_status', // pending, paid, refunded
        'payment_amount',
        'registration_date',
        'cancellation_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'registration_date' => 'datetime',
        'cancellation_date' => 'datetime',
        'payment_amount' => 'decimal:2',
    ];

    /**
     * Get the player that owns the registration.
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    /**
     * Get the session that owns the registration.
     */
    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    /**
     * Check if registration can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return $this->status === 'registered' && 
               $this->session && 
               $this->session->date->isFuture();
    }
}
