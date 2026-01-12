@extends('layouts.app')

@section('title', 'Inventory / Stock')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Inventory Management</h1>
            <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Initial Stock
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered datatable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Purchase Price</th>
                                <th>Sale Price</th>
                                <th>Discounted?</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inventory as $item)
                                <tr class="{{ $item->quantity <= 10 ? 'table-warning' : '' }}">
                                    <td>{{ $item->product->subCategory->name }} {{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rs. {{ number_format($item->purchase_price, 2) }}</td>
                                    <td>Rs. {{ number_format($item->sale_price, 2) }}</td>
                                    <td>
                                        @if($item->is_discounted)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('inventory.destroy', $item->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
