@extends('layouts.pembina')

@section('title', 'Jadwal Kegiatan')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Jadwal Kegiatan Organisasi Binaan</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Jadwal menampilkan kegiatan yang sudah disetujui pembina atau admin.
            </div>

            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Organisasi</th>
                        <th class="text-nowrap">Nama Kegiatan</th>
                        <th class="text-nowrap">Tanggal Mulai</th>
                        <th class="text-nowrap">Tempat</th>
                        <th class="text-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwalKegiatan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->organisasi?->nama_organisasi ?? '-' }}</td>
                            <td>{{ $item->nama_kegiatan }}</td>
                            <td>{{ $item->tanggal_mulai?->format('d-m-Y') ?? '-' }}</td>
                            <td>{{ $item->tempat ?? '-' }}</td>
                            <td>
                                @if ($item->status === 'disetujui admin')
                                    <span class="badge bg-success">Disetujui Admin</span>
                                @else
                                    <span class="badge bg-primary">Disetujui Pembina</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada jadwal kegiatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
