@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    <div class="row g-3">

        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h6>Total Pengguna</h6>
                <h3>{{ $stats['total_pengguna'] }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h6>Total Organisasi</h6>
                <h3>{{ $stats['total_organisasi'] }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h6>Total Kegiatan</h6>
                <h3>{{ $stats['total_kegiatan'] }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h6>Kegiatan Disetujui</h6>
                <h3>{{ $stats['kegiatan_disetujui'] }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h6>Kegiatan Ditolak</h6>
                <h3>{{ $stats['kegiatan_ditolak'] }}</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h6>Dokumentasi</h6>
                <h3>{{ $stats['total_dokumentasi'] }}</h3>
            </div>
        </div>

    </div>

    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Grafik Pengajuan Kegiatan per Bulan (12 Bulan Terakhir)</h5>
        </div>
        <div class="card-body">
            <div style="position: relative; height: 300px;">
                <canvas id="kegiatanChart"></canvas>
            </div>
        </div>
    </div>

    <!-- TABEL KEGIATAN -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            Data Kegiatan Terbaru
        </div>
        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Nama Kegiatan</th>
                        <th class="text-nowrap">Organisasi</th>
                        <th class="text-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatanTerbaru as $item)
                        @php
                            $badgeClass = match ($item->status) {
                                'disetujui admin', 'disetujui pembina' => 'bg-success',
                                'ditolak admin', 'ditolak pembina' => 'bg-danger',
                                default => 'bg-warning text-dark',
                            };

                            $statusLabel = match ($item->status) {
                                'disetujui admin' => 'Disetujui Admin',
                                'disetujui pembina' => 'Disetujui Pembina',
                                'ditolak admin' => 'Ditolak Admin',
                                'ditolak pembina' => 'Ditolak Pembina',
                                default => 'Pending',
                            };
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_kegiatan }}</td>
                            <td>{{ $item->organisasi->nama_organisasi ?? '-' }}</td>
                            <td><span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data kegiatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        const ctx = document.getElementById('kegiatanChart').getContext('2d');
        const kegiatanChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Jumlah Pengajuan Kegiatan',
                    data: @json($chartData),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#0d6efd',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endsection
