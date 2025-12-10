<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Semester extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'semesters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', // e.g., "Semester 1"
        'year',
        'start_date',
        'end_date',
        'status', // upcoming, active, ended
        'is_active',
        'renewal_deadline',
        'membership_price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'renewal_deadline' => 'datetime',
        'is_active' => 'boolean',
        'membership_price' => 'decimal:2',
    ];
}
