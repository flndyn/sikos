@extends('layouts.pembina')

@section('title', 'Absensi Ekstrakurikuler')

@section('content')

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($organisasi)
        @if ($organisasiList->count() > 1)
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('pembina.absensi-ekskul') }}" class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Pilih Organisasi / Ekskul</label>
                            <select name="organisasi_id" class="form-select" onchange="this.form.submit()">
                                @foreach ($organisasiList as $org)
                                    <option value="{{ $org->id }}" {{ $selectedOrgId == $org->id ? 'selected' : '' }}>
                                        {{ $org->nama_organisasi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Quick Stats --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;">
                            <i class="fas fa-calendar-check text-primary fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Pertemuan</div>
                            <div class="fw-bold fs-5">{{ $pertemuan->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;">
                            <i class="fas fa-users text-success fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Total Anggota</div>
                            <div class="fw-bold fs-5">{{ $totalAnggota }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;">
                            <i class="fas fa-percentage text-info fs-5"></i>
                        </div>
                        <div>
                            <div class="text-muted small">Rata-rata Kehadiran</div>
                            @php
                                $totalHadir = $pertemuan->sum('hadir_count');
                                $totalAbsensi = $pertemuan->sum(fn($p) => $p->hadir_count + $p->izin_count + $p->sakit_count + $p->alfa_count);
                                $rataRata = $totalAbsensi > 0 ? round(($totalHadir / $totalAbsensi) * 100, 1) : 0;
                            @endphp
                            <div class="fw-bold fs-5">{{ $rataRata }}%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Riwayat Pertemuan</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('pembina.absensi-ekskul.rekap', ['organisasi_id' => $selectedOrgId]) }}" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-chart-bar me-1"></i> Rekap Kehadiran
                </a>
                <a href="{{ route('pembina.absensi-ekskul.create', ['organisasi_id' => $selectedOrgId]) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i> Input Absensi Baru
                </a>
            </div>
        </div>

        {{-- Tabel Riwayat Pertemuan --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Pertemuan</th>
                                <th>Tanggal</th>
                                <th>Semester</th>
                                <th>Tahun Ajaran</th>
                                <th class="text-center">
                                    <span class="badge bg-success">Hadir</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge bg-warning text-dark">Izin</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge bg-info">Sakit</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge bg-danger">Alfa</span>
                                </th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pertemuan as $item)
                                <tr>
                                    <td class="fw-semibold">Pertemuan {{ $item->pertemuan_ke }}</td>
                                    <td>{{ $item->tanggal->format('d M Y') }}</td>
                                    <td>{{ $item->semester }}</td>
                                    <td>{{ $item->tahun_ajaran }}</td>
                                    <td class="text-center">{{ $item->hadir_count }}</td>
                                    <td class="text-center">{{ $item->izin_count }}</td>
                                    <td class="text-center">{{ $item->sakit_count }}</td>
                                    <td class="text-center">{{ $item->alfa_count }}</td>
                                    <td>
                                        <a href="{{ route('pembina.absensi-ekskul.show', $item) }}"
                                            class="btn btn-info btn-sm text-white" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('pembina.absensi-ekskul.edit', $item) }}"
                                            class="btn btn-warning btn-sm text-white" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm text-white" data-bs-toggle="modal"
                                            data-bs-target="#modalHapusPertemuan{{ $item->id }}" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                {{-- Modal Hapus Pertemuan --}}
                                <div class="modal fade" id="modalHapusPertemuan{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Konfirmasi Hapus Pertemuan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <p>Apakah Anda yakin ingin menghapus <strong>Pertemuan {{ $item->pertemuan_ke }}</strong>?</p>
                                                <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data absensi beserta foto kegiatan terkait pertemuan ini.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('pembina.absensi-ekskul.destroy', $item) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fas fa-clipboard-list fa-2x mb-2 d-block opacity-50"></i>
                                        Belum ada data pertemuan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Anda belum menjadi pembina di organisasi manapun.
        </div>
    @endif

@endsection
