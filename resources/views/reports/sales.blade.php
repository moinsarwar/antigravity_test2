@extends('layouts.app')

@section('title', 'Sales Reports')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Sales Reports</h1>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter Reports</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <h6 class="text-white-50">Total Sales</h6>
                        <h3>{{ $stats['total_sales'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body">
                        <h6 class="text-white-50">Total Revenue</h6>
                        <h3>Rs. {{ number_format($stats['total_revenue'], 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-info text-white">
                    <div class="card-body">
                        <h6 class="text-white-50">Cash Collected</h6>
                        <h3>Rs. {{ number_format($stats['cash_sales'], 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body">
                        <h6 class="text-white-50">Total Credit (Due)</h6>
                        <h3>Rs. {{ number_format($stats['credit_sales'], 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered datatable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                                <tr>
                                    <td>{{ $sale->invoice_number }}</td>
                                    <td>{{ $sale->sale_date->format('d M Y') }}</td>
                                    <td>{{ $sale->customer_name ?? 'Walk-in' }}</td>
                                    <td>Rs. {{ number_format($sale->total_amount, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $sale->payment_type == 'cash' ? 'bg-success' : ($sale->payment_type == 'credit' ? 'bg-danger' : 'bg-warning') }}">
                                            {{ ucfirst($sale->payment_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('pos.invoice', $sale->id) }}" target="_blank"
                                            class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
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
