<?php

namespace App\Http\Controllers;

use App\Models\ReturnRecord;
use App\Models\Sale;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $sales = Sale::whereBetween('sale_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->with(['items'])
            ->get();

        $stats = [
            'total_sales' => $sales->count(),
            'total_revenue' => $sales->sum('total_amount'),
            'cash_sales' => $sales->sum('cash_amount'),
            'credit_sales' => $sales->sum('credit_amount'),
        ];

        return view('reports.sales', compact('sales', 'startDate', 'endDate', 'stats'));
    }

    public function returns(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $returns = ReturnRecord::whereBetween('return_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->with(['items', 'originalSale'])
            ->get();

        return view('reports.returns', compact('returns', 'startDate', 'endDate'));
    }

    public function inventory()
    {
        $products = Product::with('inventory')->get();
        return view('reports.inventory', compact('products'));
    }
}
