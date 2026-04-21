<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>SIOKAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .nav-link {
            transition: 0.3s;
            border-radius: 6px;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.3);
            font-weight: bold;
        }

        .nav-link.active.bg-white {
            color: #0d6efd !important;
        }

        .app-sidebar {
            width: 250px;
            min-width: 250px;
            max-width: 250px;
            flex: 0 0 250px;
            min-height: 100vh;
        }

        .app-content {
            flex: 1 1 auto;
            min-width: 0;
        }
    </style>
</head>

<body>

    @php
        $user = auth()->user();
        $userInitial = $user?->name ? strtoupper(mb_substr($user->name, 0, 1)) : 'U';
    @endphp

    <div class="d-flex">
        <div class="app-sidebar bg-primary text-white p-3">
            <h4 class="text-center fw-bold">SIOKAS</h4>
            <hr class="text-white">

            <ul class="nav flex-column">
                <li class="nav-item mb-1">
                    <a href="{{ route('pembina.dashboard') }}"
                        class="nav-link {{ request()->routeIs('pembina.dashboard') ? 'active bg-white' : 'text-white' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('pembina.organisasi') }}"
                        class="nav-link {{ request()->routeIs('pembina.organisasi') ? 'active bg-white' : 'text-white' }}">
                        <i class="bi bi-building me-2"></i> Data Organisasi
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('pembina.kegiatan') }}"
                        class="nav-link {{ request()->routeIs('pembina.kegiatan') ? 'active bg-white' : 'text-white' }}">
                        <i class="bi bi-calendar-event me-2"></i> Data Kegiatan
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('pembina.validasi') }}"
                        class="nav-link {{ request()->routeIs('pembina.validasi') ? 'active bg-white' : 'text-white' }}">
                        <i class="bi bi-check-circle me-2"></i> Validasi Kegiatan
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('pembina.jadwal') }}"
                        class="nav-link {{ request()->routeIs('pembina.jadwal') ? 'active bg-white' : 'text-white' }}">
                        <i class="bi bi-calendar2-week me-2"></i> Jadwal Kegiatan
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('pembina.dokumentasi') }}"
                        class="nav-link {{ request()->routeIs('pembina.dokumentasi') ? 'active bg-white' : 'text-white' }}">
                        <i class="bi bi-images me-2"></i> Dokumentasi
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('pembina.laporan') }}"
                        class="nav-link {{ request()->routeIs('pembina.laporan') ? 'active bg-white' : 'text-white' }}">
                        <i class="bi bi-journal-text me-2"></i> Laporan Kegiatan
                    </a>
                </li>
            </ul>

            <hr class="text-white">

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-light text-primary fw-bold w-100">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </button>
            </form>
        </div>

        <div class="app-content bg-light">
            <div class="d-flex justify-content-between align-items-center bg-white p-3 shadow-sm">
                <h5 class="mb-0">@yield('title', 'Dashboard')</h5>

                <div class="d-flex align-items-center">
                    <div class="text-end me-2">
                        <div class="fw-semibold">{{ $user?->name ?? 'Tamu' }}</div>
                        <small class="text-muted text-uppercase">{{ $user?->role ?? '-' }}</small>
                    </div>
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                        style="width:40px; height:40px;">
                        {{ $userInitial }}
                    </div>
                </div>
            </div>

            <div class="p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @yield('scripts')
</body>

</html>
