<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_name',
        'description',
        'price',
        'is_available',
        'category_id',   // foreign key for category
        'meal_time',     // optional: Breakfast/Lunch/Dinner
        'image',         // path to uploaded image
        'rating',        // optional rating
    ];

    // Belongs to one category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderLines()
    {
        return $this->hasMany(OrderLine::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_lines')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }


}