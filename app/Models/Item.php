<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{   
    use HasFactory, SoftDeletes;
    protected $table = 'items';
    protected $fillable = ['item_name', 'description', 'category_id', 'quantity', 'cost_price', 'unit', 'restock_amount'  ];

    // One item belongs to one category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}