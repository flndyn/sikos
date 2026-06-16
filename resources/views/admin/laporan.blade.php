@extends('layouts.admin')

@section('content')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">

        <!-- HEADER -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Laporan Kegiatan</h5>
        </div>

        <!-- SEARCH + EXPORT -->
        <div class="card-body">
            <!-- TABLE -->
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Nama Kegiatan</th>
                        <th class="text-nowrap">Organisasi</th>
                        <th class="text-nowrap">Tanggal</th>
                        <th class="text-nowrap text-center">Status</th>
                        <th class="text-nowrap">Laporan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($laporan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->kegiatan?->nama_kegiatan ?? '-' }}</td>
                            <td>{{ $item->kegiatan?->organisasi?->nama_organisasi ?? '-' }}</td>
                            <td>{{ $item->kegiatan?->tanggal_mulai?->format('Y-m-d') ?? '-' }}</td>
                            <td class="text-center text-nowrap">
                                @if ($item->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif ($item->status === 'disetujui pembina')
                                    <span class="badge bg-success">Disetujui Pembina</span>
                                @elseif ($item->status === 'ditolak pembina')
                                    <span class="badge bg-danger d-block mb-1">Ditolak Pembina</span>
                                    @if ($item->keterangan)
                                        <small class="text-danger d-block text-wrap mx-auto" style="max-width: 150px;">
                                            Alasan: {{ $item->keterangan }}
                                        </small>
                                    @endif
                                @else
                                    <span class="badge bg-secondary">{{ $item->status ?? 'Pending' }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->file_laporan)
                                    <a href="{{ route('admin.laporan.download', $item) }}" class="btn btn-primary btn-sm"
                                        target="_blank" rel="noopener">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada file</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada laporan kegiatan.</td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>
    </div>
@endsection
