<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
class InventoryController extends Controller
{
    /**
     * Display inventory list with stats and archived items.
     */
    public function index(Request $request)
    {
        $query = Inventory::with(['category', 'menu']);
        $inventoryAlert = session('inventory_alert');
        // Remove it after showing once
        if ($inventoryAlert) {
            session()->forget('inventory_alert');
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('menu', function ($q) use ($search) {
                $q->where('menu_name', 'like', "%{$search}%");
            })->orWhereHas('category', function ($q) use ($search) {
                $q->where('category_name', 'like', "%{$search}%");
            });
        }


        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $items = $query->paginate(5)->withQueryString(); // Preserve query params
        $archivedItems = Inventory::onlyTrashed()->with(['category', 'menu'])->get();
        $categories = Category::all();
        $menus = Menu::all();

        // Summary stats
        $totalItems = Inventory::count();
        $lowStockCount = Inventory::where('quantity', '<', 10)->where('quantity', '>', 0)->count();
        $outOfStockCount = Inventory::where('quantity', '=', 0)->count();

        // Stock alert
        $stockAlert = $this->generateStockAlert(); 
        $stockStatus = $this->getStockStatus();

        return view('inventory.inventory_path', compact(
            'items', 'archivedItems', 'categories',
            'totalItems', 'lowStockCount', 'outOfStockCount', 'menus','stockAlert','stockStatus','inventoryAlert'
        ), ['page' => 'inventory']);
    }


    /**
     * Store a new inventory item (restock or add new).
     */
    public function store(Request $request)
    {
        try {
            // If an existing menu item is selected (from right column)
            if ($request->filled('menu_id')) {
                $validated = $request->validate([
                    'menu_id' => 'required|exists:menus,id',
                    'quantity' => 'required|integer|min:0',
                    'unit' => 'required|string|max:50',
                ]);

                $menu = Menu::findOrFail($validated['menu_id']);

                // Find inventory record linked to that menu
                $inventory = Inventory::where('menu_id', $menu->id)->first();

                if ($inventory) {
                    // Update existing stock
                    $inventory->quantity += $validated['quantity'];
                    $inventory->save();
                } else {
                    // Create new inventory entry based on menu data
                    Inventory::create([
                        'menu_id' => $menu->id,
                        'category_id' => $menu->category_id,
                        'quantity' => $validated['quantity'],
                        'cost_price' => $menu->price,
                        'unit' => $validated['unit'],
                    ]);
                }

                return redirect()->route('inventory.index')->with('success', 'Existing menu item restocked successfully.');
            }

            // Otherwise, user is adding a completely new item
            $validated = $request->validate([
                'item_name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'quantity' => 'required|integer|min:0',
                'cost_price' => 'required|numeric|min:0',
                'unit' => 'required|string|max:50',
            ]);

            // Create or get existing menu record (based on menu_name)
            $menu = Menu::firstOrCreate(
                ['menu_name' => $validated['item_name']], // corrected column name
                [
                    'category_id' => $validated['category_id'],
                    'price' => $validated['cost_price'],
                    'is_available' => true,
                ]
            );

            // Check if inventory record already exists
            $inventory = Inventory::where('menu_id', $menu->id)->first();

            if ($inventory) {
                $inventory->quantity += $validated['quantity'];
                $inventory->cost_price = $validated['cost_price'];
                $inventory->unit = $validated['unit'];
                $inventory->save();
            } else {
                Inventory::create([
                    'menu_id' => $menu->id,
                    'category_id' => $validated['category_id'],
                    'quantity' => $validated['quantity'],
                    'cost_price' => $validated['cost_price'],
                    'unit' => $validated['unit'],
                ]);
            }

            return redirect()->route('inventory.index')->with('success', 'New item successfully added.');
        } catch (\Exception $e) {
            return redirect()->route('inventory.index')->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'cost_price' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
        ]);

        $inventory = Inventory::findOrFail($id);

        // Update linked menu
        $menu = $inventory->menu;
        if ($menu) {
            $menu->update([
                'menu_name' => $validated['item_name'],
                'category_id' => $validated['category_id'],
                'price' => $validated['cost_price'],
            ]);
        }

        // Update inventory
        $inventory->update([
            'category_id' => $validated['category_id'],
            'quantity' => $validated['quantity'],
            'cost_price' => $validated['cost_price'],
            'unit' => $validated['unit'],
        ]);

        return redirect()->route('inventory.index')->with('success', 'Item updated successfully.');
    }

    private function getStockStatus()
    {
        $threshold = 10;

        // Count all inventory items below and above threshold
        $totalLowStock = Inventory::where('quantity', '<', $threshold)->count();
        $restockedLowStock = Inventory::where('quantity', '>=', $threshold)->count();

        $totalItems = $totalLowStock + $restockedLowStock;

        if ($totalItems === 0) {
            return [
                'percentage' => 100,
                'text' => 'No inventory data available',
            ];
        }

        $percentage = round(($restockedLowStock / $totalItems) * 100, 0);

        return [
            'percentage' => $percentage,
            'text' => "{$percentage}% of low-stock items restocked",
        ];
    }

/**
 * Generate alert for low-stock inventory
 */
    private function generateStockAlert()
    {
        $threshold = 10;

        // Get low-stock inventories with menu relationship
        $lowStockMenus = Inventory::with('menu')
            ->where('quantity', '<', $threshold)
            ->orderBy('quantity', 'asc')
            ->take(3)
            ->get();

        if ($lowStockMenus->isEmpty()) {
            return null;
        }

        // Collect menu names or fallback text if null
        $menuNames = $lowStockMenus->map(function ($inv) {
            return $inv->menu->menu_name ?? 'Not available';
        });

        $itemList = $menuNames->join(', ');

        return "Low stock alert: {$itemList} need to be reordered soon!";
    }

    /**
     * Archive an inventory item (soft delete).
     */
    public function archive($id)
    {
        $item = Inventory::findOrFail($id);
        $item->delete();

        return redirect()->route('inventory.index')->with('success', 'Item archived successfully.');
    }

    /**
     * Restore an archived inventory item.
     */
    public function restore($id)
    {
        $item = Inventory::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()->route('inventory.index')->with('success', 'Item restored successfully.');
    }
    /**
     * Permanently delete an archived inventory item.
     */
    public function destroy($id)
    {
        $item = Inventory::onlyTrashed()->findOrFail($id);
        $item->forceDelete(); 
        return redirect()->route('inventory.index')->with('success', 'Item permanently deleted.');
    }
   public function generate()
    {
        // Summary stats
        $totalItems = Inventory::count();
        $lowStockCount = Inventory::where('quantity', '<', 10)->where('quantity', '>', 0)->count();
        $outOfStockCount = Inventory::where('quantity', '=', 0)->count();
        $totalValue = Inventory::sum(DB::raw('quantity * cost_price'));

        // Category summary
        $categorySummary = Category::withCount(['inventories as total_items'])
            ->withSum(['inventories as total_value' => function($query) {
                $query->select(DB::raw('quantity * cost_price as total_value'));
            }], 'total_value')
            ->get();

        // Inventory list
        $items = Inventory::with(['menu', 'category'])->get();

        $pdf = Pdf::loadView('inventory.inventory_report', compact(
            'totalItems',
            'lowStockCount',
            'outOfStockCount',
            'totalValue',
            'categorySummary',
            'items'
        ));

        // Download the PDF
        // return $pdf->download('inventory_report.pdf');
        return $pdf->stream('Inventory_Report.pdf');

    }

}