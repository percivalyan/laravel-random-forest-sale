@extends('layouts.app')

@section('title', 'Product Management')

@section('content')
<style>
    .product-page,
    .product-page * {
        color: #000 !important;
    }

    .product-page .btn:focus {
        box-shadow: 0 0 0 .2rem rgba(0, 0, 0, .15);
    }

    .product-page .form-control {
        color: #000 !important;
    }
</style>

<div class="container-fluid product-page">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1">Product Management</h1>
            <p class="small mb-0">
                Manage product information and sales record associations.
            </p>
        </div>

        <a href="{{ route('products.create') }}"
           class="btn btn-primary btn-sm shadow-sm mt-3 mt-sm-0">
            <i class="fas fa-plus fa-sm mr-2"></i>
            Add New Product
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Summary Cards --}}
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Total Products
                            </div>
                            <div class="h5 mb-0 font-weight-bold">
                                {{ $products->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Product Table --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-boxes mr-2"></i>
                Product List
            </h6>

            <span class="badge badge-primary">
                {{ $products->count() }} shown
            </span>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width: 55px;">No.</th>
                            <th>Product Name</th>
                            <th>Brand</th>
                            <th>Door Type</th>
                            <th>Category</th>
                            <th>Capacity</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Sales Records</th>
                            <th class="text-center" style="width: 145px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td class="text-center align-middle">
                                    {{ $products->firstItem() + $loop->index }}
                                </td>

                                <td class="align-middle font-weight-bold">
                                    {{ $product->name }}
                                </td>

                                <td class="align-middle">
                                    {{ $product->brand ?: '—' }}
                                </td>

                                <td class="align-middle">
                                    {{ $product->door_type ?: '—' }}
                                </td>

                                <td class="align-middle">
                                    {{ $product->category ?: '—' }}
                                </td>

                                <td class="align-middle">
                                    {{ $product->capacity ?: '—' }}
                                </td>

                                <td class="text-center align-middle">
                                    @if ($product->is_active)
                                        <span class="badge badge-success px-3 py-2">
                                            Active
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-3 py-2">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center align-middle">
                                    <span class="badge badge-info px-3 py-2">
                                        {{ $product->sales_records_count }}
                                    </span>
                                </td>

                                <td class="text-center align-middle">
                                    <a href="{{ route('products.show', $product) }}"
                                       class="btn btn-info btn-sm"
                                       title="View details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('products.edit', $product) }}"
                                       class="btn btn-warning btn-sm"
                                       title="Edit product">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('products.destroy', $product) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                title="Delete product"
                                                {{ $product->sales_records_count > 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-box-open fa-3x mb-3"></i>
                                    <p class="mb-3">No products have been registered yet.</p>

                                    <a href="{{ route('products.create') }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus mr-1"></i>
                                        Add First Product
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($products->hasPages())
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3">
                    <div class="small mb-2 mb-md-0">
                        Showing {{ $products->firstItem() }} to
                        {{ $products->lastItem() }} of
                        {{ $products->total() }} products
                    </div>

                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection