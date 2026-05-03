@extends('layouts.ketua')

@section('title', 'Dashboard')

@section('content')

    {{-- ===== STAT CARDS ===== --}}
    <div class="row g-3 mb-4">

        {{-- Total Kegiatan --}}
        <div class="col-6 col-lg-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon-wrap stat-blue">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                        <div class="stat-label">Total Kegiatan</div>
                        <div class="stat-value">{{ $stats['total_kegiatan'] ?? 0 }}</div>
                        <div class="stat-sub">Semua kegiatan</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kegiatan Disetujui --}}
        <div class="col-6 col-lg-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon-wrap stat-green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-label">Kegiatan Disetujui</div>
                        <div class="stat-value">{{ $stats['kegiatan_disetujui_admin'] ?? 0 }}</div>
                        <div class="stat-sub">Telah disetujui</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kegiatan Ditolak --}}
        <div class="col-6 col-lg-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon-wrap stat-red">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div>
                        <div class="stat-label">Kegiatan Ditolak</div>
                        <div class="stat-value">{{ $stats['kegiatan_ditolak'] ?? 0 }}</div>
                        <div class="stat-sub">Ditolak</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Menunggu Persetujuan --}}
        <div class="col-6 col-lg-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3 py-3">
                    <div class="stat-icon-wrap stat-orange">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div>
                        <div class="stat-label">Menunggu Persetujuan</div>
                        <div class="stat-value">{{ $stats['kegiatan_pending'] ?? 0 }}</div>
                        <div class="stat-sub">Perlu persetujuan</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="row g-3">

        {{-- ===== KIRI (8 col) ===== --}}
        <div class="col-12 col-lg-8 d-flex flex-column gap-3">

            {{-- TABLE: Daftar Kegiatan Terbaru --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-semibold">Daftar Kegiatan Terbaru</h6>
                    <a href="#" class="text-primary small fw-semibold text-decoration-none">
                        Lihat Semua <i class="fas fa-arrow-right ms-1" style="font-size:0.7rem;"></i>
                    </a>
                </div>

                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width:40px;">No</th>
                                <th>Nama Kegiatan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kegiatanTerbaru as $item)
                                <tr>
                                    <td class="ps-3 text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            {{-- Ikon berwarna sesuai jenis --}}
                                            <div class="kegiatan-icon-wrap">
                                                <i class="fas fa-calendar text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold small">{{ $item->nama_kegiatan }}</div>
                                                <div class="text-muted" style="font-size:0.75rem;">
                                                    {{ $item->organisasi?->nama_organisasi }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted small">
                                            {{ $item->tanggal_mulai?->translatedFormat('d M Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $status = $item->status ?? 'pending';
                                        @endphp
                                        @if ($status === 'disetujui' || $status === 'approved')
                                            <span class="badge-status badge-disetujui">Disetujui</span>
                                        @elseif($status === 'ditolak' || $status === 'rejected')
                                            <span class="badge-status badge-ditolak">Ditolak</span>
                                        @else
                                            <span class="badge-status badge-menunggu">Menunggu</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">Belum ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="text-center py-2 border-top">
                        <a href="#" class="text-primary small fw-semibold text-decoration-none">
                            Lihat Semua Kegiatan <i class="fas fa-arrow-right ms-1" style="font-size:0.7rem;"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- DOKUMENTASI --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-semibold">Dokumentasi Terakhir Diunggah</h6>
                    <a href="#" class="text-primary small fw-semibold text-decoration-none">Lihat Semua</a>
                </div>

                <div class="card-body d-flex gap-3 align-items-start py-3">

                    {{-- Empty / Upload CTA --}}
                    <div class="dokum-empty-box text-center">
                        <div class="dokum-empty-icon mb-2">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="small fw-semibold mb-1">Belum ada dokumentasi</div>
                        <div class="text-muted" style="font-size:0.72rem; line-height:1.3;">Upload dokumentasi
                            kegiatan<br>untuk menampilkan di sini</div>
                        <a href="#" class="btn btn-primary btn-sm mt-2 px-3">Upload Sekarang</a>
                    </div>

                    {{-- File List --}}
                    <div class="flex-grow-1">
                        @php $dokList = (isset($dokumentasiTerbaru) && is_iterable($dokumentasiTerbaru)) ? $dokumentasiTerbaru : []; @endphp
                        @forelse($dokList as $dok)
                            <div class="dokum-file-item">
                                <div class="dokum-file-icon">
                                    @php
                                        $ext = pathinfo($dok->nama_file ?? '', PATHINFO_EXTENSION);
                                    @endphp
                                    @if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <i class="fas fa-file-image text-primary"></i>
                                    @elseif($ext === 'pdf')
                                        <i class="fas fa-file-pdf text-danger"></i>
                                    @else
                                        <i class="fas fa-file-word text-info"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="small fw-semibold">{{ $dok->nama_file ?? '-' }}</div>
                                    <div class="text-muted" style="font-size:0.72rem;">
                                        {{ $dok->created_at?->translatedFormat('d M Y') }}
                                        @if ($dok->ukuran ?? null)
                                            • {{ number_format($dok->ukuran / 1024 / 1024, 1) }} MB
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- Static sample shown when empty (matches screenshot) --}}
                            <div class="dokum-file-item">
                                <div class="dokum-file-icon">
                                    <i class="fas fa-file-image text-primary"></i>
                                </div>
                                <div>
                                    <div class="small fw-semibold">Foto Seminar Kepemimpinan.jpg</div>
                                    <div class="text-muted" style="font-size:0.72rem;">21 Mei 2025 • 2.4 MB</div>
                                </div>
                            </div>
                            <div class="dokum-file-item">
                                <div class="dokum-file-icon">
                                    <i class="fas fa-file-pdf text-danger"></i>
                                </div>
                                <div>
                                    <div class="small fw-semibold">Poster Bakti Sosial Ramadhan.pdf</div>
                                    <div class="text-muted" style="font-size:0.72rem;">19 Mei 2025 • 1.8 MB</div>
                                </div>
                            </div>
                            <div class="dokum-file-item">
                                <div class="dokum-file-icon">
                                    <i class="fas fa-file-word text-info"></i>
                                </div>
                                <div>
                                    <div class="small fw-semibold">Laporan Donor Darah.docx</div>
                                    <div class="text-muted" style="font-size:0.72rem;">17 Mei 2025 • 856 KB</div>
                                </div>
                            </div>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>

        {{-- ===== KANAN (4 col) ===== --}}
        <div class="col-12 col-lg-4 d-flex flex-column gap-3">

            {{-- AKSI CEPAT --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">Aksi Cepat</h6>
                </div>

                <div class="card-body d-flex flex-column gap-2 py-3">

                    <a href="#" class="btn-aksi btn-aksi-blue">
                        <div class="btn-aksi-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold small">Ajukan Kegiatan</div>
                            <div style="font-size:0.75rem; opacity:0.85;">Buat pengajuan kegiatan baru</div>
                        </div>
                        <i class="fas fa-arrow-right ms-auto" style="font-size:0.8rem; opacity:0.7;"></i>
                    </a>

                    <a href="#" class="btn-aksi btn-aksi-green">
                        <div class="btn-aksi-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold small">Upload Dokumentasi</div>
                            <div style="font-size:0.75rem; opacity:0.85;">Upload foto atau dokumen kegiatan</div>
                        </div>
                        <i class="fas fa-arrow-right ms-auto" style="font-size:0.8rem; opacity:0.7;"></i>
                    </a>

                    <a href="#" class="btn-aksi btn-aksi-red">
                        <div class="btn-aksi-icon">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold small">Upload Laporan</div>
                            <div style="font-size:0.75rem; opacity:0.85;">Upload laporan kegiatan</div>
                        </div>
                        <i class="fas fa-arrow-right ms-auto" style="font-size:0.8rem; opacity:0.7;"></i>
                    </a>

                </div>
            </div>

            {{-- JADWAL TERDEKAT --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-semibold">Jadwal Terdekat</h6>
                    <a href="#" class="text-primary small fw-semibold text-decoration-none">Lihat Semua</a>
                </div>

                <div class="card-body p-0">
                    @forelse($jadwalMendatang ?? [] as $item)
                        <div class="jadwal-item">
                            <div class="jadwal-date-box">
                                <div class="jadwal-day">{{ $item->tanggal_mulai?->format('d') }}</div>
                                <div class="jadwal-month">{{ strtoupper($item->tanggal_mulai?->translatedFormat('M')) }}
                                </div>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-semibold small text-truncate">{{ $item->nama_kegiatan }}</div>
                                <div class="text-muted" style="font-size:0.75rem; text-truncate;">{{ $item->lokasi }}
                                </div>
                            </div>
                            <div class="jadwal-time">
                                <i class="far fa-clock me-1" style="font-size:0.7rem;"></i>
                                {{ $item->tanggal_mulai?->format('H:i') }} WIB
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4 small">Tidak ada jadwal mendatang</div>
                    @endforelse
                </div>

                <div class="card-footer bg-white border-top text-center py-2">
                    <a href="#" class="text-primary small fw-semibold text-decoration-none">
                        Lihat Semua Jadwal <i class="fas fa-arrow-right ms-1" style="font-size:0.7rem;"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

@endsection

<style>
    /* ===== STAT CARDS ===== */
    .stat-card {
        border-radius: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .stat-icon-wrap {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }

    .stat-blue {
        background: rgba(37, 99, 235, 0.1);
        color: #2563eb;
    }

    .stat-green {
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
    }

    .stat-red {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .stat-orange {
        background: rgba(245, 158, 11, 0.12);
        color: #d97706;
    }

    .stat-label {
        font-size: 0.78rem;
        color: #6b7280;
        margin-bottom: 2px;
    }

    .stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.1;
    }

    .stat-sub {
        font-size: 0.72rem;
        color: #9ca3af;
    }

    /* ===== TABLE ===== */
    .kegiatan-icon-wrap {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(37, 99, 235, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.85rem;
    }

    /* ===== STATUS BADGES ===== */
    .badge-status {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
    }

    .badge-disetujui {
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
    }

    .badge-ditolak {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .badge-menunggu {
        background: rgba(245, 158, 11, 0.12);
        color: #d97706;
    }

    /* ===== DOKUMENTASI ===== */
    .dokum-empty-box {
        width: 190px;
        min-width: 190px;
        border: 2px dashed #e5e7eb;
        border-radius: 12px;
        padding: 1rem 0.75rem;
    }

    .dokum-empty-icon {
        font-size: 2rem;
        color: #9ca3af;
    }

    .dokum-file-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.45rem 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .dokum-file-item:last-child {
        border-bottom: none;
    }

    .dokum-file-icon {
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    /* ===== AKSI CEPAT ===== */
    .btn-aksi {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        text-decoration: none;
        color: #fff;
        transition: opacity 0.2s, transform 0.15s;
    }

    .btn-aksi:hover {
        opacity: 0.9;
        transform: translateX(2px);
        color: #fff;
    }

    .btn-aksi-icon {
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .btn-aksi-blue {
        background: #2563eb;
    }

    .btn-aksi-green {
        background: #059669;
    }

    .btn-aksi-red {
        background: #dc2626;
    }

    /* ===== JADWAL TERDEKAT ===== */
    .jadwal-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.6rem 1rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .jadwal-item:last-child {
        border-bottom: none;
    }

    .jadwal-date-box {
        text-align: center;
        min-width: 36px;
    }

    .jadwal-day {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a1a2e;
        line-height: 1;
    }

    .jadwal-month {
        font-size: 0.65rem;
        font-weight: 600;
        color: #6b7280;
        letter-spacing: 0.5px;
    }

    .jadwal-time {
        font-size: 0.72rem;
        color: #6b7280;
        flex-shrink: 0;
        white-space: nowrap;
    }

    /* ===== CARD COMMON ===== */
    .card {
        border-radius: 12px;
    }

    .card-header {
        border-radius: 12px 12px 0 0 !important;
    }

    .min-w-0 {
        min-width: 0;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 575.98px) {
        .stat-value {
            font-size: 1.3rem;
        }

        .stat-icon-wrap {
            width: 44px;
            height: 44px;
            font-size: 1.1rem;
        }

        .dokum-empty-box {
            width: 150px;
            min-width: 150px;
        }
    }
</style>
