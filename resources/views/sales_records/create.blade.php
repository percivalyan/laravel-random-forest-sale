
@extends('layouts.app')

@section('title', 'Add Sales Record')

@section('content')
@php
    $role = auth()->user()->role ?? 'user';

    $canViewSalesRecords = in_array($role, ['admin', 'petugas'], true);

    $backRoute = $canViewSalesRecords
        ? route('sales-records.index')
        : route('dashboard.redirect');

    $storeRoute = route('sales-records.store');
@endphp

<style>
    .sales-page,
    .sales-page * {
        color: #000 !important;
    }

    .sales-page .form-control,
    .sales-page .custom-select,
    .sales-page input,
    .sales-page select,
    .sales-page textarea {
        color: #000 !important;
        background-color: #fff;
    }

    .sales-page .form-control::placeholder {
        color: #555 !important;
        opacity: 1;
    }

    .sales-page .btn,
    .sales-page .btn *,
    .sales-page .alert,
    .sales-page .alert *,
    .sales-page .invalid-feedback,
    .sales-page .invalid-feedback * {
        color: #000 !important;
    }

    .sales-page .table th,
    .sales-page .table td {
        color: #000 !important;
    }

    .sales-page .card {
        border-radius: .35rem;
    }

    .sales-page .card-header {
        background-color: #f8f9fc;
    }

    .sales-page label {
        font-weight: 600;
    }

    .sales-page .required-mark {
        font-weight: 700;
    }
</style>

<div class="container-fluid sales-page">

    {{-- Page Heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1">Add Sales Record</h1>
            <p class="small mb-0">
                Register monthly sales and cost data for a product.
            </p>
        </div>

        <a href="{{ $backRoute }}"
           class="btn btn-light border btn-sm shadow-sm mt-3 mt-sm-0">
            <i class="fas fa-arrow-left mr-1"></i>
            {{ $canViewSalesRecords ? 'Back to Sales Records' : 'Back to Dashboard' }}
        </a>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm"
             role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="close"
                    data-dismiss="alert"
                    aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm" role="alert">
            <strong>Please correct the following errors:</strong>

            <ul class="mb-0 mt-2 pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">

        {{-- Main Form --}}
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>
                        Sales Record Information
                    </h6>
                </div>

                <div class="card-body">

                    <form action="{{ $storeRoute }}" method="POST">
                        @csrf

                        {{-- Country and Product --}}
                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label for="country_id">
                                    Country <span class="required-mark">*</span>
                                </label>

                                <select name="country_id"
                                        id="country_id"
                                        class="custom-select @error('country_id') is-invalid @enderror"
                                        required>
                                    <option value="">Select Country</option>

                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ (string) old('country_id') === (string) $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('country_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="product_id">
                                    Product <span class="required-mark">*</span>
                                </label>

                                <select name="product_id"
                                        id="product_id"
                                        class="custom-select @error('product_id') is-invalid @enderror"
                                        required>
                                    <option value="">Select Product</option>

                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ (string) old('product_id') === (string) $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($products->isEmpty())
                                    <small class="form-text">
                                        No active products available. Please contact the administrator.
                                    </small>
                                @endif
                            </div>

                        </div>

                        {{-- Period and Currency --}}
                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label for="month">
                                    Month <span class="required-mark">*</span>
                                </label>

                                <input type="date"
                                       name="month"
                                       id="month"
                                       class="form-control @error('month') is-invalid @enderror"
                                       value="{{ old('month') }}"
                                       required>

                                <small class="form-text">
                                    Use the first day of the month, for example 2024-01-01.
                                </small>

                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="currency">
                                    Currency <span class="required-mark">*</span>
                                </label>

                                <input type="text"
                                       name="currency"
                                       id="currency"
                                       class="form-control text-uppercase @error('currency') is-invalid @enderror"
                                       value="{{ old('currency', 'USD') }}"
                                       maxlength="3"
                                       minlength="3"
                                       pattern="[A-Za-z]{3}"
                                       placeholder="USD"
                                       required>

                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <hr>

                        <h6 class="font-weight-bold mb-3">
                            Sales Information
                        </h6>

                        {{-- Price --}}
                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label for="estimated_avg_price_usd">
                                    Estimated Average Price (USD)
                                    <span class="required-mark">*</span>
                                </label>

                                <input type="number"
                                       step="0.01"
                                       min="0"
                                       name="estimated_avg_price_usd"
                                       id="estimated_avg_price_usd"
                                       class="form-control @error('estimated_avg_price_usd') is-invalid @enderror"
                                       value="{{ old('estimated_avg_price_usd') }}"
                                       placeholder="e.g. 650.00"
                                       required>

                                @error('estimated_avg_price_usd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="price_data_type">
                                    Price Data Type
                                    <span class="required-mark">*</span>
                                </label>

                                <input type="text"
                                       name="price_data_type"
                                       id="price_data_type"
                                       class="form-control @error('price_data_type') is-invalid @enderror"
                                       value="{{ old('price_data_type', 'Estimated Market Price') }}"
                                       maxlength="100"
                                       placeholder="Estimated Market Price"
                                       required>

                                @error('price_data_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        {{-- Sales Quantities --}}
                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label for="sales_result">
                                    Sales Result (Units)
                                    <span class="required-mark">*</span>
                                </label>

                                <input type="number"
                                       min="0"
                                       step="1"
                                       name="sales_result"
                                       id="sales_result"
                                       class="form-control @error('sales_result') is-invalid @enderror"
                                       value="{{ old('sales_result', 0) }}"
                                       required>

                                @error('sales_result')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="product_result">
                                    Product Result (Units)
                                    <span class="required-mark">*</span>
                                </label>

                                <input type="number"
                                       min="0"
                                       step="1"
                                       name="product_result"
                                       id="product_result"
                                       class="form-control @error('product_result') is-invalid @enderror"
                                       value="{{ old('product_result', 0) }}"
                                       required>

                                @error('product_result')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <hr>

                        <h6 class="font-weight-bold mb-3">
                            Cost Information
                        </h6>

                        {{-- Costs --}}
                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label for="cost_price_usd">
                                    Cost Price per Unit (USD)
                                    <span class="required-mark">*</span>
                                </label>

                                <input type="number"
                                       min="0"
                                       step="0.01"
                                       name="cost_price_usd"
                                       id="cost_price_usd"
                                       class="form-control @error('cost_price_usd') is-invalid @enderror"
                                       value="{{ old('cost_price_usd', 0) }}"
                                       required>

                                @error('cost_price_usd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="other_costs_usd">
                                    Other Costs (USD)
                                    <span class="required-mark">*</span>
                                </label>

                                <input type="number"
                                       min="0"
                                       step="0.01"
                                       name="other_costs_usd"
                                       id="other_costs_usd"
                                       class="form-control @error('other_costs_usd') is-invalid @enderror"
                                       value="{{ old('other_costs_usd', 0) }}"
                                       required>

                                @error('other_costs_usd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle mr-1"></i>
                            Revenue, total costs, profit, loss, margin, and financial
                            status will be calculated automatically after saving.
                        </div>

                        <hr>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end flex-wrap">

                            <a href="{{ $backRoute }}"
                               class="btn btn-light border mr-2 mb-2">
                                Cancel
                            </a>

                            <button type="submit"
                                    class="btn btn-primary mb-2"
                                    {{ $products->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-save mr-1"></i>
                                Save Sales Record
                            </button>

                        </div>

                    </form>

                </div>
            </div>
        </div>

        {{-- Calculation Information --}}
        <div class="col-xl-4 col-lg-10">

            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-calculator mr-2"></i>
                        Calculation Rules
                    </h6>
                </div>

                <div class="card-body">

                    <p>
                        <strong>Revenue</strong><br>
                        Sales Result × Average Price
                    </p>

                    <hr>

                    <p>
                        <strong>COGS</strong><br>
                        Sales Result × Cost Price per Unit
                    </p>

                    <hr>

                    <p>
                        <strong>Total Cost</strong><br>
                        COGS + Other Costs
                    </p>

                    <hr>

                    <p class="mb-0">
                        <strong>Net Result</strong><br>
                        Revenue − Total Cost
                    </p>

                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-body">
                    <h6 class="font-weight-bold">
                        <i class="fas fa-info-circle mr-2"></i>
                        Important Information
                    </h6>

                    <p class="small mb-0">
                        Ensure that the selected country, product, reporting month,
                        sales quantity, and cost values are correct before saving.
                        Duplicate records for the same country, product, and month
                        are not allowed.
                    </p>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
