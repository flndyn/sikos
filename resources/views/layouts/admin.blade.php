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
            background-color: rgba(255,255,255,0.2);
        }

        .nav-link.active {
            background-color: rgba(255,255,255,0.3);
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="d-flex">

    <!-- SIDEBAR -->
    <div class="bg-primary text-white p-3" style="width:250px; min-height:100vh;">
        
        <h4 class="text-center fw-bold">SIOKAS</h4>
        <hr class="text-white">

        <ul class="nav flex-column">

            <li class="nav-item mb-1">
                <a href="/admin/dashboard" class="nav-link text-white active">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            
            <li class="nav-item mb-1">
                <a href="/admin/users" class="nav-link text-white">
                    <i class="bi bi-people me-2"></i> Data User
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="/admin/organisasi" class="nav-link text-white">
                    <i class="bi bi-building me-2"></i> Organisasi
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="/admin/kegiatan" class="nav-link text-white">
                    <i class="bi bi-calendar-event me-2"></i> Kegiatan
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="/admin/validasi" class="nav-link text-white">
                    <i class="bi bi-check-circle me-2"></i> Validasi
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="/admin/dokumentasi" class="nav-link text-white">
                    <i class="bi bi-images me-2"></i> Dokumentasi
                </a>
            </li>

            <li class="nav-item mb-1">
                <a href="/admin/laporan" class="nav-link text-white">
                    <i class="bi bi-images me-2"></i> Laporan
                </a>
            </li>

        </ul>

        <hr class="text-white">

        <a href="#" class="btn btn-light text-primary fw-bold w-100">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
        </a>

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
                <span class="me-2 fw-semibold">Nama User</span>
                <img src="https://via.placeholder.com/40" class="rounded-circle">
            </div>

        </div>

        <!-- ISI HALAMAN -->
        <div class="p-4">
            @yield('content')
        </div>

    </div>

</div>

</body>
</html>