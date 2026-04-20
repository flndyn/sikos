@extends('layouts.ketua')

@section('title', 'Dashboard')

@section('content')

    <div class="row g-4">

        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm">
                <h6>Total Kegiatan</h6>
                <h3>{{ $stats['total_kegiatan'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm">
                <h6>Kegiatan Disetujui</h6>
                <h3>{{ $stats['kegiatan_disetujui_admin'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm">
                <h6>Kegiatan Ditolak</h6>
                <h3>{{ $stats['kegiatan_ditolak'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3 shadow-sm">
                <h6>Menunggu Persetujuan</h6>
                <h3>{{ $stats['total_dokumentasi'] }}</h3>
            </div>
        </div>

    </div>

    <!-- TABEL KEGIATAN -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            Daftar Kegiatan Terbaru
        </div>
        <div class="card-body">

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Status</th>
                        <th>Tanggal Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatanTerbaru as $item)
                        @php
                            $badgeClass = match ($item->status) {
                                'disetujui admin' => 'bg-success',
                                'disetujui pembina' => 'bg-info text-dark',
                                'ditolak admin', 'ditolak pembina' => 'bg-danger',
                                default => 'bg-warning text-dark',
                            };

                            $statusLabel = match ($item->status) {
                                'disetujui admin' => 'Disetujui Admin',
                                'disetujui pembina' => 'Menunggu Admin',
                                'ditolak admin' => 'Ditolak Admin',
                                'ditolak pembina' => 'Ditolak Pembina',
                                default => 'Pending',
                            };
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_kegiatan }}</td>
                            <td><span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span></td>
                            <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
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

    <!-- DOKUMENTASI TERAKHIR DIUNGGAH -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            Dokumentasi Terakhir Diunggah
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Kolom 1: Foto Terakhir -->
                <div class="col-md-8">
                    @if ($dokumentasiTerbaru)
                        <div class="bg-light d-flex align-items-center justify-content-center"
                            style="height: 300px; border-radius: 8px; overflow: hidden; cursor: pointer;"
                            data-bs-toggle="modal" data-bs-target="#modalGambarBesar">
                            <img src="{{ asset('storage/' . $dokumentasiTerbaru->file_dokumentasi) }}" alt="Dokumentasi"
                                class="img-fluid" style="object-fit: cover; width: 100%; height: 100%;">
                        </div>
                        <p class="mt-3 text-muted small">
                            <strong>Kegiatan:</strong> {{ $dokumentasiTerbaru->kegiatan->nama_kegiatan ?? '-' }}<br>
                            <strong>Diunggah:</strong> {{ $dokumentasiTerbaru->created_at->format('d-m-Y H:i') }}<br>
                        </p>
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center"
                            style="height: 300px; border-radius: 8px;">
                            <p class="text-muted">Belum ada dokumentasi yang diunggah</p>
                        </div>
                    @endif
                </div>

                <!-- Kolom 2: 3 Tombol -->
                <div class="col-md-4">
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('ketua.kegiatan') }}"
                            class="btn btn-primary d-flex align-items-center justify-content-center" style="height: 60px;">
                            <i class="bi bi-plus-circle me-2"></i>Ajukan Kegiatan
                        </a>
                        <a href="{{ route('ketua.dokumentasi') }}"
                            class="btn btn-success d-flex align-items-center justify-content-center" style="height: 60px;">
                            <i class="bi bi-upload me-2"></i>Upload Dokumentasi
                        </a>
                        <a href="{{ route('ketua.jadwal') }}"
                            class="btn btn-secondary d-flex align-items-center justify-content-center text-white"
                            style="height: 60px;">
                            <i class="bi bi-calendar-event me-2"></i>Lihat Jadwal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL GAMBAR BESAR -->
    @if ($dokumentasiTerbaru)
        <div class="modal fade" id="modalGambarBesar" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $dokumentasiTerbaru->kegiatan->nama_kegiatan ?? 'Dokumentasi' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <img src="{{ asset('storage/' . $dokumentasiTerbaru->file_dokumentasi) }}" alt="Dokumentasi"
                            class="img-fluid w-100" style="border-radius: 8px;">
                    </div>
                    <div class="modal-footer">
                        <p class="text-muted small mb-0">
                            Diunggah: {{ $dokumentasiTerbaru->created_at->format('d-m-Y H:i') }}<br>
                            Keterangan: {{ $dokumentasiTerbaru->keterangan ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
