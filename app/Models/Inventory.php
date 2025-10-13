<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventory extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'inventory';
    protected $fillable = [
        'category_id',
        'menu_id',
        'quantity',
        'cost_price',
        'unit',
        'restock_amount'
    ];

    // Each inventory item belongs to one category
    public function category()
    {
        return $this->belongsTo(Category::class);   
    }
    // Each inventory item belongs to one menu
    public function menu()
    {
        return $this->belongsTo(Menu::class); 
    }

}