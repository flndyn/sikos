@extends('layouts.pembina')

@section('title', 'Dashboard')

@section('content')

    @php
        $total =
            ($stats['kegiatan_pending'] ?? 0) +
            ($stats['kegiatan_disetujui_pembina'] ?? 0) +
            ($stats['kegiatan_disetujui_admin'] ?? 0) +
            ($stats['kegiatan_ditolak'] ?? 0);
    @endphp

    <div class="row g-2 g-md-3 mb-3 mb-md-4">

        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-2 p-md-3">
                    <div class="d-flex align-items-start gap-2 mb-1">
                        <div class="stat-icon bg-primary-light">
                            <i class="fas fa-building text-primary"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <p class="text-muted small mb-0 text-truncate fw-bold">Total Organisasi</p>
                            <h3 class="mb-0">{{ $stats['total_organisasi'] ?? 0 }}</h3>
                        </div>
                    </div>
                    <small class="text-muted d-block text-center">Semua Organisasi</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-2 p-md-3">
                    <div class="d-flex align-items-start gap-2 mb-1">
                        <div class="stat-icon bg-success-light">
                            <i class="fas fa-calendar-alt text-success"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <p class="text-muted small mb-0 text-truncate fw-bold">Total Kegiatan</p>
                            <h3 class="mb-0">{{ $stats['total_kegiatan'] ?? 0 }}</h3>
                        </div>
                    </div>
                    <small class="text-muted d-block text-center">Semua Kegiatan</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-2 p-md-3">
                    <div class="d-flex align-items-start gap-2 mb-1">
                        <div class="stat-icon bg-warning-light">
                            <i class="fas fa-hourglass-half text-warning"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <p class="text-muted small mb-0 text-truncate fw-bold">Pending</p>
                            <h3 class="mb-0">{{ $stats['kegiatan_pending'] ?? 0 }}</h3>
                        </div>
                    </div>
                    <small class="text-warning d-block text-center">Perlu Validasi</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-2 p-md-3">
                    <div class="d-flex align-items-start gap-2 mb-1">
                        <div class="stat-icon bg-success-light">
                            <i class="fas fa-check-circle text-success"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <p class="text-muted small mb-0 text-truncate fw-bold">Disetujui</p>
                            <h3 class="mb-0">{{ $stats['kegiatan_disetujui_pembina'] ?? 0 }}</h3>
                        </div>
                    </div>
                    <small class="text-muted d-block text-center">Telah Disetujui</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-2 p-md-3">
                    <div class="d-flex align-items-start gap-2 mb-1">
                        <div class="stat-icon bg-danger-light">
                            <i class="fas fa-times-circle text-danger"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <p class="text-muted small mb-0 text-truncate fw-bold">Ditolak</p>
                            <h3 class="mb-0">{{ $stats['kegiatan_ditolak'] ?? 0 }}</h3>
                        </div>
                    </div>
                    <small class="text-danger d-block text-center">Ditolak</small>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-4 col-lg-2">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body p-2 p-md-3">
                    <div class="d-flex align-items-start gap-2 mb-1">
                        <div class="stat-icon bg-purple-light">
                            <i class="fas fa-file-alt text-purple"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <p class="text-muted small mb-0 text-truncate fw-bold">Dokumentasi</p>
                            <h3 class="mb-0">{{ $stats['total_dokumentasi'] ?? 0 }}</h3>
                        </div>
                    </div>
                    <small class="text-muted d-block text-center">Semua Dokumentasi</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-2 g-md-3 mb-3 mb-md-4">

        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 p-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Ringkasan Kegiatan</h6>
                    <select class="form-select form-select-sm w-auto">
                        <option>Semua Waktu</option>
                    </select>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row align-items-center gap-3 gap-sm-4">
                        <div class="doughnut-container">
                            <canvas id="statusChart"></canvas>
                        </div>
                        <div class="w-100">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="legend-dot" style="background:#ffc107;"></span>
                                <div>
                                    <div class="fw-semibold small">Pending</div>
                                    <small class="text-muted">
                                        {{ $stats['kegiatan_pending'] ?? 0 }} Kegiatan
                                        ({{ $total > 0 ? number_format((($stats['kegiatan_pending'] ?? 0) / $total) * 100, 1) : 0 }}%)
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="legend-dot" style="background:#198754;"></span>
                                <div>
                                    <div class="fw-semibold small">Disetujui</div>
                                    <small class="text-muted">
                                        {{ ($stats['kegiatan_disetujui_pembina'] ?? 0) + ($stats['kegiatan_disetujui_admin'] ?? 0) }}
                                        Kegiatan
                                        ({{ $total > 0 ? number_format(((($stats['kegiatan_disetujui_pembina'] ?? 0) + ($stats['kegiatan_disetujui_admin'] ?? 0)) / $total) * 100, 1) : 0 }}%)
                                    </small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="legend-dot" style="background:#dc3545;"></span>
                                <div>
                                    <div class="fw-semibold small">Ditolak</div>
                                    <small class="text-muted">
                                        {{ $stats['kegiatan_ditolak'] ?? 0 }} Kegiatan
                                        ({{ $total > 0 ? number_format((($stats['kegiatan_ditolak'] ?? 0) / $total) * 100, 1) : 0 }}%)
                                    </small>
                                </div>
                            </div>
                            <small class="text-muted">Total: <strong>{{ $total }}</strong> kegiatan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 p-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        Kegiatan Pending
                        <span class="text-warning d-none d-sm-inline">(Perlu Validasi)</span>
                    </h6>
                    <a href="{{ route('pembina.validasi') }}" class="text-primary small">Lihat Semua Validasi</a>
                </div>

                <div class="card-body p-0">
                    @php
                        $pendingIcons = ['fa-users', 'fa-paint-brush', 'fa-hands-helping', 'fa-microphone', 'fa-tint'];
                        $pendingColors = ['primary', 'purple', 'danger', 'success', 'danger'];
                    @endphp

                    @forelse ($kegiatanPending ?? [] as $item)
                        @php $pi = $loop->index % 5; @endphp

                        <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom">

                            <!-- Icon -->
                            <div class="stat-icon bg-{{ $pendingColors[$pi] }}-light flex-shrink-0"
                                style="width:36px;height:36px;font-size:15px;">
                                <i class="fas {{ $pendingIcons[$pi] }} text-{{ $pendingColors[$pi] }}"></i>
                            </div>

                            <!-- Info -->
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold small text-truncate">
                                    {{ $item->nama_kegiatan }}
                                </div>
                                <div class="text-muted" style="font-size:11px;">
                                    {{ $item->organisasi?->nama_organisasi ?? '-' }}
                                </div>
                            </div>

                            <!-- Tanggal & Status (2 Kolom Horizontal) -->
                            <div class="d-flex align-items-center flex-shrink-0 me-2" style="gap:12px; min-width:200px;">

                                <!-- Kolom Tanggal -->
                                <div class="text-muted text-end" style="font-size:11px; min-width:110px;">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $item->tanggal_mulai ? $item->tanggal_mulai->translatedFormat('d M Y') : '-' }}
                                </div>

                                <!-- Kolom Status -->
                                <div class="text-end" style="min-width:80px;">
                                    <span class="badge bg-warning text-dark">Pending</span>
                                </div>

                            </div>

                            <!-- Button -->
                            <a href="{{ route('pembina.validasi') }}" class="btn btn-primary btn-sm flex-shrink-0">
                                Validasi
                            </a>

                        </div>

                    @empty
                        <div class="text-center text-muted py-4">
                            Tidak ada kegiatan pending.
                        </div>
                    @endforelse

                    <div class="text-center py-2 border-top">
                        <a href="{{ route('pembina.validasi') }}" class="text-primary small fw-semibold">
                            Lihat Semua Pengajuan <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== ROW 3: Kegiatan Terbaru + Jadwal Mendatang ===== -->
    <div class="row g-2 g-md-3">

        <!-- Kegiatan Terbaru -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 p-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Kegiatan Terbaru</h6>
                    <a href="{{ route('pembina.kegiatan') }}" class="text-primary small">Lihat Semua Kegiatan</a>
                </div>

                <div class="card-body p-0">
                    @php
                        $recentIcons = ['fa-laptop', 'fa-camera', 'fa-pen', 'fa-chalkboard-teacher', 'fa-palette'];
                        $recentColors = ['primary', 'purple', 'success', 'warning', 'danger'];
                    @endphp

                    @forelse ($kegiatanTerbaru as $item)
                        @php
                            $ri = $loop->index % 5;
                            $badgeClass = match ($item->status) {
                                'disetujui admin', 'disetujui pembina' => 'bg-success',
                                'ditolak admin', 'ditolak pembina' => 'bg-danger',
                                default => 'bg-warning text-dark',
                            };
                            $statusLabel = match ($item->status) {
                                'disetujui admin', 'disetujui pembina' => 'Disetujui',
                                'ditolak admin', 'ditolak pembina' => 'Ditolak',
                                default => 'Pending',
                            };
                        @endphp

                        <div class="d-flex align-items-center gap-2 px-3 py-2 border-bottom">

                            <!-- Icon -->
                            <div class="stat-icon bg-{{ $recentColors[$ri] }}-light flex-shrink-0"
                                style="width:34px;height:34px;font-size:14px;">
                                <i class="fas {{ $recentIcons[$ri] }} text-{{ $recentColors[$ri] }}"></i>
                            </div>

                            <!-- Info -->
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold small text-truncate">
                                    {{ $item->nama_kegiatan }}
                                </div>
                                <div class="text-muted" style="font-size:11px;">
                                    {{ $item->organisasi?->nama_organisasi ?? '-' }}
                                </div>
                            </div>

                            <!-- Status & Tanggal (2 Kolom Horizontal) -->
                            <div class="d-flex align-items-center flex-shrink-0" style="gap:12px; min-width:200px;">

                                <!-- Kolom Status -->
                                <div class="text-end" style="min-width:90px;">
                                    <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                                </div>

                                <!-- Kolom Tanggal -->
                                <div class="text-muted text-end" style="font-size:11px; min-width:110px;">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $item->tanggal_mulai ? $item->tanggal_mulai->translatedFormat('d M Y') : '-' }}
                                </div>

                            </div>

                        </div>

                    @empty
                        <div class="text-center text-muted py-4">
                            Belum ada data kegiatan.
                        </div>
                    @endforelse

                    <div class="text-center py-2 border-top">
                        <a href="{{ route('pembina.kegiatan') }}" class="text-primary small fw-semibold">
                            Lihat Semua Kegiatan <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal Kegiatan Mendatang -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 p-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Jadwal Kegiatan Mendatang</h6>
                    <a href="{{ route('pembina.jadwal') }}" class="text-primary small">Lihat Semua Jadwal</a>
                </div>

                <div class="card-body p-0">
                    @forelse ($jadwalMendatang ?? [] as $item)
                        <div class="d-flex gap-2 gap-md-3 px-3 py-2 border-bottom align-items-start">

                            <!-- Tanggal (Kiri) -->
                            <div class="text-center flex-shrink-0" style="min-width:36px;">
                                <div class="fw-bold" style="font-size:20px;line-height:1;color:#222;">
                                    {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d') : '-' }}
                                </div>
                                <div class="text-uppercase text-muted" style="font-size:10px;">
                                    {{ $item->tanggal_mulai ? $item->tanggal_mulai->translatedFormat('M') : '' }}
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-grow-1 min-w-0 d-flex justify-content-between align-items-start">

                                <!-- Kiri: Nama & Lokasi -->
                                <div class="min-w-0">
                                    <div class="fw-semibold small text-truncate">
                                        {{ $item->nama_kegiatan }}
                                    </div>
                                    <div class="text-muted" style="font-size:11px;">
                                        {{ $item->lokasi ?? '-' }}
                                    </div>
                                </div>

                                <!-- Kanan: Jam -->
                                <div class="text-end flex-shrink-0" style="font-size:11px; min-width:70px;">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $item->tanggal_mulai ? $item->tanggal_mulai->format('H.i') : '-' }} WIB
                                </div>

                            </div>

                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            Tidak ada jadwal mendatang.
                        </div>
                    @endforelse

                    <div class="text-center py-2 border-top">
                        <a href="{{ route('pembina.jadwal') }}" class="text-primary small fw-semibold">
                            Lihat Semua Jadwal <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Disetujui', 'Ditolak'],
                datasets: [{
                    data: [
                        {{ $stats['kegiatan_pending'] ?? 0 }},
                        {{ ($stats['kegiatan_disetujui_pembina'] ?? 0) + ($stats['kegiatan_disetujui_admin'] ?? 0) }},
                        {{ $stats['kegiatan_ditolak'] ?? 0 }}
                    ],
                    backgroundColor: ['#ffc107', '#198754', '#dc3545'],
                    borderColor: '#fff',
                    borderWidth: 3,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ` ${ctx.label}: ${ctx.raw} kegiatan`
                        }
                    }
                }
            }
        });
    </script>
@endsection

<style>
    .stat-card {
        border-radius: 10px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1) !important;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .legend-dot {
        width: 11px;
        height: 11px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .doughnut-container {
        position: relative;
        width: 160px;
        height: 160px;
        flex-shrink: 0;
    }

    /* Mobile tweaks */
    @media (max-width: 575.98px) {
        .doughnut-container {
            width: 130px;
            height: 130px;
        }

        .stat-icon {
            width: 34px;
            height: 34px;
            font-size: 15px;
        }

        h3 {
            font-size: 1.3rem;
        }
    }

    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.1);
    }

    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.1);
    }

    .bg-warning-light {
        background-color: rgba(255, 193, 7, 0.1);
    }

    .bg-danger-light {
        background-color: rgba(220, 53, 69, 0.1);
    }

    .bg-purple-light {
        background-color: rgba(124, 58, 237, 0.12);
    }

    .text-purple {
        color: #7c3aed !important;
    }

    h3 {
        font-weight: 700;
        color: #222;
    }

    .card {
        border-radius: 10px;
    }

    .card-header {
        border-bottom: 1px solid #f0f0f0 !important;
    }

    .min-w-0 {
        min-width: 0;
    }
</style>
