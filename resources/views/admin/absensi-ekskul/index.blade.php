@extends('layouts.admin')

@section('title', 'Absensi Ekstrakurikuler')

@section('content')

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Monitoring Absensi Ekstrakurikuler</h5>
        </div>
        <div class="card-body">
            {{-- Search --}}
            <form method="GET" action="{{ route('admin.absensi-ekskul') }}" class="mb-3">
                <div class="input-group" style="max-width: 400px;">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari organisasi..." value="{{ $search }}">
                    <button class="btn btn-outline-secondary btn-sm" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    @if ($search)
                        <a href="{{ route('admin.absensi-ekskul') }}" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Nama Organisasi</th>
                            <th class="text-center">Jumlah Anggota</th>
                            <th class="text-center">Total Pertemuan</th>
                            <th style="width: 180px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($organisasiList as $org)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $org->nama_organisasi }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $org->anggota_count }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $org->pertemuan_ekskul_count }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.absensi-ekskul.show', $org) }}"
                                        class="btn btn-info btn-sm">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                    @if ($org->pertemuan_ekskul_count > 0)
                                        <a href="{{ route('admin.absensi-ekskul.export-pdf', $org) }}"
                                            class="btn btn-danger btn-sm" title="Export PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-building fa-2x mb-2 d-block opacity-50"></i>
                                    Belum ada organisasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
