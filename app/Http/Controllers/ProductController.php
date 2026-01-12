<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Distributor;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'subCategory', 'brand', 'distributor', 'inventory'])->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();
        $brands = Brand::all();
        $distributors = Distributor::all();
        return view('products.create', compact('categories', 'subCategories', 'brands', 'distributors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'brand_id' => 'required|exists:brands,id',
            'distributor_id' => 'required|exists:distributors,id',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $subCategories = SubCategory::all();
        $brands = Brand::all();
        $distributors = Distributor::all();
        return view('products.edit', compact('product', 'categories', 'subCategories', 'brands', 'distributors'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'required|exists:sub_categories,id',
            'brand_id' => 'required|exists:brands,id',
            'distributor_id' => 'required|exists:distributors,id',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    public function getSubCategories(Request $request)
    {
        $subCategories = SubCategory::where('category_id', $request->category_id)->get();
        return response()->json($subCategories);
    }
}
