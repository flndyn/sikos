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
        }

        /* ===================================================
           SIDEBAR
        =================================================== */
        .app-sidebar {
            width: 200px;
            min-width: 200px;
            max-width: 200px;
            min-height: 100vh;
            background: #1a56db;
            /* solid blue sesuai foto */
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            z-index: 1040;
        }

        /* --- Brand --- */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 1.1rem 1rem 0.85rem 1rem;
            text-decoration: none;
        }

        .sidebar-brand-shield {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.18);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-brand-shield i {
            font-size: 1.2rem;
            color: #fff;
        }

        .sidebar-brand-text .brand-title {
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.1;
        }

        .sidebar-brand-text .brand-sub {
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.35;
        }

        .sidebar-divider {
            border: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            margin: 0 0.85rem 0.5rem;
        }

        /* --- Nav --- */
        .sidebar-nav {
            list-style: none;
            padding: 0 0.65rem;
            margin: 0;
            flex: 1;
        }

        .sidebar-nav .nav-item {
            margin-bottom: 0.1rem;
        }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.65rem 0.85rem !important;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: background 0.2s ease, transform 0.15s ease;
            white-space: nowrap;
        }

        .sidebar-nav .nav-link i {
            font-size: 1rem;
            min-width: 18px;
            text-align: center;
            flex-shrink: 0;
        }

        /* Hover */
        .sidebar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
        }

        /* Active — white background, blue text (sesuai foto) */
        .sidebar-nav .nav-link.active {
            background: #ffffff;
            color: #1a56db !important;
            font-weight: 500;
        }

        .sidebar-nav .nav-link.active i {
            color: #1a56db;
        }

        /* --- Footer / Logout --- */
        .sidebar-footer {
            padding: 0.75rem 0.85rem 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .btn-logout {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.55rem 1rem;
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            color: #fff;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s, border-color 0.2s;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.8);
        }

        /* ===================================================
           TOPBAR
        =================================================== */
        .topbar {
            background-color: #f5f7fa;
            padding: 1rem 1.5rem !important;
            border-bottom: 1px solid #e9ecef;
        }

        .topbar-left h5 {
            font-weight: 600;
            font-size: 1.1rem;
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
            transition: background-color 0.2s ease;
        }

        .profile-link:hover {
            background-color: rgba(13, 110, 253, 0.06);
            color: inherit;
        }

        /* ===================================================
           CONTENT
        =================================================== */
        .app-content {
            flex: 1 1 auto;
            min-width: 0;
        }

        /* ===================================================
           MOBILE
        =================================================== */
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

    <!-- Mobile overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex app-wrapper">

        {{-- ===== SIDEBAR ===== --}}
        <div class="app-sidebar" id="appSidebar">

            {{-- Brand --}}
            <a href="{{ route('ketua.dashboard') }}" class="sidebar-brand">
                <div class="sidebar-brand-shield">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="sidebar-brand-text">
                    <div class="brand-title">SIOKAS</div>
                    <div class="brand-sub">Sistem Informasi<br>Organisasi &amp; Kegiatan</div>
                </div>
            </a>

            <hr class="sidebar-divider">

            {{-- Navigation --}}
            <ul class="sidebar-nav">

                <li class="nav-item">
                    <a href="{{ route('ketua.dashboard') }}"
                        class="nav-link {{ request()->routeIs('ketua.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('ketua.organisasi') }}"
                        class="nav-link {{ request()->routeIs('ketua.organisasi') ? 'active' : '' }}">
                        <i class="fas fa-th-large"></i>
                        <span>Profile Organisasi</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('ketua.kegiatan') }}"
                        class="nav-link {{ request()->routeIs('ketua.kegiatan') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Manajemen Kegiatan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('ketua.jadwal') }}"
                        class="nav-link {{ request()->routeIs('ketua.jadwal') ? 'active' : '' }}">
                        <i class="fas fa-calendar-week"></i>
                        <span>Jadwal Kegiatan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('ketua.dokumentasi') }}"
                        class="nav-link {{ request()->routeIs('ketua.dokumentasi') ? 'active' : '' }}">
                        <i class="far fa-folder-open"></i>
                        <span>Dokumentasi</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('ketua.laporan') }}"
                        class="nav-link {{ request()->routeIs('ketua.laporan*') ? 'active' : '' }}">
                        <i class="far fa-file-alt"></i>
                        <span>Laporan Kegiatan</span>
                    </a>
                </li>

            </ul>

            {{-- Logout --}}
            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>

        </div>
        {{-- /SIDEBAR --}}

        {{-- ===== MAIN CONTENT ===== --}}
        <div class="app-content">

            {{-- TOPBAR --}}
            <div class="topbar d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center gap-2">
                    <button class="btn-hamburger" id="sidebarToggle" aria-label="Buka menu">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="topbar-left">
                        <h5>@yield('title', 'Dashboard')</h5>
                        <p class="d-none d-sm-block">
                            Selamat datang kembali, <strong>{{ $user?->name ?? 'Ketua' }}</strong>!
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 gap-md-3">

                    {{-- Notifikasi --}}
                    <div class="dropdown">
                        <button class="btn-notification position-relative" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false" aria-label="Notifikasi">
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
                                        <button type="submit" class="btn btn-sm btn-link text-decoration-none p-0">
                                            Tandai semua dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>

                            @forelse ($recentNotifications as $notification)
                                @php $data = $notification->data; @endphp
                                <div
                                    class="notification-item px-3 py-2 border-bottom {{ is_null($notification->read_at) ? 'bg-light' : '' }}">
                                    <div class="fw-semibold small">{{ $data['title'] ?? 'Notifikasi' }}</div>
                                    <div class="small text-muted">{{ $data['message'] ?? '-' }}</div>
                                    <div class="small text-secondary mt-1">
                                        {{ $notification->created_at?->diffForHumans() }}</div>
                                    <div class="d-flex gap-2 mt-2">
                                        @if (!empty($data['action_url']))
                                            <a href="{{ $data['action_url'] }}" class="btn btn-sm btn-primary">
                                                {{ $data['action_label'] ?? 'Lihat detail' }}
                                            </a>
                                        @endif
                                        @if (is_null($notification->read_at))
                                            <form action="{{ route('notifications.read-one', $notification->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-secondary">Tandai
                                                    dibaca</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="px-3 py-3 small text-muted">Belum ada notifikasi.</div>
                            @endforelse
                        </div>
                    </div>

                    <div style="width:1px; height:24px; background:#e5e7eb;"></div>

                    {{-- Profil --}}
                    <a href="{{ route('ketua.profil') }}" class="profile-link" title="Buka profil">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold overflow-hidden"
                            style="width:38px; height:38px; flex-shrink:0;">
                            @if ($profilePhotoUrl)
                                <img src="{{ $profilePhotoUrl }}" alt="Foto Profil"
                                    class="w-100 h-100 object-fit-cover">
                            @else
                                {{ $userInitial }}
                            @endif
                        </div>
                        <div class="text-start profile-name-text">
                            <div class="fw-semibold" style="font-size:0.9rem; line-height:1.1;">
                                {{ $user?->name ?? 'Tamu' }}</div>
                            <small class="text-muted text-uppercase"
                                style="font-size:0.72rem;">{{ $user?->role ?? '-' }}</small>
                        </div>
                        <i class="fas fa-chevron-down text-muted ms-1 profile-name-text"
                            style="font-size:0.65rem;"></i>
                    </a>

                </div>
            </div>
            {{-- /TOPBAR --}}

            <div class="p-4">
                @yield('content')
            </div>

        </div>
        {{-- /MAIN CONTENT --}}

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // ===== SIDEBAR TOGGLE (mobile) =====
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
            document.querySelectorAll('table').forEach(function(table, index) {
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

                (table.closest('.table-responsive') || table.parentElement).prepend(wrapper);

                input.addEventListener('input', function(e) {
                    const keyword = (e.target.value || '').toLowerCase().trim();
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase().replace(/\s+/g, ' ')
                            .trim();
                        row.style.display = keyword === '' || text.includes(keyword) ? '' :
                            'none';
                    });
                });
            });
        });
    </script>

    @yield('scripts')
</body>

</html>
