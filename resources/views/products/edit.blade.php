@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit Product</h1>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('products.update', $product->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="generic_name" class="form-label">Generic Name</label>
                                    <input type="text" name="generic_name" id="generic_name"
                                        class="form-control @error('generic_name') is-invalid @enderror"
                                        value="{{ old('generic_name', $product->generic_name) }}">
                                    @error('generic_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select name="category_id" id="category_id"
                                        class="form-control select2 @error('category_id') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ (old('category_id') ?? $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="sub_category_id" class="form-label">Sub-Category</label>
                                    <select name="sub_category_id" id="sub_category_id"
                                        class="form-control select2 @error('sub_category_id') is-invalid @enderror"
                                        required>
                                        <option value="">Select Sub-Category</option>
                                        @foreach($subCategories as $subCategory)
                                            <option value="{{ $subCategory->id }}" {{ (old('sub_category_id') ?? $product->sub_category_id) == $subCategory->id ? 'selected' : '' }}>
                                                {{ $subCategory->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sub_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="brand_id" class="form-label">Brand</label>
                                    <select name="brand_id" id="brand_id"
                                        class="form-control select2 @error('brand_id') is-invalid @enderror" required>
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" {{ (old('brand_id') ?? $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="distributor_id" class="form-label">Distributor</label>
                                    <select name="distributor_id" id="distributor_id"
                                        class="form-control select2 @error('distributor_id') is-invalid @enderror" required>
                                        <option value="">Select Distributor</option>
                                        @foreach($distributors as $distributor)
                                            <option value="{{ $distributor->id }}" {{ (old('distributor_id') ?? $product->distributor_id) == $distributor->id ? 'selected' : '' }}>
                                                {{ $distributor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('distributor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid mt-3">
                                <button type="submit" class="btn btn-primary btn-lg">Update Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Handle Category selection change
            $('#category_id').on('change', function () {
                var categoryId = $(this).val();
                var subCategorySelect = $('#sub_category_id');
                var currentSubCategoryId = "{{ old('sub_category_id') ?? $product->sub_category_id }}";

                if (categoryId) {
                    $.ajax({
                        url: "{{ route('products.get-sub-categories') }}",
                        type: "GET",
                        data: { category_id: categoryId },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            subCategorySelect.empty();
                            subCategorySelect.append('<option value="">Select Sub-Category</option>');
                            $.each(data, function (key, value) {
                                var selected = (value.id == currentSubCategoryId) ? 'selected' : '';
                                subCategorySelect.append('<option value="' + value.id + '" ' + selected + '>' + value.name + '</option>');
                            });
                            subCategorySelect.trigger('change');
                        }
                    });
                } else {
                    subCategorySelect.empty();
                    subCategorySelect.append('<option value="">Select Category First</option>');
                    subCategorySelect.trigger('change');
                }
            });
        });
    </script>
@endsection
