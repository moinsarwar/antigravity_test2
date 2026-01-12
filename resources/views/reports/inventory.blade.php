@extends('layouts.app')

@section('title', 'Inventory Stock Report')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Inventory Stock Report</h1>
        <button onclick="window.print()" class="btn btn-secondary"><i class="fas fa-print"></i> Print Report</button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered datatable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Purchase Price</th>
                            <th>Sale Price</th>
                            <th>Stock Value (Pur.)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalStockValue = 0; @endphp
                        @foreach($products as $product)
                        @php 
                            $qty = $product->inventory->quantity ?? 0;
                            $purPrice = $product->inventory->purchase_price ?? 0;
                            $stockValue = $qty * $purPrice;
                            $totalStockValue += $stockValue;
                        @endphp
                        <tr class="{{ $qty <= 10 ? 'table-warning' : '' }}">
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td class="fw-bold">{{ $qty }}</td>
                            <td>Rs. {{ number_format($purPrice, 2) }}</td>
                            <td>Rs. {{ number_format($product->inventory->sale_price ?? 0, 2) }}</td>
                            <td class="text-end">Rs. {{ number_format($stockValue, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold table-light">
                            <td colspan="5" class="text-end">Total Inventory Value:</td>
                            <td class="text-end">Rs. {{ number_format($totalStockValue, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
