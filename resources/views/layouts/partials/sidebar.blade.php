<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">

                    {{-- ADMIN --}}
                    @if (Auth::check() && Auth::user()->role === 'admin')
                        <div class="sb-sidenav-menu-heading">Admin</div>

                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                           href="{{ route('admin.dashboard') }}">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            Dashboard
                        </a>

                        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                           href="{{ route('admin.users.index') }}">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-users-cog"></i>
                            </div>
                            Kelola Pengguna
                        </a>

                        <div class="sb-sidenav-menu-heading">Data Master</div>

                        <a class="nav-link {{ request()->routeIs('countries.*') ? 'active' : '' }}"
                           href="{{ route('countries.index') }}">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-globe-asia"></i>
                            </div>
                            Data Negara
                        </a>

                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                           href="{{ route('products.index') }}">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            Data Produk
                        </a>

                        <div class="sb-sidenav-menu-heading">Penjualan & Analisis</div>

                        <a class="nav-link {{ request()->routeIs('sales-records.*') ? 'active' : '' }}"
                           href="{{ route('sales-records.index') }}">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            Data Penjualan
                        </a>

                        <a class="nav-link {{ request()->routeIs('random-forest.*') ? 'active' : '' }}"
                           href="{{ route('random-forest.index') }}">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-brain"></i>
                            </div>
                            Sales Analysis & ML
                        </a>

                        <div class="sb-sidenav-menu-heading">Akun</div>

                        <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                           href="{{ route('profile.edit') }}">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-id-badge"></i>
                            </div>
                            Profil Saya
                        </a>
                    @endif

                    {{-- PETUGAS --}}
                    @if (Auth::check() && Auth::user()->role === 'petugas')
                        <div class="sb-sidenav-menu-heading">Petugas</div>

                        <a class="nav-link {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}"
                           href="{{ route('petugas.dashboard') }}">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            Dashboard
                        </a>

                        <div class="sb-sidenav-menu-heading">Akun</div>

                        <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                           href="{{ route('profile.edit') }}">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-id-badge"></i>
                            </div>
                            Profil Saya
                        </a>
                    @endif

                    {{-- USER --}}
                    @if (Auth::check() && Auth::user()->role === 'user')
                        <div class="sb-sidenav-menu-heading">User</div>

                        <a class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
                           href="{{ route('user.dashboard') }}">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            Dashboard
                        </a>

                        <div class="sb-sidenav-menu-heading">Akun</div>

                        <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
                           href="{{ route('profile.edit') }}">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-id-badge"></i>
                            </div>
                            Profil Saya
                        </a>
                    @endif

                </div>
            </div>

            {{-- FOOTER --}}
            <div class="sb-sidenav-footer">
                <div class="small">Login sebagai:</div>

                @auth
                    <strong>{{ Auth::user()->name }}</strong>
                    <div>{{ ucfirst(Auth::user()->role) }}</div>
                @else
                    <span>Guest</span>
                @endauth
            </div>
        </nav>
    </div>

    <div id="layoutSidenav_content">