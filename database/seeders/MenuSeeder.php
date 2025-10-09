<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Category;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure categories exist
        if (Category::count() === 0) {
            $this->command->warn('⚠️ No categories found. Please run CategorySeeder first.');
            return;
        }

        $categories = Category::pluck('id', 'category_name')->toArray();

        $menus = [
            ['menu_name' => 'Soy Garlic Chicken', 'price' => 250, 'category_id' => $categories['Chicken'] ?? null],
            ['menu_name' => 'Spicy Korean Chicken', 'price' => 270, 'category_id' => $categories['Chicken'] ?? null],
            ['menu_name' => 'Bulgogi Beef Bowl', 'price' => 300, 'category_id' => $categories['Beef'] ?? null],
            ['menu_name' => 'Samgyeopsal Set', 'price' => 500, 'category_id' => $categories['Pork'] ?? null],
            ['menu_name' => 'Kimchi Fried Rice', 'price' => 150, 'category_id' => $categories['Noodles & Rice'] ?? null],
            ['menu_name' => 'Bibimbap', 'price' => 200, 'category_id' => $categories['Noodles & Rice'] ?? null],
            ['menu_name' => 'Tteokbokki', 'price' => 120, 'category_id' => $categories['Side Dishes'] ?? null],
            ['menu_name' => 'Sundubu Jjigae', 'price' => 220, 'category_id' => $categories['Soup & Stew'] ?? null],
            ['menu_name' => 'Japchae', 'price' => 180, 'category_id' => $categories['Noodles & Rice'] ?? null],
            ['menu_name' => 'Patbingsu', 'price' => 130, 'category_id' => $categories['Desserts'] ?? null],
            ['menu_name' => 'Korean Tea', 'price' => 90, 'category_id' => $categories['Beverages'] ?? null],
        ];

        foreach ($menus as $menu) {
            Menu::firstOrCreate(
                ['menu_name' => $menu['menu_name']],
                [
                    'description' => $menu['menu_name'] . ' — Authentic Korean flavor.',
                    'price' => $menu['price'],
                    'is_available' => true,
                    'category_id' => $menu['category_id'],
                    'rating' => rand(35, 50) / 10, // random 3.5 to 5.0
                ]
            );
        }
    }
}