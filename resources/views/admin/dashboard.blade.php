@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    <!-- STAT CARDS -->
    <div class="mb-4">
        <div class="row g-3 mb-3">
            <!-- Total Pengguna -->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('admin.users') }}" class="text-decoration-none">
                    <div class="card stat-card border-0 shadow-sm h-100 stat-card-link">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-start gap-2 mb-2">
                                <div class="stat-icon bg-primary-light">
                                    <i class="fas fa-users text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted small mb-1">Total Pengguna</p>
                                    <div class="stat-value">
                                        <h3 class="mb-0">{{ $stats['total_pengguna'] ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Organisasi -->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('admin.organisasi') }}" class="text-decoration-none">
                    <div class="card stat-card border-0 shadow-sm h-100 stat-card-link">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-start gap-2 mb-2">
                                <div class="stat-icon bg-success-light">
                                    <i class="fas fa-building text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted small mb-1">Total Organisasi</p>
                                    <div class="stat-value">
                                        <h3 class="mb-0">{{ $stats['total_organisasi'] ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Kegiatan -->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('admin.kegiatan') }}" class="text-decoration-none">
                    <div class="card stat-card border-0 shadow-sm h-100 stat-card-link">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-start gap-2 mb-2">
                                <div class="stat-icon bg-purple-light">
                                    <i class="fas fa-calendar-alt text-purple"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted small mb-1">Total Kegiatan</p>
                                    <div class="stat-value">
                                        <h3 class="mb-0">{{ $stats['total_kegiatan'] ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Dokumentasi -->
            <div class="col-md-6 col-lg-3">
                <a href="{{ route('admin.dokumentasi') }}" class="text-decoration-none">
                    <div class="card stat-card border-0 shadow-sm h-100 stat-card-link">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-start gap-2 mb-2">
                                <div class="stat-icon bg-cyan-light">
                                    <i class="fas fa-file-alt text-cyan"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted small mb-1">Dokumentasi</p>
                                    <div class="stat-value">
                                        <h3 class="mb-0">{{ $stats['total_dokumentasi'] ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row g-3">
            <!-- Kegiatan Butuh Validasi -->
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('admin.validasi') }}" class="text-decoration-none">
                    <div class="card stat-card border-0 shadow-sm h-100 stat-card-link">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-start gap-2 mb-2">
                                <div class="stat-icon bg-secondary-light">
                                    <i class="fas fa-hourglass-half text-secondary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted small mb-1">Kegiatan Butuh Validasi</p>
                                    <div class="stat-value">
                                        <h3 class="mb-0">{{ $stats['kegiatan_butuh_validasi'] ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Kegiatan Ditolak -->
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('admin.kegiatan') }}?status=ditolak" class="text-decoration-none">
                    <div class="card stat-card border-0 shadow-sm h-100 stat-card-link">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-start gap-2 mb-2">
                                <div class="stat-icon bg-danger-light">
                                    <i class="fas fa-times-circle text-danger"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted small mb-1">Kegiatan Ditolak</p>
                                    <div class="stat-value">
                                        <h3 class="mb-0">{{ $stats['kegiatan_ditolak'] ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Kegiatan Diterima -->
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('admin.kegiatan') }}?status=disetujui" class="text-decoration-none">
                    <div class="card stat-card border-0 shadow-sm h-100 stat-card-link">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div class="d-flex align-items-start gap-2 mb-2">
                                <div class="stat-icon bg-warning-light">
                                    <i class="fas fa-check-circle text-warning"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted small mb-1">Kegiatan Diterima</p>
                                    <div class="stat-value">
                                        <h3 class="mb-0">{{ $stats['kegiatan_disetujui'] ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- CHARTS SECTION -->
    <div class="row g-3 mb-4">
        <!-- Bar Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center p-3">
                    <div>
                        <h6 class="mb-0">Organisasi Paling Aktif (Top 10)</h6>
                        <small class="text-muted">Berdasarkan jumlah kegiatan yang diajukan</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light" type="button" id="chartDropdown" data-bs-toggle="dropdown">
                            Tahun Ini <i class="fas fa-chevron-down ms-2"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="chartDropdown">
                            <li><a class="dropdown-item"
                                    href="{{ route('admin.dashboard', ['periode' => '6-bulan']) }}">6
                                    Bulan</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ route('admin.dashboard', ['periode' => '12-bulan']) }}">12
                                    Bulan</a></li>
                            <li><a class="dropdown-item"
                                    href="{{ route('admin.dashboard', ['periode' => 'tahun-ini']) }}">Tahun Ini</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px;">
                        <canvas id="kegiatanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 p-3">
                    <h6 class="mb-0">Ringkasan Kegiatan</h6>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="ringkasanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KEGIATAN TERBARU & PERLU VALIDASI -->
    <div class="row g-3">
        <!-- Kegiatan Terbaru -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 p-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Kegiatan Terbaru</h6>
                    <a href="{{ route('admin.kegiatan') }}" class="text-primary small">Lihat Semua Kegiatan <i
                            class="fas fa-arrow-right ms-2"></i></a>
                </div>
                <div class="card-body">
                    @forelse($kegiatanTerbaru as $item)
                        @php
                            $badgeClass = match ($item->status) {
                                'disetujui admin' => 'bg-success',
                                'disetujui pembina' => 'bg-warning text-dark',
                                'ditolak admin', 'ditolak pembina' => 'bg-danger',
                                'pending' => 'bg-secondary',
                                default => 'bg-light text-dark',
                            };

                            $statusLabel = match ($item->status) {
                                'disetujui admin' => 'Disetujui Admin',
                                'disetujui pembina' => 'Disetujui Pembina',
                                'ditolak admin' => 'Ditolak Admin',
                                'ditolak pembina' => 'Ditolak Pembina',
                                'pending' => 'Pending',
                                default => ucfirst($item->status),
                            };
                        @endphp
                        <div class="d-flex align-items-center mb-3 pb-3" style="border-bottom: 1px solid #e9ecef;">
                            <div class="me-3">
                                <i class="fas fa-calendar-alt fa-2x text-primary opacity-50"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item->nama_kegiatan }}</h6>
                                <small class="text-muted">{{ $item->organisasi->nama_organisasi ?? '-' }}</small>
                            </div>
                            <div class="text-end ms-3" style="min-width: 95px;">
                                <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                            </div>
                            <div class="text-end ms-3" style="min-width: 120px;">
                                <small class="text-muted d-block"><i class="far fa-calendar"></i>
                                    {{ $item->tanggal_mulai ? $item->tanggal_mulai->translatedFormat('d M Y') : '-' }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">Belum ada data kegiatan.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pengajuan Perlu Validasi -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 p-3 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Pengajuan Terbaru (Perlu Validasi)</h6>
                    <a href="{{ route('admin.validasi') }}" class="text-primary small">Lihat Semua Validasi <i
                            class="fas fa-arrow-right ms-2"></i></a>
                </div>
                <div class="card-body">
                    @forelse($pengajuanTerbaru as $item)
                        @php
                            $badgeClass = match ($item->status) {
                                'disetujui admin' => 'bg-success',
                                'disetujui pembina' => 'bg-warning text-dark',
                                'ditolak admin', 'ditolak pembina' => 'bg-danger',
                                'pending' => 'bg-secondary',
                                default => 'bg-light text-dark',
                            };

                            $statusLabel = match ($item->status) {
                                'disetujui admin' => 'Disetujui Admin',
                                'disetujui pembina' => 'Disetujui Pembina',
                                'ditolak admin' => 'Ditolak Admin',
                                'ditolak pembina' => 'Ditolak Pembina',
                                'pending' => 'Pending',
                                default => ucfirst($item->status),
                            };
                        @endphp
                        <div class="d-flex align-items-center mb-3 pb-3" style="border-bottom: 1px solid #e9ecef;">
                            <div class="me-3">
                                <i class="fas fa-user-circle fa-2x text-primary opacity-50"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $item->nama_kegiatan }}</h6>
                                <small class="text-muted">{{ $item->organisasi->nama_organisasi ?? '-' }}</small>
                            </div>
                            <div class="text-end ms-3" style="min-width: 120px;">
                                <small class="text-muted d-block"><i class="far fa-calendar"></i>
                                    {{ $item->tanggal_mulai ? $item->tanggal_mulai->translatedFormat('d M Y') : '-' }}</small>
                            </div>
                            <div class="text-end ms-3" style="min-width: 95px;">
                                <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                            </div>
                            <a href="{{ route('admin.validasi') }}" class="btn btn-sm btn-primary ms-3">Validasi</a>
                        </div>
                    @empty
                        <p class="text-center text-muted">Belum ada pengajuan dengan status Disetujui Admin.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @php
        $defaultChartLabels = [
            'PMR',
            'OSIS',
            'Pramuka',
            'Rohis',
            'KIR',
            'Basket',
            'Paskibra',
            'Seni Musik',
            'iKlim',
            'Jurnalistik',
        ];
        $chartLabelsSafe = !empty($chartLabels) ? $chartLabels : $defaultChartLabels;
        $chartDataSafe = !empty($chartData) ? $chartData : [14, 11, 9, 7, 6, 5, 4, 3, 2, 1];
    @endphp
    <script>
        // Bar Chart for Top 10 Organizations
        const ctx = document.getElementById('kegiatanChart').getContext('2d');
        const kegiatanChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabelsSafe),
                datasets: [{
                    label: 'Jumlah Kegiatan',
                    data: @json($chartDataSafe),
                    backgroundColor: '#0d6efd',
                    borderColor: '#0d6efd',
                    borderRadius: 6,
                    borderWidth: 0,
                }]
            },
            options: {
                indexAxis: 'x',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    x: {
                        type: 'category',
                        title: {
                            display: true,
                            text: 'Organisasi'
                        }
                    },
                    y: {
                        type: 'linear',
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Kegiatan'
                        },
                        ticks: {
                            stepSize: 2
                        }
                    }
                }
            }
        });

        // Pie Chart for Ringkasan
        const ctxPie = document.getElementById('ringkasanChart').getContext('2d');

        // Plugin to draw text in center of doughnut
        const textCenterPlugin = {
            id: 'textCenter',
            afterDatasetsDraw(chart) {
                const {
                    data,
                    ctx,
                    chartArea
                } = chart;
                const total = data.datasets[0].data.reduce((a, b) => a + b, 0);

                // Calculate center of the chart
                const centerX = (chartArea.left + chartArea.right) / 2;
                const centerY = (chartArea.top + chartArea.bottom) / 2;

                // Draw total number
                ctx.save();
                ctx.font = 'bold 28px Arial';
                ctx.fillStyle = '#333';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(total, centerX, centerY - 15);

                // Draw "Total" text
                ctx.font = '14px Arial';
                ctx.fillStyle = '#999';
                ctx.fillText('Total', centerX, centerY + 15);
                ctx.restore();
            }
        };

        const ringkasanChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ['Disetujui', 'Ditolak', 'Pending'],
                datasets: [{
                    data: [
                        {{ $stats['kegiatan_disetujui'] ?? 0 }},
                        {{ $stats['kegiatan_ditolak'] ?? 0 }},
                        {{ $stats['kegiatan_pending'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#dc3545',
                        '#6c757d'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            boxWidth: 12,
                            font: {
                                size: 12
                            },
                            generateLabels: function(chart) {
                                const data = chart.data;
                                const datasets = data.datasets;
                                const total = datasets[0].data.reduce((a, b) => a + b, 0);
                                return data.labels.map((label, i) => {
                                    const value = datasets[0].data[i];
                                    const percentage = ((value / total) * 100).toFixed(0);
                                    return {
                                        text: `${label} ${value} (${percentage}%)`,
                                        fillStyle: datasets[0].backgroundColor[i],
                                        hidden: false,
                                        index: i
                                    };
                                });
                            }
                        }
                    }
                }
            },
            plugins: [textCenterPlugin]
        });
    </script>
@endsection

<style>
    .stat-card {
        border-radius: 8px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card-link {
        cursor: pointer;
    }

    .stat-card-link:hover .stat-card {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.1);
    }

    .bg-success-light {
        background-color: rgba(40, 167, 69, 0.1);
    }

    .bg-warning-light {
        background-color: rgba(255, 193, 7, 0.1);
    }

    .bg-purple-light {
        background-color: rgba(124, 58, 237, 0.12);
    }

    .bg-info-light {
        background-color: rgba(23, 162, 184, 0.1);
    }

    .bg-cyan-light {
        background-color: rgba(6, 182, 212, 0.12);
    }

    .bg-danger-light {
        background-color: rgba(220, 53, 69, 0.1);
    }

    .bg-secondary-light {
        background-color: rgba(108, 117, 125, 0.12);
    }

    .card {
        border-radius: 8px;
    }

    .card-header {
        border-bottom: 1px solid #e9ecef;
        border-radius: 8px 8px 0 0 !important;
    }

    h3 {
        font-weight: 700;
        color: #333;
    }

    .text-success {
        color: #28a745 !important;
    }

    .text-purple {
        color: #7c3aed !important;
    }

    .text-cyan {
        color: #06b6d4 !important;
    }

    .stat-card .stat-value {
        min-height: 40px;
        display: flex;
        align-items: center;
    }

    .stat-card .stat-value h3 {
        font-size: 1.5rem;
        margin: 0;
    }
</style>
