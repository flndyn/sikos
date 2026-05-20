@extends('layouts.admin')

@section('title', 'Validasi Kegiatan')

@section('content')

    <!-- Alert Messages -->
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

        <!-- HEADER -->
        <div class="card-header">
            <h5 class="mb-0">Validasi Kegiatan (Disetujui Pembina)</h5>
        </div>

        <!-- BODY -->
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Organisasi</th>
                        <th class="text-nowrap">Nama Kegiatan</th>
                        <th class="text-nowrap">Deskripsi</th>
                        <th class="text-nowrap">Tanggal</th>
                        <th class="text-nowrap">Proposal</th>
                        <th class="text-nowrap">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($kegiatanMenungguValidasiAdmin as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->organisasi?->nama_organisasi ?? '-' }}</td>
                            <td>{{ $item->nama_kegiatan }}</td>
                            <td>
                                {{ \Illuminate\Support\Str::limit($item->deskripsi ?? '-', 40) }}
                            </td>
                            <td class="text-nowrap">
                                @if ($item->tanggal_mulai)
                                    {{ $item->tanggal_mulai->format('d-m-Y') }}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="text-center">
                                @if ($item->proposal)
                                    @php
                                        $proposalUrl = \Illuminate\Support\Str::startsWith($item->proposal, 'http')
                                            ? $item->proposal
                                            : route('proposal.download', $item);
                                    @endphp
                                    <a href="{{ $proposalUrl }}" class="btn btn-danger btn-sm" target="_blank"
                                        title="Lihat PDF Proposal">
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
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted mb-0">Tidak ada kegiatan dengan status disetujui pembina</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL APPROVE (Per Row) -->
    @foreach ($kegiatanMenungguValidasiAdmin as $item)
        <div class="modal fade" id="approveModal{{ $item->id }}" tabindex="-1"
            aria-labelledby="approveModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="approveModalLabel{{ $item->id }}">Konfirmasi Persetujuan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin <strong>menyetujui</strong> kegiatan berikut?</p>
                        <div class="alert alert-info" role="alert">
                            <strong>{{ $item->nama_kegiatan }}</strong><br>
                            <small>{{ $item->organisasi?->nama_organisasi ?? '-' }}</small>
                        </div>
                        <p class="text-muted small mb-0">Status akan diubah dari "Disetujui Pembina" menjadi "Disetujui
                            Admin".</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('admin.validasi.approve', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">Ya, Setujui</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- MODAL REJECT (Per Row) -->
    @foreach ($kegiatanMenungguValidasiAdmin as $item)
        <div class="modal fade reject-modal" id="rejectModal{{ $item->id }}" tabindex="-1"
            aria-labelledby="rejectModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('admin.validasi.reject', $item->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="rejectModalLabel{{ $item->id }}">Konfirmasi Penolakan
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin <strong>menolak</strong> kegiatan berikut?</p>
                            <div class="alert alert-warning" role="alert">
                                <strong>{{ $item->nama_kegiatan }}</strong><br>
                                <small>{{ $item->organisasi?->nama_organisasi ?? '-' }}</small>
                            </div>
                            <div class="mb-0">
                                <label for="keterangan_reject_{{ $item->id }}" class="form-label">Keterangan
                                    Penolakan
                                    *</label>
                                <div id="keterangan_reject_{{ $item->id }}" class="d-grid gap-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="keterangan"
                                            id="admin_reason_jadwal_{{ $item->id }}"
                                            value="Jadwal bentrok dengan kegiatan lain">
                                        <label class="form-check-label" for="admin_reason_jadwal_{{ $item->id }}">
                                            Jadwal bentrok dengan kegiatan lain
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="keterangan"
                                            id="admin_reason_kebijakan_{{ $item->id }}"
                                            value="Melanggar kebijakan sekolah">
                                        <label class="form-check-label" for="admin_reason_kebijakan_{{ $item->id }}">
                                            Melanggar kebijakan sekolah
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="keterangan_custom_{{ $item->id }}" class="form-label">Alasan lain
                                        (opsional)
                                    </label>
                                    <textarea class="form-control" id="keterangan_custom_{{ $item->id }}" name="keterangan_custom" rows="2"
                                        placeholder="Tuliskan alasan tambahan jika perlu"></textarea>
                                    <p class="form-text text-muted small mb-0">Opsi di atas bersifat pilihan, dan Anda
                                        tetap dapat menolak tanpa memilih opsi atau mengisi teks.</p>
                                </div>
                            </div>
                            <p class="text-muted small mb-0">Status akan diubah dari "Disetujui Pembina" menjadi "Ditolak
                                Admin".</p>
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

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.modal.reject-modal').forEach(function(modalEl) {
                modalEl.addEventListener('hidden.bs.modal', function() {
                    var form = modalEl.querySelector('form');
                    if (form) {
                        form.reset();
                    }
                });
            });
        });
    </script>
@endsection
