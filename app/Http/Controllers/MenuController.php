<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('category');

        if ($search = $request->search) {
            $query->where('menu_name', 'like', "%{$search}%");
        }

        if ($categories = $request->category) {
            $query->whereIn('category_id', $categories);
        }

        if ($mealTimes = $request->meal_time) {
            $query->whereIn('meal_time', $mealTimes);
        }

        if ($price = $request->price) {
            if ($price == '100-200') $query->whereBetween('price', [100, 200]);
            elseif ($price == '200-500') $query->whereBetween('price', [200, 500]);
            elseif ($price == '500+') $query->where('price', '>=', 500);
        }

        if ($rating = $request->rating) {
            $query->where('rating', '>=', $rating);
        }

        if ($sort = $request->sort) {
            if ($sort == 'price_asc') $query->orderBy('price', 'asc');
            elseif ($sort == 'price_desc') $query->orderBy('price', 'desc');
            elseif ($sort == 'newest') $query->latest();
            else $query->orderBy('rating', 'desc'); // popular
        } else {
            $query->orderBy('rating', 'desc'); // default popular
        }

        $menus = $query->paginate(8)->withQueryString();

        $categories = Category::all();
        $archivedMenus = Menu::onlyTrashed()->paginate(8);

        return view('inventory.menus', compact('menus', 'categories','archivedMenus'), ['page' => 'menus']);
    }

    public function create()
    {
        $categories = Category::all(); // Fetch all categories from database
        return view('inventory.addMenu', compact('categories'), ['page' => 'menus']);
    }


    // Store new menu items (supports multiple)
   public function store(Request $request)
    {
        $request->validate([
            'items.*.name'     => 'required|string|max:255',
            'items.*.category' => 'required|string|max:255',
            'items.*.price'    => 'required|numeric|min:0',
            'items.*.description' => 'nullable|string|max:255',
            'items.*.image'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        foreach ($request->items as $item) {
            // Handle image upload
            $imagePath = null;
            if (isset($item['image']) && $item['image']->isValid()) {
                $imagePath = $item['image']->store('menus', 'public');
            }

            // Create Menu item
            Menu::create([
                'menu_name'    => $item['name'],
                'category_id'  => $item['category'], // If you have category table, adjust accordingly
                'price'        => $item['price'],
                'description'  => $item['description'] ?? null,
                'image'        => $imagePath,
                'is_available' => true,
                'rating'       => 0,
                'rating_count' => 0,
            ]);
        }

        return redirect()->route('menus.index')->with('success', 'Menu items added successfully!');
    }

    // Edit menu form
    public function edit(Menu $menu)
    {
        $categories = Category::all(); // Fetch all categories
        return view('inventory.editMenu', compact('menu', 'categories'), ['page' => 'menus']);
    }

    // Update a menu item
   public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'menu_name'    => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'description' => 'nullable|string|max:255',
            'price'        => 'required|numeric|min:0',
            'is_available' => 'required|boolean',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menu->image && file_exists(storage_path('app/public/' . $menu->image))) {
                unlink(storage_path('app/public/' . $menu->image));
            }

            // Store new image
            $menu->image = $request->file('image')->store('menus', 'public');
        }

        // Update menu data
        $menu->menu_name    = $request->menu_name;
        $menu->category_id  = $request->category_id;
        $menu->price        = $request->price;
        $menu->description  = $request->description;
        $menu->is_available = $request->is_available;
        $menu->save();

        return redirect()->route('menus.index')->with('success', 'Menu item updated successfully!');
    }

  // Soft delete a menu (archive)
    public function destroy(Menu $menu)
    {
        $menu->delete(); // This will now just set deleted_at
        return redirect()->route('menus.index')->with('success', 'Menu item archived successfully!');
    }

    // View archived menus
    public function archived()
    {
        $menus = Menu::onlyTrashed()->paginate(8);
        return view('inventory.archivedMenus', compact('menus'));
    }

    // Restore a menu
    public function restore($id)
    {
        $menu = Menu::onlyTrashed()->findOrFail($id);
        $menu->restore();
        return redirect()->route('menus.index')->with('success', 'Menu item restored successfully!');
    }

    // Permanently delete (optional)
    public function forceDelete($id)
    {
        $menu = Menu::onlyTrashed()->findOrFail($id);
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        $menu->forceDelete();
        return redirect()->route('menus.archived')->with('success', 'Menu item permanently deleted!');
    }


}