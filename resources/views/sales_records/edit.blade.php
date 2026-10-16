
@extends('layouts.app')

@section('title', 'Edit Sales Record')

@section('content')
<style>
    .sales-page, .sales-page * { color: #000 !important; }
    .sales-page .btn-primary, .sales-page .btn-secondary { color: #fff !important; }
    .sales-page .form-control, .sales-page .custom-select { color: #000 !important; }
    .sales-page .form-control::placeholder { color: #555 !important; }
</style>

<div class="container-fluid sales-page">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1">Edit Sales Record</h1>
            <p class="small mb-0">Update monthly sales, pricing, and cost information.</p>
        </div>

        <a href="{{ route('sales-records.index') }}"
           class="btn btn-secondary btn-sm shadow-sm mt-3 mt-sm-0">
            <i class="fas fa-arrow-left mr-1"></i>Back to Sales Records
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
                        <i class="fas fa-edit mr-2"></i>Sales Record Information
                    </h6>
                    <span class="badge badge-secondary">ID: {{ $salesRecord->id }}</span>
                </div>

                <div class="card-body">
                    <form action="{{ route('sales-records.update', $salesRecord) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="country_id">Country <span class="text-danger">*</span></label>
                                <select name="country_id" id="country_id"
                                        class="custom-select @error('country_id') is-invalid @enderror" required>
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ (string) old('country_id', $salesRecord->country_id) === (string) $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="product_id">Product <span class="text-danger">*</span></label>
                                <select name="product_id" id="product_id"
                                        class="custom-select @error('product_id') is-invalid @enderror" required>
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ (string) old('product_id', $salesRecord->product_id) === (string) $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="month">Month <span class="text-danger">*</span></label>
                                <input type="date" name="month" id="month"
                                       class="form-control @error('month') is-invalid @enderror"
                                       value="{{ old('month', $salesRecord->month ? \Carbon\Carbon::parse($salesRecord->month)->format('Y-m-d') : '') }}"
                                       required>
                                <small class="form-text">Use the first day of the month.</small>
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="currency">Currency <span class="text-danger">*</span></label>
                                <input type="text" name="currency" id="currency"
                                       class="form-control @error('currency') is-invalid @enderror"
                                       value="{{ old('currency', $salesRecord->currency) }}"
                                       maxlength="3" minlength="3" required>
                                @error('currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <h6 class="font-weight-bold mb-3">Sales Information</h6>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="estimated_avg_price_usd">Estimated Average Price (USD) <span class="text-danger">*</span></label>
                                <input type="number" min="0" step="0.01"
                                       name="estimated_avg_price_usd" id="estimated_avg_price_usd"
                                       class="form-control @error('estimated_avg_price_usd') is-invalid @enderror"
                                       value="{{ old('estimated_avg_price_usd', $salesRecord->estimated_avg_price_usd) }}"
                                       required>
                                @error('estimated_avg_price_usd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="price_data_type">Price Data Type <span class="text-danger">*</span></label>
                                <input type="text" name="price_data_type" id="price_data_type"
                                       class="form-control @error('price_data_type') is-invalid @enderror"
                                       value="{{ old('price_data_type', $salesRecord->price_data_type) }}"
                                       maxlength="100" required>
                                @error('price_data_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="sales_result">Sales Result (Units) <span class="text-danger">*</span></label>
                                <input type="number" min="0" step="1"
                                       name="sales_result" id="sales_result"
                                       class="form-control @error('sales_result') is-invalid @enderror"
                                       value="{{ old('sales_result', $salesRecord->sales_result) }}" required>
                                @error('sales_result')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="product_result">Product Result (Units) <span class="text-danger">*</span></label>
                                <input type="number" min="0" step="1"
                                       name="product_result" id="product_result"
                                       class="form-control @error('product_result') is-invalid @enderror"
                                       value="{{ old('product_result', $salesRecord->product_result) }}" required>
                                @error('product_result')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <h6 class="font-weight-bold mb-3">Cost Information</h6>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="cost_price_usd">Cost Price per Unit (USD) <span class="text-danger">*</span></label>
                                <input type="number" min="0" step="0.01"
                                       name="cost_price_usd" id="cost_price_usd"
                                       class="form-control @error('cost_price_usd') is-invalid @enderror"
                                       value="{{ old('cost_price_usd', $salesRecord->cost_price_usd) }}"
                                       required>
                                @error('cost_price_usd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="other_costs_usd">Other Costs (USD) <span class="text-danger">*</span></label>
                                <input type="number" min="0" step="0.01"
                                       name="other_costs_usd" id="other_costs_usd"
                                       class="form-control @error('other_costs_usd') is-invalid @enderror"
                                       value="{{ old('other_costs_usd', $salesRecord->other_costs_usd) }}"
                                       required>
                                @error('other_costs_usd')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle mr-1"></i>
                            Financial calculations will be refreshed automatically after updating.
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('sales-records.index') }}"
                               class="btn btn-light border mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>Update Sales Record
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
                        <i class="fas fa-chart-line mr-2"></i>Current Financial Result
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="small font-weight-bold text-uppercase">Revenue</div>
                        <div class="h5 font-weight-bold">
                            ${{ number_format((float) $salesRecord->revenue_usd, 2) }}
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="small font-weight-bold text-uppercase">Total Cost</div>
                        <div class="h5 font-weight-bold">
                            ${{ number_format((float) $salesRecord->total_cost_usd, 2) }}
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="small font-weight-bold text-uppercase">Profit</div>
                        <div class="h5 font-weight-bold">
                            ${{ number_format((float) $salesRecord->profit_usd, 2) }}
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="small font-weight-bold text-uppercase">Loss</div>
                        <div class="h5 font-weight-bold">
                            ${{ number_format((float) $salesRecord->loss_usd, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
