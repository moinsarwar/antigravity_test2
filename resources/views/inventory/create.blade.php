@extends('layouts.app')

@section('title', 'Add Initial Stock')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Add Initial Stock</h1>
            <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        @if($productsWithoutInventory->isEmpty())
                            <div class="alert alert-info">
                                All products already have inventory records. You can update them from the inventory list.
                            </div>
                        @else
                            <form action="{{ route('inventory.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="product_id" class="form-label">Product</label>
                                    <select name="product_id" id="product_id"
                                        class="form-control select2 @error('product_id') is-invalid @enderror" required>
                                        <option value="">Select Product</option>
                                        @foreach($productsWithoutInventory as $product)
                                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="quantity" class="form-label">Initial Quantity</label>
                                        <input type="number" name="quantity" id="quantity"
                                            class="form-control @error('quantity') is-invalid @enderror"
                                            value="{{ old('quantity', 0) }}" required min="0">
                                        @error('quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="is_discounted" class="form-label">Is Discounted?</label>
                                        <select name="is_discounted" id="is_discounted"
                                            class="form-control @error('is_discounted') is-invalid @enderror" required>
                                            <option value="0" {{ old('is_discounted') == '0' ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ old('is_discounted') == '1' ? 'selected' : '' }}>Yes</option>
                                        </select>
                                        @error('is_discounted')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="purchase_price" class="form-label">Purchase Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rs.</span>
                                            <input type="number" step="0.01" name="purchase_price" id="purchase_price"
                                                class="form-control @error('purchase_price') is-invalid @enderror"
                                                value="{{ old('purchase_price') }}" required min="0">
                                            @error('purchase_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="sale_price" class="form-label">Sale Price</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rs.</span>
                                            <input type="number" step="0.01" name="sale_price" id="sale_price"
                                                class="form-control @error('sale_price') is-invalid @enderror"
                                                value="{{ old('sale_price') }}" required min="0">
                                            @error('sale_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid mt-3">
                                    <button type="submit" class="btn btn-primary btn-lg">Save Inventory</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
