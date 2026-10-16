@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <style>
        /* Force all text to black */
        .user-page,
        .user-page *,
        .user-page *::before,
        .user-page *::after {
            color: #000 !important;
        }

        /* Form controls */
        .user-page input,
        .user-page select,
        .user-page textarea,
        .user-page option,
        .user-page input::placeholder,
        .user-page textarea::placeholder {
            color: #000 !important;
        }

        /* Buttons: text and icons remain black */
        .user-page .btn,
        .user-page .btn *,
        .user-page button,
        .user-page button *,
        .user-page a.btn,
        .user-page a.btn *,
        .user-page .page-link,
        .user-page .page-link * {
            color: #000 !important;
        }

        /* Badges */
        .user-page .badge,
        .user-page .badge * {
            color: #000 !important;
        }

        /* Alerts */
        .user-page .alert,
        .user-page .alert *,
        .user-page .alert .close {
            color: #000 !important;
        }

        /* Table */
        .user-page table,
        .user-page th,
        .user-page td,
        .user-page thead,
        .user-page tbody {
            color: #000 !important;
        }

        /* Links and headings */
        .user-page a,
        .user-page a *,
        .user-page h1,
        .user-page h2,
        .user-page h3,
        .user-page h4,
        .user-page h5,
        .user-page h6,
        .user-page p,
        .user-page small,
        .user-page label,
        .user-page strong,
        .user-page span,
        .user-page i {
            color: #000 !important;
        }

        /*
         * User avatar:
         * The initial and the user's name are separate elements.
         * Explicit dimensions, inline-flex, and spacing prevent them
         * from appearing concatenated (for example, "AAdmin").
         */
        .user-page .user-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 36px;
            width: 36px;
            height: 36px;
            margin-right: 12px;
            border-radius: 50%;
            background-color: #e8eef7;
            color: #243b53 !important;
            font-size: 0.875rem;
            font-weight: 700;
            line-height: 1;
            text-align: center;
        }

        .user-page .user-page-name {
            min-width: 0;
            overflow-wrap: anywhere;
        }

        /* Disabled controls */
        .user-page .btn:disabled,
        .user-page .btn.disabled {
            color: #000 !important;
            opacity: 0.65;
        }
    </style>

    <div class="container-fluid user-page">

        {{-- Page Heading --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <div>
                <h1 class="h3 mb-1">User Management</h1>
                <p class="mb-0 small">
                    Manage user accounts, email addresses, and access roles.
                </p>
            </div>

            <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm shadow-sm mt-3 mt-sm-0">
                <i class="fas fa-user-plus fa-sm mr-2"></i>
                Add New User
            </a>
        </div>

        {{-- Flash Messages --}}
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

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <strong>Please check the following errors:</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Summary Cards --}}
        <div class="row">

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card shadow h-100 py-2 summary-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="small font-weight-bold text-uppercase mb-1">
                                    Total Users
                                </div>

                                <div class="summary-value">
                                    {{ method_exists($users, 'total') ? number_format($users->total()) : number_format($users->count()) }}
                                </div>
                            </div>

                            <div class="col-auto">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card shadow h-100 py-2 summary-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="small font-weight-bold text-uppercase mb-1">
                                    Administrators
                                </div>

                                <div class="summary-value">
                                    {{ \App\Models\User::where('role', 'admin')->count() }}
                                </div>
                            </div>

                            <div class="col-auto">
                                <i class="fas fa-user-shield fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card shadow h-100 py-2 summary-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="small font-weight-bold text-uppercase mb-1">
                                    Other Accounts
                                </div>

                                <div class="summary-value">
                                    {{ \App\Models\User::whereIn('role', ['petugas', 'user'])->count() }}
                                </div>
                            </div>

                            <div class="col-auto">
                                <i class="fas fa-user-friends fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- User List --}}
        <div class="card shadow mb-4">

            <div class="card-header py-3 d-flex align-items-center justify-content-between flex-wrap">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-users mr-2"></i>
                    User List
                </h6>

                <span class="badge badge-primary mt-2 mt-sm-0">
                    {{ method_exists($users, 'total') ? $users->total() : $users->count() }}
                    users
                </span>
            </div>

            <div class="card-body">

                {{-- Search --}}
                <form action="{{ route('admin.users.index') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-8 col-lg-6 mb-2">
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by name or email..." value="{{ request('search') }}">
                        </div>

                        <div class="col-md-auto mb-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i>
                                Search
                            </button>

                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo mr-1"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">

                        <thead class="thead-light">
                            <tr>
                                <th class="text-center" style="width: 65px;">
                                    No.
                                </th>

                                <th style="min-width: 190px;">
                                    Name
                                </th>

                                <th style="min-width: 220px;">
                                    Email Address
                                </th>

                                <th class="text-center" style="width: 130px;">
                                    Role
                                </th>

                                <th class="text-center" style="width: 190px;">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td class="text-center">
                                        {{ method_exists($users, 'firstItem') ? $users->firstItem() + $loop->index : $loop->iteration }}
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="user-avatar">
                                                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                            </span>

                                            <span class="font-weight-bold user-page-name">
                                                {{ $user->name }}
                                            </span>
                                        </div>
                                    </td>

                                    <td>
                                        {{ $user->email }}
                                    </td>

                                    <td class="text-center">
                                        @php
                                            $roleClass =
                                                [
                                                    'admin' => 'badge-danger',
                                                    'petugas' => 'badge-warning',
                                                    'user' => 'badge-info',
                                                ][$user->role] ?? 'badge-secondary';
                                        @endphp

                                        <span class="badge {{ $roleClass }} px-3 py-2">
                                            {{ ucfirst($user->role ?? 'Unknown') }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit user">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </a>

                                            @if (auth()->id() !== $user->id)
                                                <form action="{{ route('admin.users.destroy', $user->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        title="Delete user">
                                                        <i class="fas fa-trash-alt"></i>
                                                        Delete
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-secondary btn-sm" disabled
                                                    title="You cannot delete your own account">
                                                    <i class="fas fa-lock"></i>
                                                    Protected
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-users fa-3x mb-3 d-block"></i>

                                        <h6 class="font-weight-bold">
                                            No users found
                                        </h6>

                                        <p class="mb-3">
                                            No accounts match your search.
                                        </p>

                                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-user-plus mr-1"></i>
                                            Add New User
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                {{-- Pagination --}}
                @if (method_exists($users, 'links'))
                    <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">
                        <div class="small mb-2">
                            @if ($users->total() > 0)
                                Showing {{ $users->firstItem() }}–{{ $users->lastItem() }}
                                of {{ $users->total() }} users
                            @else
                                No users to display
                            @endif
                        </div>

                        <div class="mb-2">
                            {{ $users->withQueryString()->links() }}
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>
@endsection
