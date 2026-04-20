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

    <!-- TABEL KEGIATAN -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            Data Kegiatan Terbaru
        </div>
        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Organisasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatanTerbaru as $item)
                        @php
                            $badgeClass = match ($item->status) {
                                'disetujui' => 'bg-success',
                                'ditolak' => 'bg-danger',
                                default => 'bg-warning text-dark',
                            };

                            $statusLabel = match ($item->status) {
                                'disetujui' => 'Disetujui',
                                'ditolak' => 'Ditolak',
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
                        </tr>0
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

@endsection
