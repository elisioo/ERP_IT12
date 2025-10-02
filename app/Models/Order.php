<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    
    protected $table = 'orders';
    protected $fillable = [
        'order_number',
        'customer_name',
        'order_date',
        'status',
        'total_amount',
    ];


    public function items()
    {
        return $this->hasMany(Product::class);
    }
}