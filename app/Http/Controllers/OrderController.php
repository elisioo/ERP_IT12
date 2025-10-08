<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Menu;

class OrderController extends Controller
{
    // Bulk delete orders
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return redirect()->route('orders.index')->with('error', 'No orders selected for deletion.');
        }
        Order::whereIn('id', $ids)->delete();
        return redirect()->route('orders.index')->with('success', 'Selected orders deleted successfully!');
    }
    // Display all orders
    public function index()
    {
        $orders = Order::with(['lines.menu'])
                       ->orderBy('order_date', 'desc')
                       ->paginate(10);

        $summary = [
            'total'     => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'processing'=> Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'canceled'  => Order::where('status', 'canceled')->count(),
        ];

        $trendingMenus = Menu::where('is_available', true)
            ->orderByDesc('rating')
            ->take(3)
            ->get();

        $latestMenus = Menu::where('is_available', true)
            ->latest()
            ->take(3)
            ->get();

        $monthlyOrders = Order::whereMonth('order_date', now()->month)->count();
        $yearlyOrders  = Order::whereYear('order_date', now()->year)->count();

        return view('inventory.order', compact(
            'orders',
            'summary',
            'trendingMenus',
            'latestMenus',
            'monthlyOrders',
            'yearlyOrders'
        ), ['page' => 'orders']);
    }

    // Show create form
    public function create()
    {
        $menus = Menu::where('is_available', true)->get();
        return view('inventory.addOrders', compact('menus'), ['page' => 'orders']);
    }

    // Store new order with order lines
    public function store(Request $request)
    {
        // Generate next order number
        $latestOrder = Order::latest()->first();
        $nextNumber = $latestOrder ? intval(substr($latestOrder->order_number, 4)) + 1 : 1;
        $orderNumber = 'ORD-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $order = Order::create([
            'order_number'  => $orderNumber,
            'customer_name' => $request->customer_name,
            'order_date'    => $request->order_date,
            'status'        => $request->status ?? 'pending',
            'total_amount'  => 0, // will calculate below
        ]);

        $total = 0;

        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $menu = Menu::find($item['menu_id']);
                if ($menu) {
                    $lineTotal = $menu->price * $item['quantity'];
                    OrderLine::create([
                        'order_id' => $order->id,
                        'menu_id'  => $menu->id,
                        'quantity' => $item['quantity'],
                        'price'    => $lineTotal,
                    ]);
                    $total += $lineTotal;
                }
            }
        }

        $order->update(['total_amount' => $total]);

        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }

    // Show order details
    public function show(Order $order)
    {
        $order->load(['lines.menu']);
        return view('inventory.orderDetails', compact('order'), ['page' => 'orders']);
    }

    // Show edit form
    public function edit(Order $order)
    {
        $menus = Menu::all();
        $order->load('lines'); 
        return view('inventory.editOrders', compact('order', 'menus'), ['page' => 'orders']);
    }


    // Update order
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'order_date'    => 'required|date',
            'status'        => 'required|in:pending,processing,completed,canceled',
        ]);

        $order->update($request->only(['customer_name', 'order_date', 'status']));

        // Reset order lines if items provided
        if ($request->has('items')) {
            $order->lines()->delete();

            $total = 0;
            foreach ($request->items as $item) {
                $menu = Menu::find($item['menu_id']);
                if ($menu) {
                    $lineTotal = $menu->price * $item['quantity'];
                    OrderLine::create([
                        'order_id' => $order->id,
                        'menu_id'  => $menu->id,
                        'quantity' => $item['quantity'],
                        'price'    => $lineTotal,
                    ]);
                    $total += $lineTotal;
                }
            }
            $order->update(['total_amount' => $total]);
        }

        return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
    }

    // Delete order
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully!');
    }
}
