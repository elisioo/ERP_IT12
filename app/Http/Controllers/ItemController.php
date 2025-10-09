<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Display all active and archived items
  public function index()
    {
        // Eager-load category relationship for efficiency
        $items = Item::with('category')->paginate(10); // pagination

        // Soft-deleted items for Archive modal (optional)
        $archivedItems = Item::onlyTrashed()->with('category')->get();

        // Compute dashboard summary
        $totalItems = Item::count();
        $lowStockCount = Item::where('quantity', '<', 10)->where('quantity', '>', 0)->count();
        $outOfStockCount = Item::where('quantity', '=', 0)->count();

        // Load categories for add-item modal
        $categories = Category::all();

        return view('inventory.inventory_path', compact(
            'items',
            'archivedItems',
            'categories',
            'totalItems',
            'lowStockCount',
            'outOfStockCount'
        ), ['page' => 'inventory']);
    }

    // Store new item
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:50',
        ]);

        Item::create($request->all());
        return redirect()->back()->with('success', 'Item added successfully!');
    }

    // Soft Delete (Archive)
    public function archive($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', 'Item archived successfully!');
    }

    // Restore Archived Item
    public function restore($id)
    {
        $item = Item::onlyTrashed()->findOrFail($id);
        $item->restore();

        return redirect()->back()->with('success', 'Item restored successfully!');
    }

    // Permanent Delete
    public function destroy($id)
    {
        $item = Item::onlyTrashed()->findOrFail($id);
        $item->forceDelete();

        return redirect()->back()->with('success', 'Item permanently deleted!');
    }
}