<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    //  Show all categories
    public function index()
    {
        $categories = Category::with('menus')->get();
        return view('inventory.category', compact('categories'), ['page' => 'category']);
    }

    //  Store new category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('category_images', 'public');
        }

        Category::create([
            'category_name' => $validated['category_name'],
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Category added successfully!');
    }

    //  Update existing category
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'category_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // If new image is uploaded, delete old one
        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('category_images', 'public');
        }

        $category->update($validated);

        return back()->with('success', 'Category updated successfully!');
    }

   
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully!');
    }
}