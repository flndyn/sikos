@extends('layouts.pembina')

@section('title', 'Laporan Kegiatan')

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
                        <th class="text-nowrap text-center">Status</th>
                        <th class="text-nowrap text-center">File</th>
                        <th class="text-nowrap text-center">Aksi</th>
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
                            <td class="text-center">
                                @if ($item->file_laporan)
                                    <a href="{{ route('pembina.laporan.download', $item->id) }}"
                                        class="btn btn-primary btn-sm text-white">
                                        <i class="bi bi-download"></i>
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada file</span>
                                @endif
                            </td>
                            <td class="text-nowrap text-center">
                                @if ($item->status === 'pending')
                                    <div class="d-inline-flex flex-nowrap gap-1">
                                        <button type="button" class="btn btn-success btn-sm text-white" data-bs-toggle="modal"
                                            data-bs-target="#approveModal{{ $item->id }}">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm text-white" data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $item->id }}">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada laporan kegiatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($laporan as $item)
        @if ($item->status === 'pending')
            <!-- Approve Modal -->
            <div class="modal fade" id="approveModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Konfirmasi Persetujuan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Setujui laporan kegiatan untuk <strong>{{ $item->kegiatan?->nama_kegiatan ?? '-' }}</strong>?</p>
                            <p class="text-muted small mb-0">Status akan menjadi Disetujui Pembina.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('pembina.laporan.approve', $item->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success text-white">Ya, Setujui</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade reject-modal" id="rejectModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('pembina.laporan.reject', $item->id) }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Konfirmasi Penolakan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Tolak laporan kegiatan untuk <strong>{{ $item->kegiatan?->nama_kegiatan ?? '-' }}</strong>?</p>
                                <div class="mb-0">
                                    <label class="form-label">Keterangan Penolakan *</label>
                                    <div class="d-grid gap-2 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="keterangan"
                                                id="pembina_reason_lengkap_{{ $item->id }}"
                                                value="Laporan belum lengkap">
                                            <label class="form-check-label" for="pembina_reason_lengkap_{{ $item->id }}">
                                                Laporan belum lengkap
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="keterangan"
                                                id="pembina_reason_format_{{ $item->id }}" value="Format file tidak sesuai">
                                            <label class="form-check-label"
                                                for="pembina_reason_format_{{ $item->id }}">
                                                Format file tidak sesuai
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="keterangan"
                                                id="pembina_reason_dokumentasi_{{ $item->id }}"
                                                value="Data dokumentasi kurang jelas">
                                            <label class="form-check-label"
                                                for="pembina_reason_dokumentasi_{{ $item->id }}">
                                                Data dokumentasi kurang jelas
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="keterangan_custom_{{ $item->id }}" class="form-label">Alasan lain (opsional)</label>
                                        <textarea class="form-control" id="keterangan_custom_{{ $item->id }}" name="keterangan_custom" rows="2"
                                            placeholder="Tuliskan alasan tambahan jika perlu"></textarea>
                                        <p class="form-text text-muted small mb-0">Opsi di atas bersifat pilihan, dan Anda tetap dapat menolak tanpa memilih opsi atau mengisi teks.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger text-white">Ya, Tolak</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
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
