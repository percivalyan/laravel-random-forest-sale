
@extends('layouts.app')

@section('title', 'Sales Record Details')

@section('content')
<style>
    .sales-page, .sales-page * { color: #000 !important; }
    .sales-page .btn-primary, .sales-page .btn-secondary,
    .sales-page .btn-warning { color: #fff !important; }
    .sales-page .detail-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 5px;
    }
    .sales-page .detail-value {
        font-size: 15px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }
    .sales-page .detail-item {
        padding: 13px 0;
        border-bottom: 1px solid #e3e6f0;
    }
    .sales-page .detail-item:last-child { border-bottom: 0; }
    .sales-page .result-card { min-height: 125px; }
    .sales-page .status-badge {
        display: inline-block;
        padding: .45rem .7rem;
        border-radius: .25rem;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .sales-page .status-profit { background: #d1e7dd; }
    .sales-page .status-loss { background: #f8d7da; }
    .sales-page .status-break-even { background: #fff3cd; }
</style>

<div class="container-fluid sales-page">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1">Sales Record Details</h1>
            <p class="small mb-0">
                Detailed sales information and financial calculation results.
            </p>
        </div>

        <div class="mt-3 mt-sm-0">
            <a href="{{ route('sales-records.index') }}"
               class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back
            </a>

            <a href="{{ route('sales-records.edit', $salesRecord) }}"
               class="btn btn-warning btn-sm shadow-sm">
                <i class="fas fa-edit mr-1"></i>Edit Record
            </a>
        </div>
    </div>

    {{-- Header information --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-file-invoice-dollar mr-2"></i>
                Record #{{ $salesRecord->id }}
            </h6>

            @if ($salesRecord->financial_status === 'profit')
                <span class="status-badge status-profit">Profit</span>
            @elseif ($salesRecord->financial_status === 'loss')
                <span class="status-badge status-loss">Loss</span>
            @elseif ($salesRecord->financial_status === 'break_even')
                <span class="status-badge status-break-even">Break Even</span>
            @else
                <span class="badge badge-secondary">Not Calculated</span>
            @endif
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="detail-label">Month</div>
                    <div class="detail-value">
                        {{ $salesRecord->month
                            ? \Carbon\Carbon::parse($salesRecord->month)->format('F Y')
                            : '—' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="detail-label">Country</div>
                    <div class="detail-value">
                        {{ $salesRecord->country?->name ?? '—' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="detail-label">Product</div>
                    <div class="detail-value">
                        {{ $salesRecord->product?->name ?? '—' }}
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="detail-label">Currency</div>
                    <div class="detail-value">{{ $salesRecord->currency ?: '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Financial summary --}}
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 result-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Revenue</div>
                            <div class="h5 mb-0 font-weight-bold">
                                ${{ number_format((float) $salesRecord->revenue_usd, 2) }}
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-dollar-sign fa-2x"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 result-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Cost</div>
                            <div class="h5 mb-0 font-weight-bold">
                                ${{ number_format((float) $salesRecord->total_cost_usd, 2) }}
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-money-bill-wave fa-2x"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 result-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Profit</div>
                            <div class="h5 mb-0 font-weight-bold">
                                ${{ number_format((float) $salesRecord->profit_usd, 2) }}
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-chart-line fa-2x"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2 result-card">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Loss</div>
                            <div class="h5 mb-0 font-weight-bold">
                                ${{ number_format((float) $salesRecord->loss_usd, 2) }}
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-chart-area fa-2x"></i></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        {{-- Input data --}}
        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-database mr-2"></i>Sales Input Data
                    </h6>
                </div>

                <div class="card-body">
                    <div class="detail-item">
                        <div class="detail-label">Estimated Average Price</div>
                        <div class="detail-value">
                            ${{ number_format((float) $salesRecord->estimated_avg_price_usd, 2) }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Price Data Type</div>
                        <div class="detail-value">
                            {{ $salesRecord->price_data_type ?: '—' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Sales Result</div>
                        <div class="detail-value">
                            {{ number_format((int) $salesRecord->sales_result) }} units
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Product Result</div>
                        <div class="detail-value">
                            {{ number_format((int) $salesRecord->product_result) }} units
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Cost Price per Unit</div>
                        <div class="detail-value">
                            ${{ number_format((float) $salesRecord->cost_price_usd, 2) }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Other Costs</div>
                        <div class="detail-value">
                            ${{ number_format((float) $salesRecord->other_costs_usd, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Calculated data --}}
        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-calculator mr-2"></i>Calculated Financial Data
                    </h6>
                </div>

                <div class="card-body">
                    <div class="detail-item">
                        <div class="detail-label">Cost of Goods Sold (COGS)</div>
                        <div class="detail-value">
                            ${{ number_format((float) $salesRecord->cogs_usd, 2) }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Total Cost</div>
                        <div class="detail-value">
                            ${{ number_format((float) $salesRecord->total_cost_usd, 2) }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Sales Difference</div>
                        <div class="detail-value">
                            {{ number_format((int) $salesRecord->sales_difference_units) }} units
                            @if ($salesRecord->sales_difference_percent !== null)
                                ({{ number_format((float) $salesRecord->sales_difference_percent, 2) }}%)
                            @endif
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Profit Margin</div>
                        <div class="detail-value">
                            {{ $salesRecord->profit_margin_percent !== null
                                ? number_format((float) $salesRecord->profit_margin_percent, 2) . '%'
                                : '—' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Price Difference from Previous Record</div>
                        <div class="detail-value">
                            {{ $salesRecord->price_difference_usd !== null
                                ? '$' . number_format((float) $salesRecord->price_difference_usd, 2)
                                : 'No previous price' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Price Change</div>
                        <div class="detail-value">
                            {{ $salesRecord->price_change_percent !== null
                                ? number_format((float) $salesRecord->price_change_percent, 2) . '%'
                                : '—' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Financial Status</div>
                        <div class="detail-value">
                            {{ $salesRecord->financial_status
                                ? ucwords(str_replace('_', ' ', $salesRecord->financial_status))
                                : 'Not calculated' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Actions --}}
    <div class="card shadow mb-4">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
            <div class="small mb-2 mb-md-0">
                <strong>Record ID:</strong> {{ $salesRecord->id }}
            </div>

            <div>
                <form action="{{ route('sales-records.destroy', $salesRecord) }}"
                      method="POST" class="d-inline"
                      onsubmit="return confirm('Are you sure you want to delete this sales record?')">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash-alt mr-1"></i>Delete Record
                    </button>
                </form>

                <a href="{{ route('sales-records.edit', $salesRecord) }}"
                   class="btn btn-warning btn-sm">
                    <i class="fas fa-edit mr-1"></i>Edit Record
                </a>

                <a href="{{ route('sales-records.index') }}"
                   class="btn btn-secondary btn-sm">
                    Back to List
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
