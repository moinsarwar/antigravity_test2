<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('category')->get();
        return view('sub_categories.index', compact('subCategories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('sub_categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        SubCategory::create($request->all());

        return redirect()->route('sub-categories.index')->with('success', 'Sub-Category created successfully.');
    }

    public function edit(SubCategory $subCategory)
    {
        $categories = Category::all();
        return view('sub_categories.edit', compact('subCategory', 'categories'));
    }

    public function update(Request $request, SubCategory $subCategory)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $subCategory->update($request->all());

        return redirect()->route('sub-categories.index')->with('success', 'Sub-Category updated successfully.');
    }

    public function destroy(SubCategory $subCategory)
    {
        $subCategory->delete();
        return redirect()->route('sub-categories.index')->with('success', 'Sub-Category deleted successfully.');
    }
}
