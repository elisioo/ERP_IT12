<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Menu;

class OrderController extends Controller
{
    // 🗑️ Archive (Soft Delete) multiple orders
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if ($ids) {
            Order::whereIn('id', $ids)->delete(); // Soft delete (archive)
            return redirect()->back()->with('success', 'Selected orders have been archived.');
        }

        return redirect()->back()->with('error', 'No orders selected.');
    }

    // 📋 Display all active (non-archived) orders
    public function index()
    {
        $orders = Order::with(['lines.menu'])
                       ->whereNull('deleted_at') // exclude archived
                       ->orderBy('order_date', 'desc')
                       ->paginate(10); // pagination

        $summary = [
            'total'      => Order::whereNull('deleted_at')->count(),
            'pending'    => Order::where('status', 'pending')->whereNull('deleted_at')->count(),
            'processing' => Order::where('status', 'processing')->whereNull('deleted_at')->count(),
            'completed'  => Order::where('status', 'completed')->whereNull('deleted_at')->count(),
            'canceled'   => Order::where('status', 'canceled')->whereNull('deleted_at')->count(),
        ];

        $trendingMenus = Menu::where('is_available', true)
            ->orderByDesc('rating')
            ->take(3)
            ->get();

        $latestMenus = Menu::where('is_available', true)
            ->latest()
            ->take(3)
            ->get();

        $monthlyOrders = Order::whereMonth('order_date', now()->month)->whereNull('deleted_at')->count();
        $yearlyOrders  = Order::whereYear('order_date', now()->year)->whereNull('deleted_at')->count();

        return view('inventory.order', compact(
            'orders',
            'summary',
            'trendingMenus',
            'latestMenus',
            'monthlyOrders',
            'yearlyOrders'
        ), ['page' => 'orders']);
    }

    // ➕ Show create form
    public function create()
    {
        $menus = Menu::where('is_available', true)->get();
        return view('inventory.addOrders', compact('menus'), ['page' => 'orders']);
    }

    // 💾 Store new order with order lines
    public function store(Request $request)
    {
        $latestOrder = Order::latest()->first();
        $nextNumber = $latestOrder ? intval(substr($latestOrder->order_number, 4)) + 1 : 1;
        $orderNumber = 'ORD-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        $order = Order::create([
            'order_number'  => $orderNumber,
            'customer_name' => $request->customer_name,
            'order_date'    => $request->order_date,
            'status'        => $request->status ?? 'pending',
            'total_amount'  => 0,
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

    // 🔍 Show order details
    public function show(Order $order)
    {
        $order->load(['lines.menu']);
        return view('inventory.orderDetails', compact('order'), ['page' => 'orders']);
    }

    // ✏️ Show edit form
    public function edit(Order $order)
    {
        $menus = Menu::all();
        $order->load('lines'); 
        return view('inventory.editOrders', compact('order', 'menus'), ['page' => 'orders']);
    }

    // 🔄 Update order
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'order_date'    => 'required|date',
            'status'        => 'required|in:pending,processing,completed,canceled',
        ]);

        $order->update($request->only(['customer_name', 'order_date', 'status']));

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

    // 🗃️ Archive (Soft Delete) single order
    public function destroy(Order $order)
    {
        $order->delete(); // Soft delete
        return redirect()->route('orders.index')->with('success', 'Order has been archived.');
    }

    // 📦 View archived (soft deleted) orders
    public function archived()
    {
        $orders = Order::onlyTrashed()
            ->with(['lines.menu'])
            ->orderByDesc('deleted_at')
            ->paginate(10);

        return view('inventory.archivedOrders', compact('orders'), ['page' => 'orders']);
    }

    // ♻️ Restore archived order
    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();

        return redirect()->back()->with('success', 'Order has been restored.');
    }

    public function updateArchiveDate(Request $request, $id)
{
    $request->validate([
        'deleted_at' => 'required|date',
    ]);

    $order = Order::onlyTrashed()->findOrFail($id);
    $order->deleted_at = $request->deleted_at;
    $order->save();

    return response()->json(['success' => true]);
}

}