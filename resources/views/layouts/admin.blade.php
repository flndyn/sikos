<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>SIKOS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome -->
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
            font-weight: 400;
        }

        .nav-link.active.bg-white {
            color: #fff !important;
            background-color: rgba(59, 130, 246, 0.3) !important;
        }

        .app-sidebar {
            width: 250px;
            min-width: 250px;
            max-width: 250px;
            flex: 0 0 250px;
            max-height: 100vh;
            height: 100vh;
            overflow-y: auto;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            padding: 1.5rem 1rem !important;
            display: flex;
            flex-direction: column;
        }

        .sidebar-menu {
            flex: 1;
        }

        .sidebar-footer {
            margin-top: auto;
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

        .menu-section-title {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            padding: 0 0.5rem;
            letter-spacing: 0.5px;
        }

        .app-content {
            flex: 1 1 auto;
            min-width: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        .topbar {
            flex-shrink: 0;
        }

        .app-content-body {
            flex: 1 1 auto;
            min-height: 0;
            overflow-y: auto;
            padding: 2rem;
        }

        .notification-menu {
            width: 360px;
            max-width: calc(100vw - 2rem);
            max-height: 500px;
            overflow-y: auto;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .notification-item {
            white-space: normal;
        }

        .topbar {
            background-color: #f5f7fa;
            padding: 1rem 2rem !important;
            border-bottom: 1px solid #e5e7eb;
        }

        .topbar-left h5 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .topbar-left p {
            font-size: 0.9rem;
            color: #9ca3af;
            margin: 0.25rem 0 0;
        }

        .profile-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: inherit;
            cursor: pointer;
            padding: 0.5rem 0.75rem;
            border-radius: 12px;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .profile-link:hover {
            text-decoration: none;
            color: inherit;
            background-color: rgba(13, 110, 253, 0.06);
            transform: translateY(-2px);
        }

        .profile-link .profile-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
            font-size: 1.1rem;
        }

        .profile-link .profile-info {
            text-align: left;
        }

        .profile-link .profile-name {
            font-weight: 600;
            color: #1f2937;
            font-size: 0.95rem;
            margin: 0;
        }

        .profile-link .profile-role {
            font-size: 0.75rem;
            color: #9ca3af;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
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
            background-color: transparent;
            border-color: transparent;
            color: #374151;
        }

        .badge-notification {
            font-size: 0.65rem;
            padding: 0.3rem 0.5rem;
            min-width: 20px;
        }

        .btn-logout {
            background-color: rgba(30, 58, 138, 0.4) !important;
            color: #fff !important;
            border: none;
            font-weight: 400;
            transition: all 0.2s ease;
            text-align: left;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .btn-logout:hover {
            background-color: rgba(30, 58, 138, 0.6) !important;
            color: #fff !important;
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

    <div class="d-flex">

        <!-- SIDEBAR -->
        <div class="app-sidebar text-white">

            <div class="sidebar-header">
                <i class="fas fa-home logo-icon"></i>
                <div class="sidebar-brand">
                    <h4>SIKOS</h4>
                    <small>Sistem Informasi<br>Organisasi & Kegiatan</small>
                </div>
            </div>

            <div class="sidebar-menu">
                <ul class="nav flex-column">

                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-white' : 'text-white' }}">
                            <i class="fas fa-home logo-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.users') }}"
                            class="nav-link {{ request()->routeIs('admin.users') ? 'active bg-white' : 'text-white' }}">
                            <i class="fas fa-users"></i>
                            <span>Data User</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.organisasi') }}"
                            class="nav-link {{ request()->routeIs('admin.organisasi') ? 'active bg-white' : 'text-white' }}">
                            <i class="fas fa-building"></i>
                            <span>Organisasi</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.kegiatan') }}"
                            class="nav-link {{ request()->routeIs('admin.kegiatan') ? 'active bg-white' : 'text-white' }}">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Kegiatan</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.validasi') }}"
                            class="nav-link {{ request()->routeIs('admin.validasi') ? 'active bg-white' : 'text-white' }}">
                            <i class="fas fa-check-circle"></i>
                            <span>Validasi</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.dokumentasi') }}"
                            class="nav-link {{ request()->routeIs('admin.dokumentasi') ? 'active bg-white' : 'text-white' }}">
                            <i class="fas fa-images"></i>
                            <span>Dokumentasi</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.laporan') }}"
                            class="nav-link {{ request()->routeIs('admin.laporan') ? 'active bg-white' : 'text-white' }}">
                            <i class="fas fa-file-alt"></i>
                            <span>Laporan</span>
                        </a>
                    </li>

                </ul>
                <hr class="text-white my-3" style="opacity: 0.2;">

                <div class="menu-section-title">Pengaturan</div>

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.profil') }}"
                            class="nav-link {{ request()->routeIs('admin.profil') ? 'active bg-white' : 'text-white' }}">
                            <i class="fas fa-user-circle"></i>
                            <span>Profil Saya</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-footer">

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-light text-primary fw-bold w-100">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </button>
                </form>
            </div>

        </div>

        <!-- MAIN CONTENT -->
        <div class="app-content bg-light">

            <!-- TOPBAR -->
            <div class="topbar d-flex justify-content-between align-items-center">

                <!-- JUDUL DAN GREETING -->
                <div class="topbar-left">
                    <h5>@yield('title', 'Dashboard')</h5>
                    <p>Selamat datang kembali, {{ $user?->name ?? 'Admin' }}!</p>
                </div>

                <!-- PROFIL -->
                <div class="d-flex align-items-center gap-3">

                    <div class="dropdown">
                        <button class="btn-notification position-relative" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false" aria-label="Notifikasi" title="Notifikasi">
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
                                            class="btn btn-sm btn-link text-decoration-none p-0">Tandai
                                            semua
                                            dibaca</button>
                                    </form>
                                @endif
                            </div>

                            @forelse ($recentNotifications as $notification)
                                @php
                                    $data = $notification->data;
                                @endphp
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

                    <div style="width: 1px; height: 24px; background-color: #e5e7eb;"></div>

                    <a href="{{ route('admin.profil') }}" class="profile-link" title="Buka profil akun admin"
                        aria-label="Buka profil akun admin">
                        <div class="profile-avatar">
                            @if ($profilePhotoUrl)
                                <img src="{{ $profilePhotoUrl }}" alt="Foto Profil"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            @else
                                {{ $userInitial }}
                            @endif
                        </div>

                        <div class="profile-info">
                            <p class="profile-name">{{ $user?->name ?? 'Tamu' }}</p>
                            <p class="profile-role">{{ $user?->role ?? '-' }}</p>
                        </div>
                    </a>

                </div>

            </div>

            <!-- ISI HALAMAN -->
            <div class="app-content-body">
                @yield('content')
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Table search handler
        const tables = document.querySelectorAll('table');
        tables.forEach(function(table, index) {
            const tbody = table.querySelector('tbody');

            if (!tbody || table.dataset.hasGlobalSearch === '1') {
                return;
            }

            const rows = Array.from(tbody.querySelectorAll('tr'));

            if (!rows.length) {
                return;
            }

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
            input.setAttribute('aria-label', 'Search semua data tabel');
            input.id = `table-global-search-${index + 1}`;

            searchGroup.appendChild(span);
            searchGroup.appendChild(input);
            wrapper.appendChild(searchGroup);

            const container = table.closest('.table-responsive') || table.parentElement;
            container.prepend(wrapper);

            input.addEventListener('input', function(event) {
                const keyword = (event.target.value || '').toLowerCase().trim();

                rows.forEach(function(row) {
                    const text = row.textContent.toLowerCase().replace(/\s+/g, ' ')
                        .trim();
                    row.style.display = keyword === '' || text.includes(keyword) ? '' :
                        'none';
                });
            });
        });
    </script>
    @yield('scripts')

</body>

</html>
