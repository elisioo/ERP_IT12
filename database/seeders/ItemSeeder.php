<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['item_name' => 'Yangnyeom Chicken', 'category' => 'Chicken', 'quantity' => 25, 'cost_price' => 100, 'unit' => 'pcs'],
            ['item_name' => 'Fried Chicken', 'category' => 'Chicken', 'quantity' => 8,  'cost_price' => 80, 'unit' => 'pcs'],
            ['item_name' => 'Beef Bulgogi', 'category' => 'Beef', 'quantity' => 15, 'cost_price' => 120, 'unit' => 'bowl'],
            ['item_name' => 'Kimchi Jjigae', 'category' => 'Soup & Stew', 'quantity' => 5, 'cost_price' => 90, 'unit' => 'bowl'],
            ['item_name' => 'Bibimbap', 'category' => 'Noodles & Rice', 'quantity' => 12,  'cost_price' => 85, 'unit' => 'bowl'],
            ['item_name' => 'Patbingsu', 'category' => 'Desserts', 'quantity' => 0,  'cost_price' => 70, 'unit' => 'serving'],
            ['item_name' => 'Soju', 'category' => 'Beverages', 'quantity' => 30,  'cost_price' => 50, 'unit' => 'bottle'],
        ];

        foreach ($items as $item) {
            $category = Category::where('category_name', $item['category'])->first();

            if ($category) {
                Item::firstOrCreate(
                    ['item_name' => $item['item_name']],
                    [
                        'category_id' => $category->id,
                        'quantity' => $item['quantity'],
                        'cost_price' => $item['cost_price'],
                        'unit' => $item['unit'],
                    ]
                );
            }
        }
    }
}