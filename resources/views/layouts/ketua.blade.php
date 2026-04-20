<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>SIOKAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
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
    </style>
</head>

<body>

    @php
        $user = auth()->user();
        $userInitial = $user?->name ? strtoupper(mb_substr($user->name, 0, 1)) : 'U';
    @endphp

    <div class="d-flex">

        <!-- SIDEBAR -->
        <div class="bg-primary text-white p-3" style="width:250px; min-height:100vh;">

            <h4 class="text-center fw-bold">SIOKAS</h4>
            <hr class="text-white">

            <ul class="nav flex-column">

                <li class="nav-item mb-1">
                    <a href="{{ route('ketua.dashboard') }}"
                        class="nav-link {{ request()->routeIs('ketua.dashboard') ? 'active bg-white' : 'text-white' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('ketua.organisasi') }}"
                        class="nav-link {{ request()->routeIs('ketua.organisasi') ? 'active bg-white' : 'text-white' }}">
                        <i class="bi bi-building me-2"></i> Profile Organisasi
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('ketua.kegiatan') }}"
                        class="nav-link {{ request()->routeIs('ketua.kegiatan') ? 'active bg-white' : 'text-white' }}">
                        <i class="bi bi-calendar-event me-2"></i> Manajemen Kegiatan
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('ketua.jadwal') }}"
                        class="nav-link {{ request()->routeIs('ketua.jadwal') ? 'active bg-white' : 'text-white' }}">
                        <i class="bi bi-calendar2-week me-2"></i> Jadwal Kegiatan
                    </a>
                </li>

                <li class="nav-item mb-1">
                    <a href="{{ route('ketua.dokumentasi') }}"
                        class="nav-link {{ request()->routeIs('ketua.dokumentasi') ? 'active bg-white' : 'text-white' }}">
                        <i class="bi bi-images me-2"></i> Dokumentasi
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

        <!-- MAIN CONTENT -->
        <div class="flex-grow-1 bg-light">

            <!-- TOPBAR -->
            <div class="d-flex justify-content-between align-items-center bg-white p-3 shadow-sm">

                <!-- JUDUL -->
                <h5 class="mb-0">
                    @yield('title', 'Dashboard')
                </h5>

                <!-- PROFIL -->
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

            <!-- ISI HALAMAN -->
            <div class="p-4">
                @yield('content')
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
