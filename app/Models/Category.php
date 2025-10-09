<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $table = 'categories';
    protected $fillable = ['category_name', 'description', 'image'];

    // One category has many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // One category has many menu items
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}