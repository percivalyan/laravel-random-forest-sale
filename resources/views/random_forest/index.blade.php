@extends('layouts.app')

@section('title', 'Sales Analysis & Random Forest')

@section('content')
<style>
    .analysis-page,
    .analysis-page * {
        color: #000 !important;
    }

    .analysis-page .form-control {
        color: #000 !important;
    }

    .analysis-page .table th,
    .analysis-page .table td {
        vertical-align: middle;
    }

    .analysis-page .result-output {
        white-space: pre-wrap;
        overflow-wrap: anywhere;
        font-size: 13px;
        background: #f8f9fc;
        border: 1px solid #d1d3e2;
        border-radius: 4px;
        padding: 15px;
        max-height: 450px;
        overflow-y: auto;
    }

    .analysis-page .metric-label {
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .analysis-page .action-group {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .analysis-page .status-badge {
        display: inline-block;
        padding: 5px 9px;
        border: 1px solid #777;
        border-radius: 4px;
        font-size: 11px;
        font-weight: bold;
    }

    .analysis-page .btn:disabled {
        cursor: not-allowed;
        opacity: .65;
    }
</style>

<div class="container-fluid analysis-page">

    {{-- Page Heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1">Sales Analysis & Random Forest</h1>
            <p class="small mb-0">
                Financial calculations, model training, evaluation, and predictions.
            </p>
        </div>

        <a href="{{ route('sales-records.index') }}"
           class="btn btn-secondary btn-sm shadow-sm mt-3 mt-sm-0">
            <i class="fas fa-table mr-1"></i>
            Sales Records
        </a>
    </div>

    {{-- Session Notifications --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm"
             role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}

            <button type="button" class="close"
                    data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm"
             role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}

            <button type="button" class="close"
                    data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Financial Summary --}}
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="metric-label mb-1">Total Records</div>
                    <div class="h5 mb-0 font-weight-bold">
                        {{ number_format($summary['total_records']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="metric-label mb-1">Total Revenue</div>
                    <div class="h5 mb-0 font-weight-bold">
                        ${{ number_format($summary['revenue'], 2) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="metric-label mb-1">Total Profit</div>
                    <div class="h5 mb-0 font-weight-bold">
                        ${{ number_format($summary['profit'], 2) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="metric-label mb-1">Total Loss</div>
                    <div class="h5 mb-0 font-weight-bold">
                        ${{ number_format($summary['loss'], 2) }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Break-even and Training Data --}}
    <div class="row">

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="metric-label mb-1">Break-even Records</div>
                    <div class="h4 mb-0 font-weight-bold">
                        {{ number_format($summary['break_even']) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body">
                    <div class="metric-label mb-1">Eligible Training Records</div>
                    <div class="h4 mb-0 font-weight-bold">
                        {{ number_format($summary['training_records']) }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Actions --}}
    <div class="row">

        {{-- Recalculation --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-calculator mr-2"></i>
                        Financial Calculation
                    </h6>
                </div>

                <div class="card-body">
                    <p>
                        Recalculate all sales records in chronological order.
                        The system updates revenue, COGS, total cost, profit,
                        loss, margin, sales differences, and price changes.
                    </p>

                    <p>
                        <span class="status-badge">
                            {{ number_format($summary['total_records']) }} records
                        </span>
                    </p>

                    <button type="button"
                            id="recalculateButton"
                            class="btn btn-primary">
                        <i class="fas fa-sync-alt mr-1"></i>
                        Recalculate All Records
                    </button>
                </div>
            </div>
        </div>

        {{-- Random Forest --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-brain mr-2"></i>
                        Random Forest Model
                    </h6>
                </div>

                <div class="card-body">
                    <p>
                        Train a Random Forest classifier to classify the financial
                        status of a sales record.
                    </p>

                    <p>
                        <span class="status-badge" id="modelStatus">
                            Model: {{ $modelExists ? 'Available' : 'Not trained' }}
                        </span>

                        <span class="status-badge">
                            Training records: {{ $summary['training_records'] }}
                        </span>
                    </p>

                    <button type="button"
                            id="trainButton"
                            class="btn btn-success">
                        <i class="fas fa-play mr-1"></i>
                        Train & Evaluate Model
                    </button>

                    <p class="small mt-3 mb-0">
                        Training requires at least 20 observations and at least
                        two target classes.
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- AJAX Result --}}
    <div class="card shadow mb-4" id="resultCard" hidden>
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold" id="resultTitle">
                Process Results
            </h6>

            <button type="button"
                    class="btn btn-sm btn-light border"
                    id="closeResultButton">
                Close
            </button>
        </div>

        <div class="card-body">
            <div id="resultMessage" class="mb-3"></div>
            <pre id="resultOutput" class="result-output mb-0"></pre>
        </div>
    </div>

    {{-- Sales Records --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">
                <i class="fas fa-chart-line mr-2"></i>
                Sales Records & Predictions
            </h6>
        </div>

        <div class="card-body">

            <div class="alert alert-info">
                The prediction is generated by the saved model.
                It does not modify the actual financial status in the database.
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover"
                       width="100%" cellspacing="0">

                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Month</th>
                            <th>Country</th>
                            <th>Product</th>
                            <th>Revenue (USD)</th>
                            <th>Profit (USD)</th>
                            <th>Loss (USD)</th>
                            <th>Actual Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($records as $record)
                            <tr>
                                <td class="text-center">
                                    {{ $records->firstItem() + $loop->index }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($record->month)->format('M Y') }}
                                </td>

                                <td>
                                    {{ $record->country->name ?? '—' }}
                                </td>

                                <td>
                                    {{ $record->product->name ?? '—' }}
                                </td>

                                <td>
                                    ${{ number_format((float) $record->revenue_usd, 2) }}
                                </td>

                                <td>
                                    ${{ number_format((float) $record->profit_usd, 2) }}
                                </td>

                                <td>
                                    ${{ number_format((float) $record->loss_usd, 2) }}
                                </td>

                                <td>
                                    {{ ucfirst(str_replace('_', ' ', $record->financial_status ?? 'unknown')) }}
                                </td>

                                <td class="text-center">
                                    <button type="button"
                                            class="btn btn-outline-primary btn-sm predict-button"
                                            data-url="{{ route('random-forest.predict', $record) }}"
                                            {{ $modelExists ? '' : 'disabled' }}>
                                        <i class="fas fa-brain mr-1"></i>
                                        Predict
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                                    <p class="mb-0">
                                        No sales records available.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            @if ($records->hasPages())
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3">
                    <div class="small mb-2 mb-md-0">
                        Showing {{ $records->firstItem() }} to
                        {{ $records->lastItem() }} of
                        {{ $records->total() }} records
                    </div>

                    {{ $records->links() }}
                </div>
            @endif

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = @json(csrf_token());

    const resultCard = document.getElementById('resultCard');
    const resultTitle = document.getElementById('resultTitle');
    const resultMessage = document.getElementById('resultMessage');
    const resultOutput = document.getElementById('resultOutput');
    const modelStatus = document.getElementById('modelStatus');

    function showResult(title, message, data, isError = false) {
        resultTitle.textContent = title;
        resultMessage.textContent = message;

        resultMessage.className = isError
            ? 'mb-3 text-danger'
            : 'mb-3 text-success';

        resultOutput.textContent = JSON.stringify(data, null, 2);
        resultCard.hidden = false;

        resultCard.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    async function sendPost(url, button) {
        const originalContent = button.innerHTML;

        button.disabled = true;
        button.innerHTML =
            '<i class="fas fa-spinner fa-spin mr-1"></i> Processing...';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            });

            let data;

            try {
                data = await response.json();
            } catch (error) {
                throw new Error(
                    'Server did not return valid JSON. Check Laravel logs.'
                );
            }

            if (!response.ok) {
                showResult(
                    'Process Failed',
                    data.message || 'The request could not be completed.',
                    data,
                    true
                );

                return;
            }

            showResult(
                'Process Completed',
                data.message || 'The request completed successfully.',
                data
            );

            if (url.includes('/train')) {
                modelStatus.textContent = data.model_saved
                    ? 'Model: Available'
                    : 'Model: Not saved';

                if (data.model_saved) {
                    document.querySelectorAll('.predict-button').forEach(
                        function (predictButton) {
                            predictButton.disabled = false;
                        }
                    );
                }
            }

            if (url.includes('/recalculate')) {
                window.setTimeout(function () {
                    window.location.reload();
                }, 1800);
            }
        } catch (error) {
            showResult(
                'Request Error',
                'Unable to communicate with the server.',
                { error: error.message },
                true
            );
        } finally {
            button.disabled = false;
            button.innerHTML = originalContent;
        }
    }

    document.getElementById('trainButton').addEventListener(
        'click',
        function () {
            if (!confirm('Start Random Forest training and evaluation?')) {
                return;
            }

            sendPost(
                @json(route('random-forest.train')),
                this
            );
        }
    );

    document.getElementById('recalculateButton').addEventListener(
        'click',
        function () {
            if (!confirm(
                'Recalculate all sales records? This updates calculated financial fields.'
            )) {
                return;
            }

            sendPost(
                @json(route('sales-records.recalculate')),
                this
            );
        }
    );

    document.querySelectorAll('.predict-button').forEach(
        function (button) {
            button.addEventListener('click', function () {
                sendPost(this.dataset.url, this);
            });
        }
    );

    document.getElementById('closeResultButton').addEventListener(
        'click',
        function () {
            resultCard.hidden = true;
        }
    );
});
</script>
@endsection