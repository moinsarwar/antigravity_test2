<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class POSController extends Controller
{
    public function index()
    {
        return view('pos.index');
    }

    public function searchProducts(Request $request)
    {
        $query = $request->get('q');
        $products = Product::with(['inventory', 'subCategory']);

        if ($query) {
            $products->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('generic_name', 'LIKE', "%{$query}%");
            });
        } else {
            $products->latest()->take(15);
        }

        return response()->json($products->get());
    }

    public function processSale(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'payment_type' => 'required|in:cash,credit,partial',
            'cash_amount' => 'required|numeric|min:0',
            'credit_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount_type' => 'required|in:none,percent,amount',
            'items.*.discount_value' => 'required|numeric|min:0',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $totalAmount = 0;
                $itemDetails = [];

                foreach ($request->items as $item) {
                    $inventory = Inventory::where('product_id', $item['id'])->first();
                    if (!$inventory || $inventory->quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for product: " . ($inventory ? $inventory->product->name : 'Unknown'));
                    }

                    $unitPrice = $inventory->sale_price;
                    $subtotal = $unitPrice * $item['quantity'];
                    $discountAmount = 0;

                    if ($inventory->is_discounted) {
                        if ($item['discount_type'] === 'percent') {
                            $discountAmount = ($subtotal * $item['discount_value']) / 100;
                        } else if ($item['discount_type'] === 'amount') {
                            $discountAmount = $item['discount_value'];
                        }
                    }

                    $finalSubtotal = $subtotal - $discountAmount;
                    $totalAmount += $finalSubtotal;

                    $itemDetails[] = [
                        'inventory' => $inventory,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $unitPrice,
                        'discount_type' => $inventory->is_discounted ? $item['discount_type'] : 'none',
                        'discount_value' => $inventory->is_discounted ? $item['discount_value'] : 0,
                        'discount_amount' => $discountAmount,
                        'subtotal' => $finalSubtotal,
                    ];
                }

                $sale = Sale::create([
                    'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                    'sale_date' => now(),
                    'total_amount' => $totalAmount,
                    'cash_amount' => $request->cash_amount,
                    'credit_amount' => $request->credit_amount,
                    'payment_type' => $request->payment_type,
                    'customer_name' => $request->customer_name,
                ]);

                foreach ($itemDetails as $detail) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $detail['product_id'],
                        'quantity' => $detail['quantity'],
                        'unit_price' => $detail['unit_price'],
                        'discount_type' => $detail['discount_type'],
                        'discount_value' => $detail['discount_value'],
                        'discount_amount' => $detail['discount_amount'],
                        'subtotal' => $detail['subtotal'],
                    ]);

                    // Reduce Inventory
                    $detail['inventory']->decrement('quantity', $detail['quantity']);
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Sale processed successfully.',
                    'invoice_url' => route('pos.invoice', $sale->id)
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function showInvoice(Sale $sale)
    {
        $sale->load(['items.product.subCategory']);
        return view('pos.invoice', compact('sale'));
    }
}
