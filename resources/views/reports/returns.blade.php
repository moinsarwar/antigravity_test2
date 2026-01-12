@extends('layouts.app')

@section('title', 'Return Reports')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Return & Exchange Reports</h1>
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
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered datatable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Return #</th>
                                <th>Orig. Invoice</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($returns as $return)
                                <tr>
                                    <td>{{ $return->return_invoice_number }}</td>
                                    <td>{{ $return->originalSale->invoice_number }}</td>
                                    <td>{{ $return->return_date->format('d M Y') }}</td>
                                    <td>Rs. {{ number_format($return->total_amount, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $return->return_type == 'return' ? 'bg-danger' : 'bg-primary' }}">
                                            {{ ucfirst($return->return_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('returns.invoice', $return->id) }}" target="_blank"
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
