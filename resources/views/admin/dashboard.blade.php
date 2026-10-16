
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Admin Dashboard</h1>
            <p class="mb-0">
                Ringkasan dan pengelolaan sistem.
            </p>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger shadow-sm">
                <i class="fas fa-sign-out-alt fa-sm mr-2"></i>
                Logout
            </button>
        </form>
    </div>

    {{-- Welcome Card --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h5 class="font-weight-bold mb-2">
                        Selamat datang, {{ Auth::user()->name }}!
                    </h5>

                    <p class="mb-2">
                        Anda masuk sebagai administrator dan memiliki akses
                        untuk mengelola data serta memantau analisis penjualan.
                    </p>

                    <small>
                        Email: {{ Auth::user()->email }}
                    </small>
                </div>

                <div class="col-md-3 text-md-right mt-3 mt-md-0">
                    <i class="fas fa-user-shield fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics --}}
    @php
        $totalUsers = \App\Models\User::count();

        $totalCountries = \Illuminate\Support\Facades\Schema::hasTable('countries')
            ? \App\Models\Country::count()
            : 0;

        $totalProducts = \Illuminate\Support\Facades\Schema::hasTable('products')
            ? \App\Models\Product::count()
            : 0;

        $totalSalesRecords = \Illuminate\Support\Facades\Schema::hasTable('sales_records')
            ? \App\Models\SalesRecord::count()
            : 0;
    @endphp

    <div class="row">

        {{-- Total Users --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Total Users
                            </div>

                            <div class="h5 mb-0 font-weight-bold">
                                {{ number_format($totalUsers) }}
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Countries --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Total Countries
                            </div>

                            <div class="h5 mb-0 font-weight-bold">
                                {{ number_format($totalCountries) }}
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-globe fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Products --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Total Products
                            </div>

                            <div class="h5 mb-0 font-weight-bold">
                                {{ number_format($totalProducts) }}
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-box fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Sales Records --}}
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                Sales Records
                            </div>

                            <div class="h5 mb-0 font-weight-bold">
                                {{ number_format($totalSalesRecords) }}
                            </div>
                        </div>

                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Management Menu --}}
    <div class="row">

        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        Master Data
                    </h6>
                </div>

                <div class="card-body">
                    <p>
                        Kelola data negara dan produk yang digunakan dalam
                        pencatatan penjualan.
                    </p>

                    <div class="d-flex flex-wrap">
                        <a href="{{ route('countries.index') }}"
                           class="btn btn-outline-dark mr-2 mb-2">
                            <i class="fas fa-globe mr-1"></i>
                            Manage Countries
                        </a>

                        <a href="{{ route('products.index') }}"
                           class="btn btn-outline-dark mb-2">
                            <i class="fas fa-box mr-1"></i>
                            Manage Products
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        Sales & Analysis
                    </h6>
                </div>

                <div class="card-body">
                    <p>
                        Kelola catatan penjualan dan akses dashboard analisis
                        serta prediksi menggunakan Random Forest.
                    </p>

                    <div class="d-flex flex-wrap">
                        <a href="{{ route('sales-records.index') }}"
                           class="btn btn-outline-dark mr-2 mb-2">
                            <i class="fas fa-file-invoice-dollar mr-1"></i>
                            Sales Records
                        </a>

                        <a href="{{ route('random-forest.index') }}"
                           class="btn btn-outline-dark mb-2">
                            <i class="fas fa-chart-bar mr-1"></i>
                            Sales Analysis
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        User Management
                    </h6>
                </div>

                <div class="card-body">
                    <p>
                        Lihat dan kelola akun pengguna sistem.
                    </p>

                    <a href="{{ route('admin.users.index') }}"
                       class="btn btn-outline-dark">
                        <i class="fas fa-users-cog mr-1"></i>
                        Manage Users
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold">
                        Account Settings
                    </h6>
                </div>

                <div class="card-body">
                    <p>
                        Periksa atau perbarui informasi profil akun Anda.
                    </p>

                    <a href="{{ route('profile.edit') }}"
                       class="btn btn-outline-dark">
                        <i class="fas fa-user-edit mr-1"></i>
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

{{-- Keep dashboard text black --}}
<style>
    #content,
    #content *,
    .container-fluid,
    .container-fluid * {
        color: #000 !important;
    }

    .container-fluid .btn-danger,
    .container-fluid .btn-danger * {
        color: #fff !important;
    }

    .container-fluid .card {
        border-radius: 0.35rem;
    }

    .container-fluid .card-header {
        background-color: #f8f9fc;
    }

    .container-fluid .btn {
        transition: background-color 0.15s ease-in-out;
    }
</style>
@endsection
