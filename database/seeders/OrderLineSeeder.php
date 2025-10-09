<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Menu;
use App\Models\OrderLine;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderLineSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // --- 1️⃣ Ensure there are menus to attach ---
            if (Menu::count() === 0) {
                $this->command->warn('⚠️ No menus found. Please run MenuSeeder first.');
                return;
            }

            $menuIds = Menu::pluck('id')->toArray();

            // --- 2️⃣ Create 10 Orders ---
            for ($i = 1; $i <= 10; $i++) {
                $order = Order::create([
                    'order_number' => 'ORD-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'customer_name' => fake()->name(),
                    'order_date' => Carbon::now()->subDays(rand(0, 15)),
                    'status' => collect(['pending', 'processing', 'completed', 'canceled'])->random(),
                    'total_amount' => 0,
                ]);

                // --- 3️⃣ Add Order Lines ---
                $total = 0;
                $itemCount = rand(2, 4);

                for ($j = 1; $j <= $itemCount; $j++) {
                    $menuId = collect($menuIds)->random();
                    $menu = Menu::find($menuId);
                    $qty = rand(1, 5);
                    $price = $menu->price;
                    $subtotal = $qty * $price;

                    OrderLine::create([
                        'order_id' => $order->id,
                        'menu_id' => $menuId,
                        'quantity' => $qty,
                        'price' => $price,
                    ]);

                    $total += $subtotal;
                }

                // --- 4️⃣ Update total amount ---
                $order->update(['total_amount' => $total]);
            }
        });
    }
}