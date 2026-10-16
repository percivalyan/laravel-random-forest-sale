@extends('layouts.app')

@section('title', 'Country Details')

@section('content')
<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Country Details</h1>
            <p class="mb-0 text-muted small">
                View country information and associated sales records.
            </p>
        </div>

        <div class="mt-3 mt-sm-0">
            <a href="{{ route('countries.index') }}"
               class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i>
                Back
            </a>

            <a href="{{ route('countries.edit', $country) }}"
               class="btn btn-warning btn-sm shadow-sm">
                <i class="fas fa-edit mr-1"></i>
                Edit Country
            </a>
        </div>
    </div>

    {{-- Country Information --}}
    <div class="row">

        <div class="col-xl-4 col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-globe mr-2"></i>
                        Country Information
                    </h6>
                </div>

                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-globe-americas fa-4x text-gray-300 mb-3"></i>
                        <h5 class="font-weight-bold text-gray-800">
                            {{ $country->name }}
                        </h5>

                        @if ($country->code)
                            <span class="badge badge-info px-3 py-2">
                                {{ strtoupper($country->code) }}
                            </span>
                        @else
                            <span class="badge badge-secondary">
                                No country code
                            </span>
                        @endif
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Country ID</span>
                        <strong>{{ $country->id }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Country Name</span>
                        <strong>{{ $country->name }}</strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Country Code</span>
                        <strong>{{ $country->code ? strtoupper($country->code) : '—' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sales Summary --}}
        <div class="col-xl-8 col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line mr-2"></i>
                        Sales Records
                    </h6>
                </div>

                <div class="card-body">
                    <div class="row no-gutters align-items-center mb-4">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Records
                            </div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">
                                {{ $country->salesRecords->count() }}
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-database fa-3x text-gray-300"></i>
                        </div>
                    </div>

                    <p class="text-muted small mb-0">
                        This section lists the sales records associated with {{ $country->name }}.
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Sales Records Table --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table mr-2"></i>
                Sales Record Details
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">No.</th>
                            <th>Product</th>
                            <th>Record Details</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($country->salesRecords as $record)
                            <tr>
                                <td class="text-center align-middle">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="align-middle">
                                    {{ $record->product->name ?? 'Product unavailable' }}
                                </td>

                                <td class="align-middle">
                                    <span class="text-muted small">
                                        Sales Record ID: {{ $record->id }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <i class="fas fa-folder-open fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-gray-500 mb-0">
                                        No sales records are associated with this country.
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
