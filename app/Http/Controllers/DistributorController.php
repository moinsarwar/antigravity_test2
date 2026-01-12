<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    public function index()
    {
        $distributors = Distributor::all();
        return view('distributors.index', compact('distributors'));
    }

    public function create()
    {
        return view('distributors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Distributor::create($request->all());

        return redirect()->route('distributors.index')->with('success', 'Distributor created successfully.');
    }

    public function edit(Distributor $distributor)
    {
        return view('distributors.edit', compact('distributor'));
    }

    public function update(Request $request, Distributor $distributor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $distributor->update($request->all());

        return redirect()->route('distributors.index')->with('success', 'Distributor updated successfully.');
    }

    public function destroy(Distributor $distributor)
    {
        $distributor->delete();
        return redirect()->route('distributors.index')->with('success', 'Distributor deleted successfully.');
    }
}
