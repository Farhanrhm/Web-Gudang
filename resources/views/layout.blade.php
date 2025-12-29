<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GudangApp - Manajemen Stok</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #ffffff !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .nav-link {
            color: #555;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: 0.3s;
        }
        .nav-link:hover {
            background-color: #f0f0f0;
            color: #0d6efd;
        }
        .nav-link.active {
            color: #0d6efd !important;
            background-color: #e7f1ff;
        }
        .main-content {
            min-height: 80vh;
            padding-top: 30px;
            padding-bottom: 50px;
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 12px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="{{ route('dashboard') }}">
            <i class="bi bi-box-seam-fill me-2"></i>GudangApp
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            {{-- MENU KIRI --}}
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}"
                       href="{{ route('dashboard') }}">
                        Dashboard
                    </a>
                </li>

                {{-- PRODUK --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active fw-bold' : '' }}"
                       href="{{ route('products.index') }}">
                        Produk
                    </a>
                </li>

                {{-- RIWAYAT TRANSAKSI --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active fw-bold' : '' }}"
                       href="{{ route('transactions.index') }}">
                        Riwayat Transaksi
                    </a>
                </li>

                {{-- MENU KHUSUS ADMIN --}}
                @if(Auth::user()->role === 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('categories.*') ? 'active fw-bold' : '' }}"
                           href="{{ route('categories.index') }}">
                            Kategori
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active fw-bold' : '' }}"
                           href="{{ route('users.index') }}">
                            <i class="bi bi-people-fill me-1"></i>Manajemen User
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active fw-bold' : '' }}"
                           href="{{ route('activity-logs.index') }}">
                            <i class="bi bi-clock-history me-1"></i>Activity Log
                        </a>
                    </li>
                @endif
            </ul>

            {{-- MENU KANAN --}}
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center"
                       href="#" role="button" data-bs-toggle="dropdown">

                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/'.Auth::user()->avatar) }}"
                                 class="rounded-circle me-2"
                                 style="width:30px;height:30px;object-fit:cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0d6efd&color=fff"
                                 class="rounded-circle me-2"
                                 style="width:30px;height:30px;">
                        @endif

                        <span>{{ Auth::user()->name }}</span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('profile') }}">
                                <i class="bi bi-person me-2"></i>Profil Saya
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</nav>

<div class="container main-content">
    @yield('content')
</div>

<footer class="bg-white border-top py-4 mt-auto">
    <div class="container text-center text-muted small">
        &copy; 2025 GudangApp - Sistem Manajemen Stok
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Inisialisasi Bootstrap tooltip untuk elemen dengan atribut data-bs-toggle="tooltip"
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>

</body>
</html>
