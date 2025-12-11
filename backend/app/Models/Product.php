<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'category',
        'price',
        'original_price',
        'image_url',
        'sizes',
        'badge',
        'in_stock',
        'is_featured',
        'created_by',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'in_stock' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
        'sizes' => 'array',
    ];

    /**
     * Get the user who created the product.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get only featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope to get only in-stock products
     */
    public function scopeInStock($query)
    {
        return $query->where('in_stock', true);
    }
}
