@extends('layouts.pembina')

@section('title', 'Data Kegiatan')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Data Kegiatan Organisasi Binaan</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Organisasi</th>
                        <th class="text-nowrap">Nama Kegiatan</th>
                        <th class="text-nowrap">Tanggal Mulai</th>
                        <th class="text-nowrap">Tempat</th>
                        <th class="text-nowrap">Proposal</th>
                        <th class="text-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kegiatan as $item)
                        @php
                            $badgeClass = match ($item->status) {
                                'disetujui admin' => 'bg-success',
                                'disetujui pembina' => 'bg-info text-dark',
                                'ditolak admin', 'ditolak pembina' => 'bg-danger',
                                default => 'bg-warning text-dark',
                            };

                            $statusLabel = match ($item->status) {
                                'disetujui admin' => 'Disetujui Admin',
                                'disetujui pembina' => 'Disetujui Pembina',
                                'ditolak admin' => 'Ditolak Admin',
                                'ditolak pembina' => 'Ditolak Pembina',
                                default => 'Pending',
                            };
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->organisasi?->nama_organisasi ?? '-' }}</td>
                            <td>{{ $item->nama_kegiatan }}</td>
                            <td>{{ $item->tanggal_mulai?->format('d-m-Y') ?? '-' }}</td>
                            <td>{{ $item->tempat ?? '-' }}</td>
                            <td class="text-center">
                                @if ($item->proposal)
                                    @php
                                        $proposalUrl = \Illuminate\Support\Str::startsWith($item->proposal, 'http')
                                            ? $item->proposal
                                            : (\Illuminate\Support\Str::startsWith(
                                                $item->proposal,
                                                'proposal-kegiatan/',
                                            )
                                                ? asset('storage/' . $item->proposal)
                                                : asset('storage/proposal-kegiatan/' . $item->proposal));
                                    @endphp
                                    <a href="{{ $proposalUrl }}" class="btn btn-danger btn-sm" target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td><span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data kegiatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
