@extends('layouts.pembina')

@section('title', 'Detail Pertemuan ' . $pertemuan->pertemuan_ke)

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('pembina.absensi-ekskul') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali ke Riwayat
        </a>
    </div>

    {{-- Info Pertemuan --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Pertemuan {{ $pertemuan->pertemuan_ke }}</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('pembina.absensi-ekskul.export-pertemuan-pdf', $pertemuan) }}" class="btn btn-danger btn-sm text-white">
                    <i class="fas fa-file-pdf me-1"></i> Export PDF
                </a>
                <a href="{{ route('pembina.absensi-ekskul.edit', $pertemuan) }}" class="btn btn-warning btn-sm text-white">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold text-muted small">Tanggal</label>
                    <p class="mb-0">{{ $pertemuan->tanggal->format('d F Y') }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold text-muted small">Semester</label>
                    <p class="mb-0">{{ $pertemuan->semester }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold text-muted small">Tahun Ajaran</label>
                    <p class="mb-0">{{ $pertemuan->tahun_ajaran }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold text-muted small">Pembina</label>
                    <p class="mb-0">{{ $pertemuan->pembina?->name ?? '-' }}</p>
                </div>
            </div>
            @if ($pertemuan->deskripsi_kegiatan)
                <div class="mt-2">
                    <label class="form-label fw-semibold text-muted small">Deskripsi Kegiatan</label>
                    <p class="mb-0">{{ $pertemuan->deskripsi_kegiatan }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Foto Kegiatan --}}
    @if ($pertemuan->fotoKegiatan->isNotEmpty())
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-images me-2"></i>Foto Kegiatan</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach ($pertemuan->fotoKegiatan as $foto)
                        <div class="col-md-4 col-sm-6">
                            <div class="card border">
                                <img src="{{ asset('storage/' . $foto->file_path) }}" class="card-img-top"
                                    alt="Foto Kegiatan" style="height: 200px; object-fit: cover; cursor: pointer;"
                                    data-bs-toggle="modal" data-bs-target="#fotoModal{{ $foto->id }}">
                                @if ($foto->keterangan)
                                    <div class="card-body p-2">
                                        <small class="text-muted">{{ $foto->keterangan }}</small>
                                    </div>
                                @endif
                            </div>

                            {{-- Modal Preview --}}
                            <div class="modal fade" id="fotoModal{{ $foto->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body p-0">
                                            <img src="{{ asset('storage/' . $foto->file_path) }}"
                                                class="w-100" alt="Foto Kegiatan">
                                        </div>
                                        @if ($foto->keterangan)
                                            <div class="modal-footer">
                                                <span class="text-muted">{{ $foto->keterangan }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- Daftar Absensi --}}
    <div class="card shadow-sm">
        <div class="card-header">
            <h6 class="mb-0"><i class="fas fa-user-check me-2"></i>Daftar Absensi</h6>
        </div>
        <div class="card-body">
            @php
                $hadir = $pertemuan->absensi->where('status', 'hadir')->count();
                $izin = $pertemuan->absensi->where('status', 'izin')->count();
                $sakit = $pertemuan->absensi->where('status', 'sakit')->count();
                $alfa = $pertemuan->absensi->where('status', 'alfa')->count();
            @endphp

            <div class="d-flex gap-3 mb-3 flex-wrap">
                <span class="badge bg-success fs-6">Hadir: {{ $hadir }}</span>
                <span class="badge bg-warning text-dark fs-6">Izin: {{ $izin }}</span>
                <span class="badge bg-info fs-6">Sakit: {{ $sakit }}</span>
                <span class="badge bg-danger fs-6">Alfa: {{ $alfa }}</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pertemuan->absensi->sortBy('anggota.nama') as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $item->anggota?->nama ?? '-' }}</td>
                                <td>{{ $item->anggota?->kelas ?? '-' }}</td>
                                <td>
                                    @switch($item->status)
                                        @case('hadir')
                                            <span class="badge bg-success">Hadir</span>
                                        @break
                                        @case('izin')
                                            <span class="badge bg-warning text-dark">Izin</span>
                                        @break
                                        @case('sakit')
                                            <span class="badge bg-info">Sakit</span>
                                        @break
                                        @case('alfa')
                                            <span class="badge bg-danger">Alfa</span>
                                        @break
                                    @endswitch
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
