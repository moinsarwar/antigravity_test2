<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\ReturnRecord;
use App\Models\ReturnItem;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReturnController extends Controller
{
    public function index()
    {
        return view('returns.index');
    }

    public function searchInvoice(Request $request)
    {
        $sale = Sale::with(['items.product'])->where('invoice_number', $request->invoice_number)->first();
        if (!$sale) {
            return response()->json(['success' => false, 'message' => 'Invoice not found.'], 404);
        }
        return response()->json(['success' => true, 'sale' => $sale]);
    }

    public function processReturn(Request $request)
    {
        $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'return_type' => 'required|in:return,exchange',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $sale = Sale::find($request->sale_id);
                $totalReturnAmount = 0;

                foreach ($request->items as $item) {
                    $saleItem = $sale->items()->where('product_id', $item['product_id'])->first();
                    if (!$saleItem || $saleItem->quantity < $item['quantity']) {
                        throw new \Exception("Cannot return more than purchased for product ID: {$item['product_id']}");
                    }
                    $effectiveUnitPrice = $saleItem->subtotal / $saleItem->quantity;
                    $totalReturnAmount += $effectiveUnitPrice * $item['quantity'];
                }

                $returnRecord = ReturnRecord::create([
                    'return_invoice_number' => 'RET-' . strtoupper(Str::random(8)),
                    'original_sale_id' => $sale->id,
                    'return_date' => now(),
                    'total_amount' => $totalReturnAmount,
                    'return_type' => $request->return_type,
                ]);

                foreach ($request->items as $item) {
                    $saleItem = $sale->items()->where('product_id', $item['product_id'])->first();
                    $effectiveUnitPrice = $saleItem->subtotal / $saleItem->quantity;

                    ReturnItem::create([
                        'return_id' => $returnRecord->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $effectiveUnitPrice,
                        'subtotal' => $effectiveUnitPrice * $item['quantity'],
                        'action_type' => $request->return_type,
                    ]);

                    // Update Inventory
                    $inventory = Inventory::where('product_id', $item['product_id'])->first();
                    if ($inventory) {
                        $inventory->increment('quantity', $item['quantity']);
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Return processed successfully.',
                    'invoice_url' => route('returns.invoice', $returnRecord->id)
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function showReturnInvoice(ReturnRecord $return)
    {
        $return->load(['items.product.subCategory', 'originalSale']);
        return view('returns.invoice', compact('return'));
    }
}
