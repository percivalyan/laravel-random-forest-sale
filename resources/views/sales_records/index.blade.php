
@extends('layouts.app')

@section('title', 'Sales Records Management')

@section('content')
<style>
    .sales-page,
    .sales-page *,
    .sales-page *::before,
    .sales-page *::after {
        color: #000 !important;
    }

    .sales-page .text-white,
    .sales-page .btn-primary,
    .sales-page .btn-info,
    .sales-page .btn-danger,
    .sales-page .btn-success,
    .sales-page .btn-secondary,
    .sales-page .btn-warning {
        color: #fff !important;
    }

    .sales-page .btn-light,
    .sales-page .btn-outline-secondary {
        color: #000 !important;
    }

    .sales-page .form-control,
    .sales-page .custom-select {
        color: #000 !important;
    }

    .sales-page .form-control::placeholder {
        color: #555 !important;
    }

    .sales-page .table {
        margin-bottom: 0;
    }

    .sales-page .table th {
        white-space: nowrap;
        vertical-align: middle;
    }

    .sales-page .table td {
        vertical-align: middle;
    }

    .sales-page .summary-card {
        min-height: 105px;
    }

    .sales-page .summary-value {
        font-size: 1.25rem;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .sales-page .status-badge {
        display: inline-block;
        padding: .4rem .65rem;
        border-radius: .25rem;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .sales-page .status-profit {
        background: #d1e7dd;
    }

    .sales-page .status-loss {
        background: #f8d7da;
    }

    .sales-page .status-break-even {
        background: #fff3cd;
    }

    .sales-page .table-responsive {
        min-height: 120px;
    }

    /* Pagination */
    .sales-page .pagination-wrapper {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 20px;
    }

    .sales-page .pagination-info {
        font-size: .875rem;
        line-height: 1.5;
    }

    .sales-page .sales-pagination {
        max-width: 100%;
        min-width: 0;
    }

    .sales-page .sales-pagination nav {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .sales-page .sales-pagination .pagination {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-bottom: 0;
    }

    .sales-page .sales-pagination .page-item {
        margin: 0;
    }

    .sales-page .sales-pagination .page-link {
        color: #000 !important;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: .4rem .7rem;
        line-height: 1.5;
        min-width: 36px;
        text-align: center;
        box-shadow: none;
    }

    .sales-page .sales-pagination .page-link:hover {
        color: #000 !important;
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    .sales-page .sales-pagination .page-item.active .page-link {
        color: #000 !important;
        background-color: #e9ecef;
        border-color: #adb5bd;
        font-weight: 700;
        z-index: 1;
    }

    .sales-page .sales-pagination .page-item.disabled .page-link {
        color: #777 !important;
        background-color: #f8f9fa;
        border-color: #dee2e6;
        pointer-events: none;
    }

    .sales-page .sales-pagination .page-link:focus {
        box-shadow: 0 0 0 .15rem rgba(0, 0, 0, .1);
    }

    @media (min-width: 768px) {
        .sales-page .pagination-wrapper {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
    }

    @media (max-width: 767.98px) {
        .sales-page .sales-pagination nav {
            justify-content: flex-start;
        }

        .sales-page .sales-pagination .pagination {
            gap: 2px;
        }

        .sales-page .sales-pagination .page-link {
            padding: .35rem .55rem;
            min-width: 32px;
        }
    }
</style>

<div class="container-fluid sales-page">

    {{-- Page Heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1">Sales Records Management</h1>
            <p class="small mb-0">
                Manage monthly sales data and financial performance.
            </p>
        </div>

        <div class="mt-3 mt-sm-0 d-flex flex-wrap">
            <form
                action="{{ route('sales-records.recalculate') }}"
                method="POST"
                class="mr-2 mb-2 mb-sm-0"
                onsubmit="return confirm('Recalculate all financial records?')"
            >
                @csrf

                <button type="submit" class="btn btn-warning btn-sm shadow-sm">
                    <i class="fas fa-calculator fa-sm mr-1"></i>
                    Recalculate
                </button>
            </form>

            <a
                href="{{ route('sales-records.create') }}"
                class="btn btn-primary btn-sm shadow-sm mb-2 mb-sm-0"
            >
                <i class="fas fa-plus fa-sm mr-1"></i>
                Add Sales Record
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Error Message --}}
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
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 summary-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Total Records
                            </div>
                            <div class="summary-value">
                                {{ number_format((int) ($summary['total_records'] ?? 0)) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-database fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 summary-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Total Revenue
                            </div>
                            <div class="summary-value">
                                ${{ number_format((float) ($summary['revenue'] ?? 0), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2 summary-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Total Profit
                            </div>
                            <div class="summary-value">
                                ${{ number_format((float) ($summary['profit'] ?? 0), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 summary-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Total Loss
                            </div>
                            <div class="summary-value">
                                ${{ number_format((float) ($summary['loss'] ?? 0), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-area fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-filter mr-2"></i>
                Filter Sales Records
            </h6>
        </div>

        <div class="card-body">
            <form action="{{ route('sales-records.index') }}" method="GET">
                <div class="form-row align-items-end">
                    <div class="form-group col-md-3">
                        <label for="country_id">Country</label>

                        <select name="country_id" id="country_id" class="form-control">
                            <option value="">All Countries</option>

                            @foreach ($countries as $country)
                                <option
                                    value="{{ $country->id }}"
                                    {{ (string) request('country_id') === (string) $country->id ? 'selected' : '' }}
                                >
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="product_id">Product</label>

                        <select name="product_id" id="product_id" class="form-control">
                            <option value="">All Products</option>

                            @foreach ($products as $product)
                                <option
                                    value="{{ $product->id }}"
                                    {{ (string) request('product_id') === (string) $product->id ? 'selected' : '' }}
                                >
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label for="start_month">Start Month</label>

                        <input
                            type="month"
                            name="start_month"
                            id="start_month"
                            class="form-control"
                            value="{{ request('start_month') }}"
                        >
                    </div>

                    <div class="form-group col-md-2">
                        <label for="end_month">End Month</label>

                        <input
                            type="month"
                            name="end_month"
                            id="end_month"
                            class="form-control"
                            value="{{ request('end_month') }}"
                        >
                    </div>

                    <div class="form-group col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm mb-1">
                            <i class="fas fa-search mr-1"></i>
                            Filter
                        </button>

                        <a
                            href="{{ route('sales-records.index') }}"
                            class="btn btn-light border btn-sm mb-1"
                        >
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Sales Table --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between flex-wrap">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-table mr-2"></i>
                Sales Records List
            </h6>

            <span class="badge badge-primary mt-2 mt-sm-0">
                {{ number_format($records->total()) }} total records
            </span>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Month</th>
                            <th>Country</th>
                            <th>Product</th>
                            <th>Currency</th>
                            <th>Avg. Price (USD)</th>
                            <th>Sales Result</th>
                            <th>Product Result</th>
                            <th>Revenue (USD)</th>
                            <th>Total Cost (USD)</th>
                            <th>Profit (USD)</th>
                            <th>Loss (USD)</th>
                            <th>Financial Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($records as $record)
                            <tr>
                                <td class="text-center">
                                    {{ $records->firstItem() + $loop->index }}
                                </td>

                                <td class="text-nowrap">
                                    {{ \Carbon\Carbon::parse($record->month)->format('M Y') }}
                                </td>

                                <td>{{ $record->country?->name ?? '—' }}</td>
                                <td>{{ $record->product?->name ?? '—' }}</td>
                                <td>{{ $record->currency }}</td>

                                <td class="text-right">
                                    {{ number_format((float) $record->estimated_avg_price_usd, 2) }}
                                </td>

                                <td class="text-right">
                                    {{ number_format((int) $record->sales_result) }}
                                </td>

                                <td class="text-right">
                                    {{ number_format((int) $record->product_result) }}
                                </td>

                                <td class="text-right">
                                    {{ number_format((float) $record->revenue_usd, 2) }}
                                </td>

                                <td class="text-right">
                                    {{ number_format((float) $record->total_cost_usd, 2) }}
                                </td>

                                <td class="text-right">
                                    {{ number_format((float) $record->profit_usd, 2) }}
                                </td>

                                <td class="text-right">
                                    {{ number_format((float) $record->loss_usd, 2) }}
                                </td>

                                <td>
                                    @if ($record->financial_status === 'profit')
                                        <span class="status-badge status-profit">
                                            Profit
                                        </span>
                                    @elseif ($record->financial_status === 'loss')
                                        <span class="status-badge status-loss">
                                            Loss
                                        </span>
                                    @elseif ($record->financial_status === 'break_even')
                                        <span class="status-badge status-break-even">
                                            Break Even
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            Not Calculated
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center text-nowrap">
                                    <a
                                        href="{{ route('sales-records.show', $record) }}"
                                        class="btn btn-info btn-sm"
                                        title="View details"
                                        aria-label="View sales record details"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a
                                        href="{{ route('sales-records.edit', $record) }}"
                                        class="btn btn-warning btn-sm"
                                        title="Edit record"
                                        aria-label="Edit sales record"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form
                                        action="{{ route('sales-records.destroy', $record) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this sales record?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                            title="Delete record"
                                            aria-label="Delete sales record"
                                        >
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center py-5">
                                    <i class="fas fa-database fa-3x mb-3"></i>

                                    <p class="mb-3">
                                        No sales records found.
                                    </p>

                                    <a
                                        href="{{ route('sales-records.create') }}"
                                        class="btn btn-primary btn-sm"
                                    >
                                        <i class="fas fa-plus mr-1"></i>
                                        Add First Record
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($records->hasPages())
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Showing
                        <strong>{{ $records->firstItem() ?? 0 }}</strong>
                        to
                        <strong>{{ $records->lastItem() ?? 0 }}</strong>
                        of
                        <strong>{{ $records->total() }}</strong>
                        records
                    </div>

                    <div class="sales-pagination">
                        {{ $records->onEachSide(1)->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
