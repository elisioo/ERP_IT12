<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'order_date',
        'status',
        'total_amount',
    ];

    // Each order has many order lines
    public function lines()
    {
        return $this->hasMany(OrderLine::class);
    }

    // Shortcut: fetch menus directly (many-to-many via order_lines)
    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'order_lines')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
}