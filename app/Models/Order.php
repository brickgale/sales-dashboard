<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Add any additional fields that might be in your orders table
        // For now, only timestamps are in the migration
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the order details for the order.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Get the total amount for this order.
     */
    public function getTotalAmountAttribute(): float
    {
        return $this->orderDetails()->with('pizza')->get()
            ->sum(function ($detail) {
                return $detail->quantity * $detail->pizza->price;
            });
    }

    /**
     * Get the total quantity of items in this order.
     */
    public function getTotalQuantityAttribute(): int
    {
        return $this->orderDetails()->sum('quantity');
    }
}
