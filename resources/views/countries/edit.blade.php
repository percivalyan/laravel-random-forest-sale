@extends('layouts.app')

@section('title', 'Edit Country')

@section('content')
<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Edit Country</h1>
            <p class="mb-0 text-muted small">
                Update the selected country's information.
            </p>
        </div>

        <a href="{{ route('countries.index') }}"
           class="btn btn-secondary btn-sm shadow-sm mt-3 mt-sm-0">
            <i class="fas fa-arrow-left fa-sm mr-2"></i>
            Back to Countries
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <strong><i class="fas fa-exclamation-circle mr-2"></i>Please correct the following errors:</strong>
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

                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Country Information
                    </h6>

                    <span class="badge badge-secondary">
                        ID: {{ $country->id }}
                    </span>
                </div>

                <div class="card-body">
                    <form action="{{ route('countries.update', $country) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">
                                Country Name <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $country->name) }}"
                                   maxlength="255"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="code">Country Code</label>

                            <input type="text"
                                   id="code"
                                   name="code"
                                   class="form-control text-uppercase @error('code') is-invalid @enderror"
                                   value="{{ old('code', $country->code) }}"
                                   minlength="3"
                                   maxlength="3"
                                   pattern="[A-Za-z]{3}"
                                   title="Enter exactly three letters.">

                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="form-text text-muted">
                                Leave blank if this country does not use a code in your dataset.
                            </small>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('countries.index') }}"
                               class="btn btn-light border mr-2">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                Update Country
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Related Data
                    </h6>
                </div>

                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Sales Records
                    </div>

                    <div class="h4 mb-3 font-weight-bold text-gray-800">
                        {{ $country->salesRecords()->count() }}
                    </div>

                    <p class="text-muted small mb-0">
                        Sales records associated with this country will remain linked to it after the name or code is updated.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection