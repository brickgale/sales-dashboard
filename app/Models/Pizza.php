<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pizza extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'slug',
        'pizza_type_id',
        'size',
        'price',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the pizza type that owns the pizza.
     */
    public function pizzaType(): BelongsTo
    {
        return $this->belongsTo(PizzaType::class, 'pizza_type_id');
    }

    /**
     * Get the order details for the pizza.
     */
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    /**
     * Get the size options for pizzas.
     *
     * @return array<string>
     */
    public static function getSizeOptions(): array
    {
        return ['small', 'medium', 'large'];
    }
}
