@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<style>
    .product-page,
    .product-page * {
        color: #000 !important;
    }

    .product-page .form-control {
        color: #000 !important;
    }

    .product-page .form-control::placeholder {
        color: #555 !important;
        opacity: 1;
    }
</style>

<div class="container-fluid product-page">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1">Edit Product</h1>
            <p class="small mb-0">Update product information and status.</p>
        </div>

        <a href="{{ route('products.index') }}"
           class="btn btn-secondary btn-sm shadow-sm mt-3 mt-sm-0">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Products
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2 pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-8 col-lg-10">

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Product Information
                    </h6>

                    <span class="badge badge-secondary">
                        ID: {{ $product->id }}
                    </span>
                </div>

                <div class="card-body">
                    <form action="{{ route('products.update', $product) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">
                                Product Name <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $product->name) }}"
                                   maxlength="255"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="brand">Brand</label>

                                <input type="text"
                                       id="brand"
                                       name="brand"
                                       class="form-control @error('brand') is-invalid @enderror"
                                       value="{{ old('brand', $product->brand) }}"
                                       maxlength="100">

                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="door_type">Door Type</label>

                                <input type="text"
                                       id="door_type"
                                       name="door_type"
                                       class="form-control @error('door_type') is-invalid @enderror"
                                       value="{{ old('door_type', $product->door_type) }}"
                                       maxlength="100">

                                @error('door_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="category">Category</label>

                                <input type="text"
                                       id="category"
                                       name="category"
                                       class="form-control @error('category') is-invalid @enderror"
                                       value="{{ old('category', $product->category) }}"
                                       maxlength="100">

                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="capacity">Capacity</label>

                                <input type="text"
                                       id="capacity"
                                       name="capacity"
                                       class="form-control @error('capacity') is-invalid @enderror"
                                       value="{{ old('capacity', $product->capacity) }}"
                                       maxlength="100">

                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="is_active"
                                       name="is_active"
                                       value="1"
                                       {{ old('is_active', $product->is_active) ? 'checked' : '' }}>

                                <label class="custom-control-label" for="is_active">
                                    Active Product
                                </label>
                            </div>

                            <small class="form-text">
                                Uncheck this option to deactivate the product.
                            </small>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('products.index') }}"
                               class="btn btn-light border mr-2">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="col-xl-4 col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Related Data
                    </h6>
                </div>

                <div class="card-body">
                    <div class="small font-weight-bold text-uppercase mb-1">
                        Sales Records
                    </div>

                    <div class="h4 font-weight-bold mb-3">
                        {{ $product->salesRecords()->count() }}
                    </div>

                    <p class="small mb-0">
                        Updating this product will not remove its associated sales records.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
