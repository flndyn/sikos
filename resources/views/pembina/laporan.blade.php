@extends('layouts.pembina')

@section('title', 'Laporan Kegiatan')

@section('content')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Laporan Kegiatan Organisasi Binaan</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Organisasi</th>
                        <th class="text-nowrap">Kegiatan</th>
                        <th class="text-nowrap">Tanggal</th>
                        <th class="text-nowrap">Isi Laporan</th>
                        <th class="text-nowrap text-center">File</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->kegiatan?->organisasi?->nama_organisasi ?? '-' }}</td>
                            <td>{{ $item->kegiatan?->nama_kegiatan ?? '-' }}</td>
                            <td>{{ $item->kegiatan?->tanggal_mulai?->format('d-m-Y') ?? '-' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->isi_laporan ?? '-', 100) }}</td>
                            <td>
                                @if ($item->file_laporan)
                                    <a href="{{ route('pembina.laporan.download', $item->id) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="bi bi-download"></i>
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
