@extends('layouts.ketua')

@section('title', 'Dashboard')

@section('content')

    {{-- ===== STAT CARDS ===== --}}
    <div class="row g-3 mb-4">

        {{-- Total Kegiatan --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">

                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-4 bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-calendar-alt fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Kegiatan</div>
                            <div class="fs-2 fw-bold">{{ $stats['total_kegiatan'] ?? 0 }}</div>
                            <div class="text-secondary small">Semua kegiatan</div>
                        </div>
                    </div>
                </div>

                <div class="border-bottom-primary"></div>
            </div>
        </div>

        {{-- Kegiatan Disetujui --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">

                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-4 bg-success bg-opacity-10 text-success">
                            <i class="fas fa-check-circle fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Kegiatan Disetujui</div>
                            <div class="fs-2 fw-bold">{{ $stats['kegiatan_disetujui_admin'] ?? 0 }}</div>
                            <div class="text-secondary small">Telah disetujui</div>
                        </div>
                    </div>
                </div>

                <div class="border-bottom-success"></div>
            </div>
        </div>

        {{-- Kegiatan Ditolak --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">

                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-4 bg-danger bg-opacity-10 text-danger">
                            <i class="fas fa-times-circle fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Kegiatan Ditolak</div>
                            <div class="fs-2 fw-bold">{{ $stats['kegiatan_ditolak'] ?? 0 }}</div>
                            <div class="text-secondary small">Ditolak</div>
                        </div>
                    </div>
                </div>

                <div class="border-bottom-danger"></div>
            </div>
        </div>

        {{-- Menunggu Persetujuan --}}
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">

                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="p-3 rounded-4 bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-hourglass-half fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Menunggu Persetujuan</div>
                            <div class="fs-2 fw-bold">{{ $stats['kegiatan_pending'] ?? 0 }}</div>
                            <div class="text-secondary small">Perlu persetujuan</div>
                        </div>
                    </div>
                </div>

                <div class="border-bottom-warning"></div>
            </div>
        </div>

    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="row g-3">

        {{-- ===== KIRI (8 col) ===== --}}
        <div class="col-12 col-lg-8 d-flex flex-column gap-3">

            {{-- TABLE: Daftar Kegiatan Terbaru --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-3">
                    <h6 class="mb-0 fw-semibold">Daftar Kegiatan Terbaru</h6>
                    <a href="{{ route('ketua.kegiatan') }}" class="text-primary small fw-semibold text-decoration-none">
                        Lihat Semua <i class="fas fa-arrow-right ms-1" style="font-size:0.7rem;"></i>
                    </a>
                </div>

                <div class="card-body p-0">
                    <table class="table table-hover mb-0 align-middle" data-no-search="1">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width:45px;">No</th>
                                <th>Nama Kegiatan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kegiatanTerbaru as $item)
                                <tr>
                                    <td class="ps-3 text-muted fw-semibold" style="font-size:0.9rem;">{{ $loop->iteration }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            {{-- Ikon berwarna sesuai jenis --}}
                                            @php
                                                $namaKegiatan = strtolower($item->nama_kegiatan ?? '');
                                                if (strpos($namaKegiatan, 'seminar') !== false) {
                                                    $icon = 'fa-users';
                                                    $iconClass = 'kegiatan-icon-blue';
                                                } elseif (
                                                    strpos($namaKegiatan, 'pelatihan') !== false ||
                                                    strpos($namaKegiatan, 'workshop') !== false
                                                ) {
                                                    $icon = 'fa-palette';
                                                    $iconClass = 'kegiatan-icon-warning';
                                                } elseif (
                                                    strpos($namaKegiatan, 'bakti') !== false ||
                                                    strpos($namaKegiatan, 'sosial') !== false
                                                ) {
                                                    $icon = 'fa-heart';
                                                    $iconClass = 'kegiatan-icon-danger';
                                                } elseif (strpos($namaKegiatan, 'donor') !== false) {
                                                    $icon = 'fa-heart-pulse';
                                                    $iconClass = 'kegiatan-icon-danger';
                                                } elseif (
                                                    strpos($namaKegiatan, 'speaking') !== false ||
                                                    strpos($namaKegiatan, 'presentasi') !== false
                                                ) {
                                                    $icon = 'fa-microphone';
                                                    $iconClass = 'kegiatan-icon-info';
                                                } else {
                                                    $icon = 'fa-calendar';
                                                    $iconClass = 'kegiatan-icon-blue';
                                                }
                                            @endphp
                                            <div class="kegiatan-icon-wrap {{ $iconClass }}">
                                                <i class="fas {{ $icon }}"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold" style="font-size:0.95rem;">
                                                    {{ $item->nama_kegiatan }}</div>
                                                <div class="text-muted" style="font-size:0.8rem;">
                                                    {{ $item->organisasi?->nama_organisasi }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted" style="font-size:0.9rem;">
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

                    <div class="text-center py-3 border-top">
                        <a href="{{ route('ketua.kegiatan') }}"
                            class="text-primary small fw-semibold text-decoration-none">
                            Lihat Semua Kegiatan <i class="fas fa-arrow-right ms-1" style="font-size:0.7rem;"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- DOKUMENTASI --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-semibold">Dokumentasi Terakhir Diunggah</h6>
                    <a href="{{ route('ketua.dokumentasi') }}"
                        class="text-primary small fw-semibold text-decoration-none">Lihat Semua</a>
                </div>

                <div class="card-body d-flex gap-3 align-items-start py-3">
                    @php $dokList = (isset($dokumentasiTerbaru) && is_iterable($dokumentasiTerbaru)) ? $dokumentasiTerbaru : []; @endphp

                    @if (count($dokList) > 0)
                        {{-- File List --}}
                        <div class="flex-grow-1">
                            @forelse($dokList as $dok)
                                <div class="dokum-file-item">
                                    <div class="dokum-file-icon">
                                        @php
                                            $ext = pathinfo($dok->nama_file ?? '', PATHINFO_EXTENSION);
                                            $type = $dok->type ?? 'dokumentasi';
                                        @endphp
                                        @if ($type === 'laporan')
                                            <i class="fas fa-file-contract text-warning"></i>
                                        @elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
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
                                <div class="text-muted small">Tidak ada data</div>
                            @endforelse
                        </div>
                    @else
                        {{-- Empty / Upload CTA --}}
                        <div class="dokum-empty-box text-center">
                            <div class="dokum-empty-icon mb-2">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="small fw-semibold mb-1">Belum ada dokumentasi</div>
                            <div class="text-muted" style="font-size:0.72rem; line-height:1.3;">Upload dokumentasi
                                kegiatan<br>untuk menampilkan di sini</div>
                            <a href="{{ route('ketua.dokumentasi') }}" class="btn btn-primary btn-sm mt-2 px-3">Upload
                                Sekarang</a>
                        </div>
                    @endif
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

                <div class="card-body d-flex flex-column gap-2 py-3 px-2">

                    <a href="{{ route('ketua.kegiatan') }}" class="btn-aksi btn-aksi-blue">
                        <div class="btn-aksi-icon">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div class="btn-aksi-text">
                            <div class="fw-semibold small">Ajukan Kegiatan</div>
                            <div style="font-size:0.75rem; opacity:0.85;">Buat pengajuan kegiatan baru</div>
                        </div>
                        <div class="btn-aksi-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <a href="{{ route('ketua.dokumentasi') }}" class="btn-aksi btn-aksi-green">
                        <div class="btn-aksi-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="btn-aksi-text">
                            <div class="fw-semibold small">Upload Dokumentasi</div>
                            <div style="font-size:0.75rem; opacity:0.85;">Upload foto atau dokumen kegiatan</div>
                        </div>
                        <div class="btn-aksi-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                    <a href="{{ route('ketua.laporan') }}" class="btn-aksi btn-aksi-red">
                        <div class="btn-aksi-icon">
                            <i class="fas fa-file-upload"></i>
                        </div>
                        <div class="btn-aksi-text">
                            <div class="fw-semibold small">Upload Laporan</div>
                            <div style="font-size:0.75rem; opacity:0.85;">Upload laporan kegiatan</div>
                        </div>
                        <div class="btn-aksi-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>

                </div>
            </div>

            {{-- JADWAL TERDEKAT --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h6 class="mb-0 fw-semibold">Jadwal Terdekat</h6>
                    <a href="{{ route('ketua.jadwal') }}"
                        class="text-primary small fw-semibold text-decoration-none">Lihat Semua</a>
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
                                <div class="text-muted" style="font-size:0.75rem; text-truncate;">{{ $item->tempat }}
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
                    <a href="{{ route('ketua.jadwal') }}" class="text-primary small fw-semibold text-decoration-none">
                        Lihat Semua Jadwal <i class="fas fa-arrow-right ms-1" style="font-size:0.7rem;"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

@endsection

<style>
    /* ===== STAT CARDS ===== */

    .border-bottom-primary,
    .border-bottom-success,
    .border-bottom-danger,
    .border-bottom-warning {
        height: 4px;
        width: 100%;
    }

    .border-bottom-primary {
        background-color: #0d6efd;
    }

    .border-bottom-success {
        background-color: #198754;
    }

    .border-bottom-danger {
        background-color: #dc3545;
    }

    .border-bottom-warning {
        background-color: #ffc107;
    }

    .stat-card {
        border-radius: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
        border-bottom: 4px solid #ccc;
    }

    .stat-card.stat-card-blue {
        border-bottom-color: #2563eb;
    }

    .stat-card.stat-card-green {
        border-bottom-color: #059669;
    }

    .stat-card.stat-card-red {
        border-bottom-color: #dc2626;
    }

    .stat-card.stat-card-orange {
        border-bottom-color: #d97706;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08) !important;
    }

    .stat-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
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
        font-weight: 500;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.1;
    }

    .stat-sub {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-top: 4px;
    }

    /* ===== TABLE ===== */
    .kegiatan-icon-wrap {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }

    .kegiatan-icon-wrap.kegiatan-icon-blue {
        background: rgba(13, 110, 253, 0.15);
        color: #0d6efd;
    }

    .kegiatan-icon-wrap.kegiatan-icon-warning {
        background: rgba(255, 193, 7, 0.15);
        color: #ff9800;
    }

    .kegiatan-icon-wrap.kegiatan-icon-danger {
        background: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }

    .kegiatan-icon-wrap.kegiatan-icon-info {
        background: rgba(23, 162, 184, 0.15);
        color: #17a2b8;
    }

    .kegiatan-icon-wrap i {
        color: inherit;
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
        justify-content: space-between;
        gap: 1rem;
        padding: 0.9rem 1rem;
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
        font-size: 1.3rem;
        flex-shrink: 0;
        width: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-aksi-text {
        flex-grow: 1;
        text-align: start;
    }

    .btn-aksi-arrow {
        font-size: 0.9rem;
        flex-shrink: 0;
        width: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.8;
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
