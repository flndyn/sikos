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
            text-decoration: none;
            color: inherit;
            background-color: rgba(13, 110, 253, 0.06);
            transform: translateY(-1px);
        }

        .profile-link .profile-text {
            line-height: 1.1;
        }

        .profile-link .profile-hint {
            font-size: 0.72rem;
            color: #0d6efd;
            font-weight: 600;
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
        <div class="app-sidebar bg-primary text-white p-3">

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

                <li class="nav-item mb-1">
                    <a href="{{ route('ketua.laporan') }}"
                        class="nav-link {{ request()->routeIs('ketua.laporan*') ? 'active bg-white' : 'text-white' }}">
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

        <!-- MAIN CONTENT -->
        <div class="app-content bg-light">

            <!-- TOPBAR -->
            <div class="d-flex justify-content-between align-items-center bg-white p-3 shadow-sm">

                <!-- JUDUL -->
                <h5 class="mb-0">
                    @yield('title', 'Dashboard')
                </h5>

                <!-- PROFIL -->
                <div class="d-flex align-items-center gap-3">

                    <div class="dropdown">
                        <button class="btn btn-outline-secondary position-relative" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifikasi">
                            <i class="bi bi-bell"></i>
                            @if ($unreadNotificationsCount > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
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
                                            class="btn btn-sm btn-link text-decoration-none p-0">Tandai semua
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

                    <a href="{{ route('ketua.profil') }}" class="profile-link me-3"
                        title="Klik untuk mengubah profil akun" aria-label="Klik untuk mengubah profil akun">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold overflow-hidden"
                            style="width:40px; height:40px;">
                            @if ($profilePhotoUrl)
                                <img src="{{ $profilePhotoUrl }}" alt="Foto Profil"
                                    class="w-100 h-100 object-fit-cover">
                            @else
                                {{ $userInitial }}
                            @endif
                        </div>

                        <div class="text-start profile-text">
                            <div class="fw-semibold">{{ $user?->name ?? 'Tamu' }}</div>
                            <small class="text-muted text-uppercase">{{ $user?->role ?? '-' }}</small>
                        </div>
                    </a>

                </div>

            </div>

            <!-- ISI HALAMAN -->
            <div class="p-4">
                @yield('content')
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>

</body>

</html>
