@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard Overview</h1>
            <a href="{{ route('pos.index') }}" class="btn btn-primary shadow-sm"><i
                    class="fas fa-plus fa-sm text-white-50"></i> New Sale</a>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Sales (Today)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rs.
                                    {{ number_format($stats['sales_today'], 2) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Products</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_products'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-pills fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Low Stock Alerts
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['low_stock'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Customers</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_customers'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Sales -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Sales</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($stats['recent_sales'] as $sale)
                                        <tr>
                                            <td>{{ $sale->invoice_number }}</td>
                                            <td>{{ $sale->customer_name ?? 'Walk-in' }}</td>
                                            <td>Rs. {{ number_format($sale->total_amount, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $sale->payment_type == 'cash' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ ucfirst($sale->payment_type) }}
                                                </span>
                                            </td>
                                            <td><a href="{{ route('pos.invoice', $sale->id) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary">View</a></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No recent sales found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('products.create') }}" class="btn btn-outline-primary text-start">
                                <i class="fas fa-plus me-2"></i> Add New Product
                            </a>
                            <a href="{{ route('categories.create') }}" class="btn btn-outline-secondary text-start">
                                <i class="fas fa-folder-plus me-2"></i> Add Category
                            </a>
                            <a href="{{ route('returns.index') }}" class="btn btn-outline-danger text-start">
                                <i class="fas fa-undo me-2"></i> Process Return
                            </a>
                            <a href="{{ route('reports.sales') }}" class="btn btn-outline-info text-start">
                                <i class="fas fa-chart-line me-2"></i> View Sales Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
