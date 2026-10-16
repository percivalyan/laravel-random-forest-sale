@extends('layouts.app')

@section('title', 'Country Management')

@section('content')


<style>
    /* Semua teks menjadi hitam */
    .container-fluid,
    .container-fluid h1,
    .container-fluid h2,
    .container-fluid h3,
    .container-fluid h4,
    .container-fluid h5,
    .container-fluid h6,
    .container-fluid p,
    .container-fluid span,
    .container-fluid label,
    .container-fluid th,
    .container-fluid td,
    .container-fluid small,
    .container-fluid strong,
    .container-fluid div,
    .container-fluid a,
    .container-fluid .text-primary,
    .container-fluid .text-secondary,
    .container-fluid .text-muted,
    .container-fluid .text-gray-800,
    .container-fluid .text-gray-700,
    .container-fluid .text-gray-500,
    .container-fluid .text-gray-400,
    .container-fluid .text-gray-300 {
        color: #000 !important;
    }

    /* Ikon juga hitam */
    .container-fluid i,
    .container-fluid svg {
        color: #000 !important;
    }

    /* Tulisan tombol */
    .container-fluid .btn {
        color: #000 !important;
    }

    /* Tulisan badge */
    .container-fluid .badge {
        color: #000 !important;
    }

    /* Tulisan input dan dropdown */
    .container-fluid input,
    .container-fluid select,
    .container-fluid textarea {
        color: #000 !important;
    }

    /* Placeholder input */
    .container-fluid input::placeholder,
    .container-fluid textarea::placeholder {
        color: #000 !important;
        opacity: 0.7;
    }
</style>

<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Country Management</h1>
            <p class="mb-0 text-muted small">
                Manage countries used in sales records and product analysis.
            </p>
        </div>

        <a href="{{ route('countries.create') }}"
           class="btn btn-primary btn-sm shadow-sm mt-3 mt-sm-0">
            <i class="fas fa-plus fa-sm mr-2"></i>
            Add New Country
        </a>
    </div>

    {{-- Alerts --}}
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

    {{-- Summary --}}
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Countries
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $countries->total() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-globe fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Countries Table --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-2"></i>
                Country List
            </h6>

            <span class="badge badge-primary">
                {{ $countries->count() }} shown
            </span>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" style="width: 65px;">No.</th>
                            <th>Country Name</th>
                            <th class="text-center" style="width: 140px;">Country Code</th>
                            <th class="text-center" style="width: 170px;">Sales Records</th>
                            <th class="text-center" style="width: 190px;">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($countries as $country)
                            <tr>
                                <td class="text-center align-middle">
                                    {{ $countries->firstItem() + $loop->index }}
                                </td>

                                <td class="align-middle font-weight-bold text-gray-800">
                                    <i class="fas fa-globe-americas text-gray-400 mr-2"></i>
                                    {{ $country->name }}
                                </td>

                                <td class="text-center align-middle">
                                    @if ($country->code)
                                        <span class="badge badge-info px-3 py-2">
                                            {{ strtoupper($country->code) }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td class="text-center align-middle">
                                    <span class="badge badge-secondary px-3 py-2">
                                        {{ $country->sales_records_count }}
                                    </span>
                                </td>

                                <td class="text-center align-middle">
                                    <a href="{{ route('countries.show', $country) }}"
                                       class="btn btn-info btn-sm"
                                       title="View details">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('countries.edit', $country) }}"
                                       class="btn btn-warning btn-sm"
                                       title="Edit country">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('countries.destroy', $country) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this country?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                title="Delete country"
                                                {{ $country->sales_records_count > 0 ? 'disabled' : '' }}>
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-globe fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-gray-500 mb-3">
                                        No countries have been registered yet.
                                    </p>
                                    <a href="{{ route('countries.create') }}"
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus mr-1"></i>
                                        Add First Country
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($countries->hasPages())
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3">
                    <div class="text-muted small mb-2 mb-md-0">
                        Showing {{ $countries->firstItem() }} to
                        {{ $countries->lastItem() }} of
                        {{ $countries->total() }} countries
                    </div>

                    {{ $countries->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection