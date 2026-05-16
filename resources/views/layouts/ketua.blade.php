<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>SIOKAS - @yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            height: 100vh;
            overflow: hidden;
        }

        html {
            height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .app-sidebar {
            width: 250px;
            min-width: 250px;
            max-width: 250px;
            height: 100vh;
            max-height: 100vh;
            overflow-y: auto;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            transition: transform 0.3s ease;
            z-index: 1040;
        }

        .app-sidebar h4 {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .nav-link {
            transition: 0.3s;
            border-radius: 8px;
            padding: 0.75rem 1rem !important;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            font-weight: 400;
        }

        .nav-link i {
            font-size: 1.2rem;
            min-width: 24px;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateX(5px);
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .nav-link.active.bg-white {
            color: #fff !important;
            background-color: rgba(59, 130, 246, 0.3) !important;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            gap: 0.75rem;
        }

        .sidebar-header .logo-icon {
            font-size: 2rem;
            color: #fff;
            flex-shrink: 0;
        }

        .sidebar-brand {
            text-align: left;
        }

        .sidebar-brand h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.1;
        }

        .sidebar-brand small {
            font-size: 0.65rem;
            font-weight: 400;
            opacity: 0.9;
            display: block;
            margin-top: 0.15rem;
            line-height: 1.2;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background-color: #f5f7fa;
            padding: 1rem 1.5rem !important;
            border-bottom: 1px solid #e9ecef;
            flex-shrink: 0;
        }

        .app-content {
            flex: 1 1 auto;
            min-width: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .app-content-body {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            padding: 2rem;
        }

        .topbar-left h5 {
            font-weight: 600;
            font-size: 1.05rem;
            margin: 0;
        }

        .topbar-left p {
            font-size: 0.85rem;
            color: #6b7280;
            margin: 0;
        }

        .btn-notification {
            position: relative;
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 8px;
            background-color: transparent;
            color: #6b7280;
            font-size: 1.2rem;
            transition: all 0.2s ease;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-notification:hover {
            color: #374151;
        }

        .badge-notification {
            font-size: 0.65rem;
        }

        .notification-menu {
            width: 360px;
            max-width: calc(100vw - 2rem);
        }

        .notification-item {
            white-space: normal;
        }

        .profile-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            padding: 0.35rem 0.5rem;
            border-radius: 12px;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .profile-link:hover {
            background-color: rgba(13, 110, 253, 0.06);
            transform: translateY(-1px);
            color: inherit;
        }

        .profile-link .profile-text {
            line-height: 1.1;
        }

        /* ===== SIDEBAR OVERLAY ===== */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 1039;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* ===== HAMBURGER ===== */
        .btn-hamburger {
            display: none;
            border: none;
            background: transparent;
            font-size: 1.4rem;
            color: #374151;
            padding: 4px 8px;
            border-radius: 6px;
            cursor: pointer;
            line-height: 1;
        }

        .btn-hamburger:hover {
            background: rgba(0, 0, 0, 0.06);
        }

        /* ===== MOBILE ===== */
        @media (max-width: 991.98px) {
            .app-wrapper {
                flex-direction: column !important;
            }

            .app-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                transform: translateX(-100%);
                min-height: 100%;
                overflow-y: auto;
            }

            .app-sidebar.open {
                transform: translateX(0);
            }

            .btn-hamburger {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .profile-name-text {
                display: none;
            }

            .topbar {
                padding: 0.75rem 1rem !important;
            }

            .app-content>.p-4 {
                padding: 1rem !important;
            }
        }

        @media (max-width: 575.98px) {
            .topbar-left h5 {
                font-size: 0.95rem;
            }

            .topbar-left p {
                font-size: 0.78rem;
            }
        }
    </style>
</head>

<body>

    @php
        $user = auth()->user();
        $userInitial = $user?->name ? strtoupper(mb_substr($user->name, 0, 1)) : 'U';
        $profilePhotoUrl = $user?->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : null;
        $unreadNotificationsCount = $user ? $user->unreadNotifications()->count() : 0;
        $recentNotifications = $user ? $user->notifications()->latest()->limit(6)->get() : collect();
    @endphp

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex app-wrapper">

        <!-- SIDEBAR -->
        <div class="app-sidebar text-white p-3 d-flex flex-column" id="appSidebar">

            <div class="sidebar-header">
                <i class="fas fa-home logo-icon"></i>

                <div class="sidebar-brand">
                    <h4>SIOKAS</h4>
                    <small>Sistem Informasi<br>Organisasi & Kegiatan</small>
                </div>
            </div>

            <ul class="nav flex-column flex-grow-1">

                <li class="nav-item">
                    <a href="{{ route('ketua.dashboard') }}"
                        class="nav-link {{ request()->routeIs('ketua.dashboard') ? 'active bg-white' : 'text-white' }}">
                        <i class="fas fa-home-alt"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('ketua.organisasi') }}"
                        class="nav-link {{ request()->routeIs('ketua.organisasi') ? 'active bg-white' : 'text-white' }}">
                        <i class="fas fa-building"></i> Profile Organisasi
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('ketua.kegiatan') }}"
                        class="nav-link {{ request()->routeIs('ketua.kegiatan') ? 'active bg-white' : 'text-white' }}">
                        <i class="fas fa-calendar-alt"></i> Manajemen Kegiatan
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('ketua.jadwal') }}"
                        class="nav-link {{ request()->routeIs('ketua.jadwal') ? 'active bg-white' : 'text-white' }}">
                        <i class="fas fa-calendar-week"></i> Jadwal Kegiatan
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('ketua.dokumentasi') }}"
                        class="nav-link {{ request()->routeIs('ketua.dokumentasi') ? 'active bg-white' : 'text-white' }}">
                        <i class="fas fa-images"></i> Dokumentasi
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('ketua.laporan') }}"
                        class="nav-link {{ request()->routeIs('ketua.laporan') ? 'active bg-white' : 'text-white' }}">
                        <i class="fas fa-file-alt"></i> Laporan Kegiatan
                    </a>
                </li>

            </ul>

            <hr class="text-white">

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-light text-primary fw-bold w-100">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </button>
            </form>

        </div>
        <!-- /SIDEBAR -->

        <div class="app-content">

            <!-- TOPBAR -->
            <div class="topbar d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center gap-2">

                    <button class="btn-hamburger" id="sidebarToggle" aria-label="Buka menu">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="topbar-left">
                        <h5>@yield('title', 'Dashboard')</h5>
                        <p class="d-none d-sm-block">
                            Selamat datang kembali,
                            <strong>{{ $user?->name ?? 'Ketua' }}</strong>!
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 gap-md-3">

                    <!-- Notifikasi -->
                    <div class="dropdown">
                        <button class="btn-notification position-relative" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">

                            <i class="far fa-bell"></i>

                            @if ($unreadNotificationsCount > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge badge-notification rounded-pill bg-danger">
                                    {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                                </span>
                            @endif
                        </button>

                        <div class="dropdown-menu dropdown-menu-end p-0 shadow notification-menu">

                            <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                                <strong>Notifikasi</strong>

                                @if ($unreadNotificationsCount > 0)
                                    <form action="{{ route('notifications.read-all') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm btn-link text-decoration-none p-0">
                                            Tandai semua dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>

                            @forelse ($recentNotifications as $notification)
                                @php $data = $notification->data; @endphp

                                <div
                                    class="notification-item px-3 py-2 border-bottom {{ is_null($notification->read_at) ? 'bg-light' : '' }}">

                                    <div class="fw-semibold small">
                                        {{ $data['title'] ?? 'Notifikasi' }}
                                    </div>

                                    <div class="small text-muted">
                                        {{ $data['message'] ?? '-' }}
                                    </div>

                                    <div class="small text-secondary mt-1">
                                        {{ $notification->created_at?->diffForHumans() }}
                                    </div>

                                    <div class="d-flex gap-2 mt-2">

                                        @if (!empty($data['action_url']))
                                            <a href="{{ $data['action_url'] }}"
                                                class="btn btn-sm btn-primary">
                                                {{ $data['action_label'] ?? 'Lihat detail' }}
                                            </a>
                                        @endif

                                        @if (is_null($notification->read_at))
                                            <form
                                                action="{{ route('notifications.read-one', $notification->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    Tandai dibaca
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </div>

                            @empty
                                <div class="px-3 py-3 small text-muted">
                                    Belum ada notifikasi.
                                </div>
                            @endforelse

                        </div>
                    </div>

                    <div style="width:1px; height:24px; background:#e5e7eb;"></div>

                    <!-- PROFIL -->
                    <a href="{{ route('ketua.profil') }}" class="profile-link">

                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold overflow-hidden"
                            style="width:38px; height:38px; flex-shrink:0;">

                            @if ($profilePhotoUrl)
                                <img src="{{ $profilePhotoUrl }}" alt="Foto Profil"
                                    class="w-100 h-100 object-fit-cover">
                            @else
                                {{ $userInitial }}
                            @endif

                        </div>

                        <div class="text-start profile-text profile-name-text">
                            <div class="fw-semibold" style="font-size:0.9rem;">
                                {{ $user?->name ?? 'Tamu' }}
                            </div>

                            <small class="text-muted text-uppercase">
                                {{ $user?->role ?? '-' }}
                            </small>
                        </div>

                    </a>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="app-content-body">
                @yield('content')
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // ===== SIDEBAR TOGGLE =====
        const sidebar = document.getElementById('appSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggle');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
            document.body.style.overflow = '';
        }

        toggleBtn?.addEventListener('click', openSidebar);
        overlay?.addEventListener('click', closeSidebar);

        sidebar?.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) closeSidebar();
            });
        });

        // ===== TABLE SEARCH =====
        document.addEventListener('DOMContentLoaded', function() {

            const tables = document.querySelectorAll('table');

            tables.forEach(function(table, index) {

                const tbody = table.querySelector('tbody');

                if (!tbody || table.dataset.hasGlobalSearch === '1') return;

                const rows = Array.from(tbody.querySelectorAll('tr'));

                if (!rows.length) return;

                table.dataset.hasGlobalSearch = '1';

                const wrapper = document.createElement('div');
                wrapper.className = 'd-flex justify-content-end mt-2 mb-2';

                const searchGroup = document.createElement('div');
                searchGroup.className = 'input-group input-group-sm';
                searchGroup.style.maxWidth = '360px';

                const span = document.createElement('span');
                span.className = 'input-group-text';
                span.innerHTML = '<i class="bi bi-search"></i>';

                const input = document.createElement('input');
                input.type = 'search';
                input.className = 'form-control';
                input.placeholder = 'Search semua data tabel';
                input.id = `table-global-search-${index + 1}`;

                searchGroup.appendChild(span);
                searchGroup.appendChild(input);
                wrapper.appendChild(searchGroup);

                const container = table.closest('.table-responsive') || table.parentElement;
                container.prepend(wrapper);

                input.addEventListener('input', function(e) {

                    const keyword = (e.target.value || '').toLowerCase().trim();

                    rows.forEach(row => {

                        const text = row.textContent
                            .toLowerCase()
                            .replace(/\s+/g, ' ')
                            .trim();

                        row.style.display =
                            keyword === '' || text.includes(keyword) ?
                            '' :
                            'none';
                    });
                });
            });
        });
    </script>

    @yield('scripts')

</body>

</html>