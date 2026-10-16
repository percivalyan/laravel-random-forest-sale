@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<style>
    .product-page,
    .product-page * {
        color: #000 !important;
    }
</style>

<div class="container-fluid product-page">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1">Product Details</h1>
            <p class="small mb-0">
                View product specifications and associated sales records.
            </p>
        </div>

        <div class="mt-3 mt-sm-0">
            <a href="{{ route('products.index') }}"
               class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i>
                Back
            </a>

            <a href="{{ route('products.edit', $product) }}"
               class="btn btn-warning btn-sm shadow-sm">
                <i class="fas fa-edit mr-1"></i>
                Edit Product
            </a>
        </div>
    </div>

    {{-- Product Information --}}
    <div class="row">

        <div class="col-xl-5 col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-box mr-2"></i>
                        Product Information
                    </h6>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-box-open fa-4x mb-3"></i>

                        <h5 class="font-weight-bold">
                            {{ $product->name }}
                        </h5>

                        @if ($product->is_active)
                            <span class="badge badge-success px-3 py-2">Active</span>
                        @else
                            <span class="badge badge-secondary px-3 py-2">Inactive</span>
                        @endif
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Product ID</span>
                        <strong>{{ $product->id }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Brand</span>
                        <strong>{{ $product->brand ?: '—' }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Door Type</span>
                        <strong>{{ $product->door_type ?: '—' }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Category</span>
                        <strong>{{ $product->category ?: '—' }}</strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Capacity</span>
                        <strong>{{ $product->capacity ?: '—' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sales Summary --}}
        <div class="col-xl-7 col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-line mr-2"></i>
                        Sales Summary
                    </h6>
                </div>

                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="small font-weight-bold text-uppercase mb-1">
                                Total Sales Records
                            </div>

                            <div class="h3 mb-2 font-weight-bold">
                                {{ $product->salesRecords->count() }}
                            </div>

                            <p class="small mb-0">
                                Number of sales records associated with this product.
                            </p>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-database fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Sales Records --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-table mr-2"></i>
                Associated Sales Records
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">No.</th>
                            <th>Country</th>
                            <th>Sales Record ID</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($product->salesRecords as $record)
                            <tr>
                                <td class="text-center align-middle">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="align-middle">
                                    {{ $record->country->name ?? 'Country unavailable' }}
                                </td>

                                <td class="align-middle">
                                    {{ $record->id }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                                    <p class="mb-0">
                                        No sales records are associated with this product.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
