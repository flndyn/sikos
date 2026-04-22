@extends('layouts.pembina')

@section('title', 'Validasi Kegiatan')

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Validasi Kegiatan (Status Pending)</h5>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Organisasi</th>
                        <th class="text-nowrap">Nama Kegiatan</th>
                        <th class="text-nowrap">Deskripsi</th>
                        <th class="text-nowrap">Tanggal</th>
                        <th class="text-nowrap">Proposal</th>
                        <th class="text-nowrap text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kegiatanMenungguValidasiPembina as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->organisasi?->nama_organisasi ?? '-' }}</td>
                            <td>{{ $item->nama_kegiatan }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->deskripsi ?? '-', 40) }}</td>
                            <td>{{ $item->tanggal_mulai?->format('d-m-Y') ?? '-' }}</td>
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
                            <td class="text-nowrap">
                                <div class="d-inline-flex flex-nowrap gap-1">
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#approveModal{{ $item->id }}">
                                        <i class="bi bi-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#rejectModal{{ $item->id }}">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada kegiatan yang menunggu validasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($kegiatanMenungguValidasiPembina as $item)
        <div class="modal fade" id="approveModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Persetujuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Setujui kegiatan <strong>{{ $item->nama_kegiatan }}</strong>?</p>
                        <p class="text-muted small mb-0">Status akan menjadi Disetujui Pembina.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('pembina.validasi.approve', $item->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Ya, Setujui</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="rejectModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('pembina.validasi.reject', $item->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Penolakan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Tolak kegiatan <strong>{{ $item->nama_kegiatan }}</strong>?</p>
                            <div class="mb-0">
                                <label for="keterangan_reject_{{ $item->id }}" class="form-label">Keterangan Penolakan
                                    *</label>
                                <div id="keterangan_reject_{{ $item->id }}" class="d-grid gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="keterangan"
                                            id="pembina_reason_proposal_{{ $item->id }}" value="Proposal belum lengkap" required>
                                        <label class="form-check-label" for="pembina_reason_proposal_{{ $item->id }}">
                                            Proposal belum lengkap
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="keterangan"
                                            id="pembina_reason_anggaran_{{ $item->id }}" value="Anggaran tidak sesuai" required>
                                        <label class="form-check-label" for="pembina_reason_anggaran_{{ $item->id }}">
                                            Anggaran tidak sesuai
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="keterangan"
                                            id="pembina_reason_deskripsi_{{ $item->id }}" value="Deskripsi atau data kegiatan kurang jelas" required>
                                        <label class="form-check-label" for="pembina_reason_deskripsi_{{ $item->id }}">
                                            Deskripsi atau data kegiatan kurang jelas
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Ya, Tolak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection
