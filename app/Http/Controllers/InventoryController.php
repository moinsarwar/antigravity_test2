<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = Inventory::with('product')->get();
        return view('inventory.index', compact('inventory'));
    }

    public function create()
    {
        $productsWithoutInventory = Product::whereDoesntHave('inventory')->get();
        return view('inventory.create', compact('productsWithoutInventory'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id|unique:inventory,product_id',
            'quantity' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'is_discounted' => 'required|boolean',
        ]);

        Inventory::create($request->all());

        return redirect()->route('inventory.index')->with('success', 'Inventory record created successfully.');
    }

    public function edit(Inventory $inventory)
    {
        return view('inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'is_discounted' => 'required|boolean',
        ]);

        $inventory->update($request->all());

        return redirect()->route('inventory.index')->with('success', 'Inventory record updated successfully.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Inventory record deleted successfully.');
    }
}
