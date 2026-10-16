@extends('layouts.app')

@section('title', 'Add Country')

@section('content')
<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Add New Country</h1>
            <p class="mb-0 text-muted small">
                Register a country for your sales data.
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

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-globe mr-2"></i>
                        Country Information
                    </h6>
                </div>

                <div class="card-body">
                    <form action="{{ route('countries.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="name">
                                Country Name <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="e.g. Indonesia"
                                   maxlength="255"
                                   autocomplete="off"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="form-text text-muted">
                                Enter the full country name. The name must be unique.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="code">Country Code</label>

                            <input type="text"
                                   id="code"
                                   name="code"
                                   class="form-control text-uppercase @error('code') is-invalid @enderror"
                                   value="{{ old('code') }}"
                                   placeholder="e.g. IDN"
                                   minlength="3"
                                   maxlength="3"
                                   pattern="[A-Za-z]{3}"
                                   title="Enter exactly three letters."
                                   autocomplete="off">

                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <small class="form-text text-muted">
                                Optional. Enter a unique three-letter code, such as IDN or USA.
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
                                Save Country
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
                        <i class="fas fa-info-circle mr-2"></i>
                        Information
                    </h6>
                </div>

                <div class="card-body">
                    <p class="text-gray-700 mb-3">
                        Country data is used to associate sales records with their respective markets.
                    </p>

                    <div class="alert alert-info mb-0">
                        <i class="fas fa-lightbulb mr-2"></i>
                        <strong>Note:</strong> A country with existing sales records cannot be deleted.
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection