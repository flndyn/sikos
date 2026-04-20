@extends('layouts.ketua')

@section('title', 'Jadwal Kegiatan')

@section('content')

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Jadwal Kegiatan</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Jadwal menampilkan kegiatan organisasi Anda yang sudah disetujui pembina atau admin.
            </div>

            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Tanggal Mulai</th>
                        <th>Tempat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($jadwalKegiatan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_kegiatan }}</td>
                            <td>{{ $item->tanggal_mulai->format('d-m-Y') }}</td>
                            <td>{{ $item->tempat }}</td>
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
                            <td colspan="5" class="text-center py-4">
                                <p class="text-muted mb-0">Belum ada jadwal kegiatan yang disetujui.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
