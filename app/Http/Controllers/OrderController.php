<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // List all orders
    public function index()
    {
        $orders = Order::orderBy('created_at','desc')->paginate(10);

        // Counts for summary cards
        $summary = [
            'total' => Order::count(),
            'on_process' => Order::where('status','on_process')->count(),
            'completed' => Order::where('status','completed')->count(),
            'canceled' => Order::where('status','canceled')->count(),
        ];

        return view('orders.index', compact('orders','summary'));
    }

    // Show form to create order
    public function create()
    {
        return view('orders.create');
    }

    // Store new order
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'items' => 'required|string',
            'total' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,canceled,on_process',
        ]);

        Order::create([
            'order_number' => 'ORD-' . rand(1000,9999),
            'customer_name' => $request->customer_name,
            'items' => $request->items,
            'total' => $request->total,
            'status' => $request->status,
        ]);

        return redirect()->route('orders.index')->with('success','Order created successfully!');
    }

    // Show single order
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    // Show edit form
    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    // Update order
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'items' => 'required|string',
            'total' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,canceled,on_process',
        ]);

        $order->update($request->all());

        return redirect()->route('orders.index')->with('success','Order updated successfully!');
    }

    // Delete order
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success','Order deleted successfully!');
    }
}