@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Edit Profile</h1>
            <p class="mb-0 text-muted small">
                Manage your personal information and account password.
            </p>
        </div>
    </div>

    {{-- Success Message --}}
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

    {{-- Validation Summary --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm" role="alert">
            <div class="font-weight-bold mb-2">
                <i class="fas fa-exclamation-circle mr-2"></i>
                Please correct the following errors:
            </div>

            <ul class="mb-0 pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">

        {{-- Profile Information --}}
        <div class="col-xl-8 col-lg-10">

            <div class="card shadow mb-4">

                <div class="card-header py-3 d-flex align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-circle mr-2"></i>
                        Profile Information
                    </h6>
                </div>

                <div class="card-body">

                    {{-- Account Information --}}
                    <div class="d-flex align-items-center mb-4">
                        <div class="mr-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                 style="width: 55px; height: 55px; font-size: 22px;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>

                        <div>
                            <div class="font-weight-bold text-gray-800">
                                {{ $user->name }}
                            </div>
                            <div class="text-muted small">
                                {{ $user->email }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div class="form-group">
                            <label for="name">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}"
                                placeholder="Masukkan nama lengkap"
                                autocomplete="name"
                                maxlength="255"
                                required>

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email">
                                Alamat Email <span class="text-danger">*</span>
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}"
                                placeholder="nama@example.com"
                                autocomplete="email"
                                maxlength="255"
                                required>

                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- New Password --}}
                        <div class="form-group">
                            <label for="password">Password Baru</label>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Kosongkan jika tidak ingin mengubah password"
                                autocomplete="new-password">

                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="form-text text-muted">
                                Isi hanya jika ingin mengganti password akun Anda.
                            </small>
                        </div>

                        {{-- Password Confirmation --}}
                        <div class="form-group">
                            <label for="password_confirmation">
                                Konfirmasi Password Baru
                            </label>

                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Ulangi password baru"
                                autocomplete="new-password">
                        </div>

                        <hr>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-light border mr-2">
                                <i class="fas fa-undo mr-1"></i>
                                Reset
                            </button>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>

        {{-- Security Information --}}
        <div class="col-xl-4 col-lg-10">

            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Keamanan Akun
                    </h6>
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <i class="fas fa-lock text-success mr-2"></i>
                        <strong>Jaga Password</strong>
                        <p class="text-muted small mt-1 mb-0">
                            Gunakan password yang kuat dan jangan membagikannya kepada orang lain.
                        </p>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <i class="fas fa-key text-primary mr-2"></i>
                        <strong>Ganti Password</strong>
                        <p class="text-muted small mt-1 mb-0">
                            Password hanya akan diperbarui jika Anda mengisi kolom password baru.
                        </p>
                    </div>

                    <hr>

                    <div>
                        <i class="fas fa-envelope text-warning mr-2"></i>
                        <strong>Alamat Email</strong>
                        <p class="text-muted small mt-1 mb-0">
                            Pastikan email yang digunakan benar dan dapat diakses.
                        </p>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>
@endsection