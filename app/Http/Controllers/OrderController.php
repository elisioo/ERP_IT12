<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Menu;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Intervention\Image\ImageManagerStatic as Image;
use Chartisan\PHP\Chartisan; 
class OrderController extends Controller
{
    // Archive (Soft Delete) multiple orders
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');


        if ($ids) {
            Order::whereIn('id', $ids)->delete(); // Soft delete (archive)
            return redirect()->back()->with('success', 'Selected orders have been archived.');
        }

        return redirect()->back()->with('error', 'No orders selected.');
    }

    //  Display all active (non-archived) orders
    public function index(Request $request)
    {
        $perPage = $request->get('perPage', 5);
        $status = $request->get('status');
        $search = $request->get('search');
        $sort = $request->get('sort', 'desc'); // default newest first

        $ordersQuery = Order::with(['lines.menu'])
            ->whereNull('deleted_at');

        //  Search filter (by order no or customer)
        if (!empty($search)) {
            $ordersQuery->where(function ($query) use ($search) {
                $query->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        //  Status filter
        if (!empty($status)) {
            $ordersQuery->where('status', $status);
        }

        //  Sort by order date
        $ordersQuery->orderBy('order_date', $sort);

        $orderss = $ordersQuery->paginate($perPage)
            ->appends([
                'perPage' => $perPage,
                'status' => $status,
                'search' => $search,
                'sort' => $sort,
            ]);

        // Summary counts
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
        $archivedOrders = Order::onlyTrashed()
            ->with(['lines.menu'])
            ->orderBy('deleted_at', 'desc')
            ->paginate(5);

        return view('inventory.order', compact(
            'orderss',
            'summary',
            'trendingMenus',
            'latestMenus',
            'monthlyOrders',
            'yearlyOrders',
            'perPage',
            'status',
            'search',
            'sort',
            'archivedOrders'
        ), ['page' => 'orders']);

    }


    //  Show create form
    public function create()
    {
        $menus = Menu::where('is_available', true)->get();
        return view('inventory.addOrders', compact('menus'), ['page' => 'orders']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'order_date'    => 'required|date',
            'status'        => 'required|in:pending,processing,completed,canceled',
            'items'         => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // ✅ Generate a unique order number safely
        $orderNumber = null;
        $attempts = 0;

        do {
            $latestOrder = Order::latest('id')->first();
            $nextNumber = $latestOrder ? intval(substr($latestOrder->order_number, 4)) + 1 : 1;
            $orderNumber = 'ORD-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $exists = Order::where('order_number', $orderNumber)->withTrashed()->exists();
            $attempts++;
        } while ($exists && $attempts < 10);

        if ($exists) {
            return back()->with('error', 'Failed to generate a unique order number after several attempts. Please try again.');
        }

        // ✅ Validate stock availability before creating the order
        foreach ($request->items as $i => $item) {
            $menu = Menu::with('inventory')->find($item['menu_id']);
            $availableQty = $menu->inventory->quantity ?? 0;

            if ($item['quantity'] > $availableQty) {
                return back()
                    ->withInput()
                    ->with('error', "Cannot order more than available stock for \"{$menu->menu_name}\". Available: {$availableQty}, Requested: {$item['quantity']}.");
            }
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number'  => $orderNumber,
                'customer_name' => $request->customer_name,
                'order_date'    => $request->order_date,
                'status'        => $request->status ?? 'pending',
                'total_amount'  => 0,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $menu = Menu::with('inventory')->findOrFail($item['menu_id']);
                $qty = (int) $item['quantity'];
                $lineTotal = $menu->price * $qty;

                OrderLine::create([
                    'order_id' => $order->id,
                    'menu_id'  => $menu->id,
                    'quantity' => $qty,
                    'price'    => $lineTotal,
                ]);

                // Deduct inventory
                $inventory = $menu->inventory;
                if ($inventory) {
                    $inventory->quantity = max(0, $inventory->quantity - $qty);
                    $inventory->save();

                    // Update menu availability
                    if ($inventory->quantity <= 0) {
                        $menu->is_available = false;
                        $menu->save();
                    }
                } else {
                    throw new \Exception("Inventory record missing for menu ID {$menu->id}");
                }

                $total += $lineTotal;
            }

            $order->update(['total_amount' => $total]);

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create order: ' . $e->getMessage());
        }
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

   public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'order_date'    => 'required|date',
            'status'        => 'required|in:pending,processing,completed,canceled',
            'items'         => 'nullable|array',
            'items.*.menu_id' => 'required_with:items|exists:menus,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            //  Restore inventory from existing order lines before making updates
            foreach ($order->lines as $line) {
                $menu = Menu::with('inventory')->find($line->menu_id);
                if ($menu && $menu->inventory) {
                    $menu->inventory->quantity += $line->quantity;
                    $menu->inventory->save();

                    // Mark menu as available again if it now has stock
                    if ($menu->inventory->quantity > 0 && !$menu->is_available) {
                        $menu->is_available = true;
                        $menu->save();
                    }
                }
            }

            //  If the order is being canceled — stop here and keep items restored
            if ($request->status === 'canceled') {
                $order->update([
                    'customer_name' => $request->customer_name,
                    'order_date'    => $request->order_date,
                    'status'        => 'canceled',
                    'total_amount'  => 0, // No total for canceled order
                ]);

                // Delete all order lines since the order is canceled
                $order->lines()->delete();

                DB::commit();
                return redirect()->route('orders.index')->with('success', 'Order has been canceled and inventory restored.');
            }

            //  If not canceled, continue normal update flow
            $order->lines()->delete();

            $items = $request->items ?? [];
            foreach ($items as $item) {
                $menu = Menu::with('inventory')->find($item['menu_id']);
                $availableQty = $menu->inventory->quantity ?? 0;

                if ($item['quantity'] > $availableQty) {
                    DB::rollBack();
                    return redirect()->back()
                        ->withInput()
                        ->with('error', "Cannot order more than available stock for \"{$menu->menu_name}\". Available: {$availableQty}, Requested: {$item['quantity']}.");
                }
            }

            // Recreate order lines and deduct inventory again
            $total = 0;
            foreach ($items as $item) {
                $menu = Menu::with('inventory')->findOrFail($item['menu_id']);
                $qty = (int) $item['quantity'];
                $lineTotal = $menu->price * $qty;

                OrderLine::create([
                    'order_id' => $order->id,
                    'menu_id'  => $menu->id,
                    'quantity' => $qty,
                    'price'    => $lineTotal,
                ]);

                // Deduct from inventory again
                if ($menu->inventory) {
                    $menu->inventory->quantity = max(0, $menu->inventory->quantity - $qty);
                    $menu->inventory->save();

                    // Update availability based on remaining stock
                    if ($menu->inventory->quantity <= 0) {
                        $menu->is_available = false;
                        $menu->save();
                    } elseif (!$menu->is_available) {
                        $menu->is_available = true;
                        $menu->save();
                    }
                } else {
                    throw new \Exception("Inventory record missing for menu ID {$menu->id}");
                }

                $total += $lineTotal;
            }
            // Update order details and total amount
            $order->update([
                'customer_name' => $request->customer_name,
                'order_date'    => $request->order_date,
                'status'        => $request->status,
                'total_amount'  => $total,
            ]);

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Failed to update order: ' . $e->getMessage());
        }
    }



    // Archive (Soft Delete) single order
    public function destroy(Order $order)
    {
        $order->delete(); // Soft delete
        return redirect()->route('orders.index')->with('success', 'Order has been archived.');
    }

    public function archiveSelection(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || count($ids) === 0) {
            return redirect()->route('orders.index')
                ->with('error', 'No orders selected for archiving.');
        }

        // Soft delete (archive) the selected orders
        $count = Order::whereIn('id', $ids)->delete();

        return redirect()->route('orders.index')
            ->with('success', "$count order(s) successfully archived.");
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
    public function archived()
    {
        $archivedOrders = Order::onlyTrashed()
            ->with(['lines.menu'])
            ->orderBy('deleted_at', 'desc')
            ->get();

        // return HTML partial for modal body
        return view('inventory.partials.archived_orders', compact('archivedOrders'));
    }

    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();

        return redirect()->back()->with('success', 'Order restored successfully.');
    }

    public function forceDelete(Request $request, $id = null)
    {
        try {
            // Handle single delete (from button)
            if ($id) {
                $order = Order::onlyTrashed()->find($id);

                if (!$order) {
                    return redirect()->back()->with('error', 'Order not found or already deleted.');
                }

                $order->forceDelete();
                return redirect()->back()->with('success', 'Order permanently deleted.');
            }

            // Handle multiple delete (from checkboxes)
            $ids = $request->input('ids', []);
            if (empty($ids)) {
                return redirect()->back()->with('error', 'No orders selected to permanently delete.');
            }

            DB::transaction(function () use ($ids) {
                Order::onlyTrashed()->whereIn('id', $ids)->forceDelete();
            });

            return redirect()->back()->with('success', 'Selected orders permanently deleted.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }


    public function analytics(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        $orders = Order::with('lines.menu')
            ->whereBetween('order_date', [$request->from_date, $request->to_date])
            ->get();

        $totalSales = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        

        $pdf = Pdf::loadView('inventory.analytics_report', compact('orders', 'totalSales', 'totalOrders', 'request'));

        return $pdf->stream('Sales_Report_'.$request->from_date.'_to_'.$request->to_date.'.pdf');
    }
    public function invoice(Order $order)
    {
        $order->load('lines.menu');

        $pdf = Pdf::loadView('inventory.invoice', compact('order'));

        return $pdf->stream('Invoice_'.$order->order_number.'.pdf');
    }

}