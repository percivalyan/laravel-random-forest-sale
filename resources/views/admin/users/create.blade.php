@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Create New User</h1>
            <p class="mb-0 text-muted small">
                Create a new account and assign its access role.
            </p>
        </div>

        <a href="{{ route('admin.users.index') }}"
           class="btn btn-secondary btn-sm shadow-sm mt-3 mt-sm-0">
            <i class="fas fa-arrow-left fa-sm mr-2"></i>
            Back to Users
        </a>
    </div>

    {{-- Validation Summary --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <div class="font-weight-bold mb-2">
                <i class="fas fa-exclamation-circle mr-2"></i>
                Please check the following errors:
            </div>

            <ul class="mb-0 pl-4">
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
                        <i class="fas fa-user-plus mr-2"></i>
                        User Information
                    </h6>
                </div>

                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}"
                          method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="name">
                                Full Name <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Enter full name"
                                autocomplete="name"
                                maxlength="255"
                                required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">
                                Email Address <span class="text-danger">*</span>
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                placeholder="name@example.com"
                                autocomplete="email"
                                maxlength="255"
                                required>

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="password">
                                    Password <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter password"
                                    autocomplete="new-password"
                                    required>

                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="password_confirmation">
                                    Confirm Password
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="form-control"
                                    placeholder="Repeat password"
                                    autocomplete="new-password"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="role">
                                User Role <span class="text-danger">*</span>
                            </label>

                            <select
                                id="role"
                                name="role"
                                class="form-control @error('role') is-invalid @enderror"
                                required>

                                <option value="">-- Select Role --</option>

                                <option value="admin"
                                    {{ old('role') === 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>

                                <option value="petugas"
                                    {{ old('role') === 'petugas' ? 'selected' : '' }}>
                                    Petugas
                                </option>

                                <option value="user"
                                    {{ old('role') === 'user' ? 'selected' : '' }}>
                                    User
                                </option>
                            </select>

                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="form-text text-muted">
                                Select the role that determines the user's access level.
                            </small>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.users.index') }}"
                               class="btn btn-light border mr-2">
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                Create User
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
