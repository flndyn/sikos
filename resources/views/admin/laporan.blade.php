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

            <div class="d-flex justify-content-between mb-3">

                <!-- EXPORT -->
                <a href="{{ route('admin.laporan.export-pdf') }}" class="btn btn-danger">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                </a>

            </div>

            <!-- TABLE -->
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Nama Kegiatan</th>
                        <th class="text-nowrap">Organisasi</th>
                        <th class="text-nowrap">Tanggal</th>
                        <th class="text-nowrap">Laporan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($laporan as $item)
                        @php
                            $fileUrl = null;

                            if ($item->file_laporan) {
                                $fileUrl = \Illuminate\Support\Str::startsWith($item->file_laporan, 'http')
                                    ? $item->file_laporan
                                    : asset('storage/' . $item->file_laporan);
                            }
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->kegiatan?->nama_kegiatan ?? '-' }}</td>
                            <td>{{ $item->kegiatan?->organisasi?->nama_organisasi ?? '-' }}</td>
                            <td>{{ $item->kegiatan?->tanggal_mulai?->format('Y-m-d') ?? '-' }}</td>
                            <td>
                                @if ($fileUrl)
                                    <a href="{{ route('admin.laporan.download', $item->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada file</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada laporan kegiatan.</td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>
    </div>
@endsection
