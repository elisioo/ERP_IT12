<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Chicken', 'description' => 'Fried, grilled, or spicy Korean chicken dishes'],
            ['category_name' => 'Beef', 'description' => 'Beef dishes like Bulgogi or Galbi'],
            ['category_name' => 'Pork', 'description' => 'Korean pork dishes like Samgyeopsal'],
            ['category_name' => 'Seafood', 'description' => 'Seafood dishes such as seafood stews or grilled fish'],
            ['category_name' => 'Noodles & Rice', 'description' => 'Bibimbap, Jjajangmyeon, and other rice/noodle dishes'],
            ['category_name' => 'Soup & Stew', 'description' => 'Hot soups and stews like Kimchi Jjigae, Sundubu Jjigae'],
            ['category_name' => 'Side Dishes', 'description' => 'Banchan, kimchi, and other side dishes'],
            ['category_name' => 'Beverages', 'description' => 'Soft drinks, Korean teas, and juices'],
            ['category_name' => 'Desserts', 'description' => 'Sweet treats like Patbingsu and Korean pastries'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['category_name' => $category['category_name']], $category);
        }
    }
}