<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['category_name', 'description'];

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
}