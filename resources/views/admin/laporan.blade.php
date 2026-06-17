@extends('layouts.admin')

@section('title', 'Laporan Kegiatan')

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

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validasi Gagal!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <!-- HEADER -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Laporan Kegiatan</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.laporan.export-pdf') }}" class="btn btn-danger btn-sm">
                    <i class="bi bi-file-pdf"></i> Export PDF
                </a>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#tambahLaporanModal">
                    <i class="bi bi-plus"></i> Tambah Laporan
                </button>
            </div>
        </div>

        <!-- BODY -->
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Nama Kegiatan</th>
                        <th class="text-nowrap">Organisasi</th>
                        <th class="text-nowrap">Tanggal</th>
                        <th class="text-nowrap text-center">Status</th>
                        <th class="text-nowrap">Isi Laporan</th>
                        <th class="text-nowrap text-center">Laporan</th>
                        <th class="text-nowrap">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($laporan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->kegiatan?->nama_kegiatan ?? '-' }}</td>
                            <td>{{ $item->kegiatan?->organisasi?->nama_organisasi ?? '-' }}</td>
                            <td>{{ $item->kegiatan?->tanggal_mulai?->format('d-m-Y') ?? '-' }}</td>
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
                                    <span class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($item->isi_laporan, 50) }}</td>
                            <td class="text-center">
                                @if ($item->file_laporan)
                                    @php
                                        $fileUrl = \Illuminate\Support\Str::startsWith($item->file_laporan, 'http')
                                            ? $item->file_laporan
                                            : route('admin.laporan.download', $item);
                                    @endphp
                                    <a href="{{ $fileUrl }}" class="btn btn-info btn-sm text-white" target="_blank"
                                        title="Download Laporan">
                                        <i class="bi bi-download"></i>
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada file</span>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                <div class="d-inline-flex flex-nowrap gap-1">
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editLaporanModal{{ $item->id }}" title="Edit">
                                        <i class="bi bi-pencil text-white"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#hapusLaporanModal{{ $item->id }}" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
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

    <!-- MODAL TAMBAH LAPORAN -->
    <div class="modal fade" id="tambahLaporanModal" tabindex="-1" aria-labelledby="tambahLaporanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tambahLaporanModalLabel">Tambah Laporan Baru</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('admin.laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kegiatan_id" class="form-label">Kegiatan *</label>
                            <select class="form-select @error('kegiatan_id') is-invalid @enderror" id="kegiatan_id"
                                name="kegiatan_id" required>
                                <option value="" selected>-- Pilih Kegiatan --</option>
                                @foreach ($kegiatanTersedia as $keg)
                                    <option value="{{ $keg->id }}"
                                        {{ old('kegiatan_id') == $keg->id ? 'selected' : '' }}>
                                        {{ $keg->nama_kegiatan ?? 'Tanpa Nama' }} ({{ $keg->tanggal_mulai?->format('d-m-Y') ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('kegiatan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="isi_laporan" class="form-label">Isi Laporan *</label>
                            <textarea class="form-control @error('isi_laporan') is-invalid @enderror" id="isi_laporan" name="isi_laporan"
                                rows="5" required>{{ old('isi_laporan') }}</textarea>
                            @error('isi_laporan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="file_laporan" class="form-label">File Laporan *</label>
                            <input type="file" class="form-control @error('file_laporan') is-invalid @enderror"
                                id="file_laporan" name="file_laporan" accept=".pdf,.doc,.docx" required>
                            @error('file_laporan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT LAPORAN -->
    @foreach ($laporan as $item)
        <div class="modal fade" id="editLaporanModal{{ $item->id }}" tabindex="-1"
            aria-labelledby="editLaporanModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editLaporanModalLabel{{ $item->id }}">Edit Laporan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('admin.laporan.update', $item->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Kegiatan</label>
                                <input type="text" class="form-control" value="{{ $item->kegiatan?->nama_kegiatan ?? '-' }}" readonly disabled>
                            </div>

                            <div class="mb-3">
                                <label for="edit_isi_laporan_{{ $item->id }}" class="form-label">Isi Laporan *</label>
                                <textarea class="form-control" id="edit_isi_laporan_{{ $item->id }}" name="isi_laporan" rows="5" required>{{ $item->isi_laporan }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="edit_file_laporan_{{ $item->id }}" class="form-label">File Laporan</label>
                                @if ($item->file_laporan)
                                    @php
                                        $currFileUrl = \Illuminate\Support\Str::startsWith($item->file_laporan, 'http')
                                            ? $item->file_laporan
                                            : route('admin.laporan.download', $item);
                                    @endphp
                                    <div class="mb-2">
                                        <a href="{{ $currFileUrl }}" target="_blank" class="small">
                                            Lihat file laporan saat ini
                                        </a>
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="edit_file_laporan_{{ $item->id }}" name="file_laporan" accept=".pdf,.doc,.docx">
                                <div class="form-text">Kosongkan jika tidak ingin mengganti file laporan.</div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- MODAL HAPUS LAPORAN -->
    @foreach ($laporan as $item)
        <div class="modal fade" id="hapusLaporanModal{{ $item->id }}" tabindex="-1"
            aria-labelledby="hapusLaporanModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="hapusLaporanModalLabel{{ $item->id }}">Konfirmasi Hapus</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus laporan kegiatan <strong>{{ $item->kegiatan?->nama_kegiatan ?? '-' }}</strong>?</p>
                        <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('admin.laporan.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Auto-open modal on validation errors -->
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('tambahLaporanModal'));
                modal.show();
            });
        </script>
    @endif
@endsection
