<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="Login to your account">
    <meta name="author" content="">

    <title>Login - SB Admin</title>

    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-primary">

    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">

                            <div class="card shadow-lg border-0 rounded-lg mt-5">

                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">
                                        Login
                                    </h3>
                                </div>

                                <div class="card-body">

                                    {{-- Pesan sukses --}}
                                    @if (session('success'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    {{-- Pesan error autentikasi --}}
                                    @if ($errors->any())
                                        <div class="alert alert-danger" role="alert">
                                            <ul class="mb-0 ps-3">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form
                                        method="POST"
                                        action="{{ route('login.process') }}"
                                    >
                                        @csrf

                                        {{-- Email --}}
                                        <div class="form-floating mb-3">
                                            <input
                                                class="form-control @error('email') is-invalid @enderror"
                                                id="inputEmail"
                                                type="email"
                                                name="email"
                                                placeholder="name@example.com"
                                                value="{{ old('email') }}"
                                                autocomplete="username"
                                                required
                                                autofocus
                                            >

                                            <label for="inputEmail">
                                                Email address
                                            </label>

                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        {{-- Password --}}
                                        <div class="form-floating mb-3">
                                            <input
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="inputPassword"
                                                type="password"
                                                name="password"
                                                placeholder="Password"
                                                autocomplete="current-password"
                                                required
                                            >

                                            <label for="inputPassword">
                                                Password
                                            </label>

                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        {{-- Remember me --}}
                                        <div class="form-check mb-3">
                                            <input
                                                class="form-check-input"
                                                id="remember"
                                                type="checkbox"
                                                name="remember"
                                                value="1"
                                                @checked(old('remember'))
                                            >

                                            <label
                                                class="form-check-label"
                                                for="remember"
                                            >
                                                Remember Me
                                            </label>
                                        </div>

                                        {{-- Submit --}}
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <button
                                                type="submit"
                                                class="btn btn-primary"
                                            >
                                                <i class="fas fa-sign-in-alt me-1"></i>
                                                Login
                                            </button>
                                        </div>
                                    </form>

                                </div>

                                <div class="card-footer text-center py-3">
                                    <div class="small">
                                        <a href="{{ route('register') }}">
                                            Need an account? Sign up!
                                        </a>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"
    ></script>

    <script src="{{ asset('js/scripts.js') }}"></script>

</body>
</html>
