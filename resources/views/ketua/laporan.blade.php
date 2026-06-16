@extends('layouts.ketua')

@section('title', 'Laporan Kegiatan')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Laporan Kegiatan</h5>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#unggahLaporanModal">
                <i class="bi bi-upload me-1"></i> Unggah Laporan
            </button>
        </div>

        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Nama Kegiatan</th>
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
                            <td>{{ $item->kegiatan?->nama_kegiatan ?? '-' }}</td>
                            <td>{{ $item->kegiatan?->tanggal_mulai?->format('d-m-Y') ?? '-' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->isi_laporan ?? '-', 120) }}</td>
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
                            <td class="text-nowrap text-center">
                                @if ($item->file_laporan)
                                    <a href="{{ route('ketua.laporan.download', $item) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada file</span>
                                @endif
                            </td>
                            <td class="text-nowrap text-center">
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editLaporanModal{{ $item->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#hapusLaporanModal{{ $item->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                             <td colspan="7" class="text-center text-muted">Belum ada laporan kegiatan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="unggahLaporanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Unggah Laporan Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('ketua.laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kegiatan_id" class="form-label">Pilih Kegiatan <span
                                    class="text-danger">*</span></label>
                            <select name="kegiatan_id" id="kegiatan_id"
                                class="form-select @error('kegiatan_id') is-invalid @enderror" required>
                                <option value="">-- Pilih kegiatan --</option>
                                @foreach ($kegiatanTersedia as $kegiatan)
                                    <option value="{{ $kegiatan->id }}" @selected(old('kegiatan_id') == $kegiatan->id)>
                                        {{ $kegiatan->nama_kegiatan }}
                                        ({{ $kegiatan->tanggal_mulai?->format('d-m-Y') ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('kegiatan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="isi_laporan" class="form-label">Isi Laporan <span
                                    class="text-danger">*</span></label>
                            <textarea name="isi_laporan" id="isi_laporan" rows="5"
                                class="form-control @error('isi_laporan') is-invalid @enderror" placeholder="Tulis ringkasan hasil kegiatan"
                                required>{{ old('isi_laporan') }}</textarea>
                            @error('isi_laporan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label for="file_laporan" class="form-label">File Laporan <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="file_laporan" id="file_laporan"
                                class="form-control @error('file_laporan') is-invalid @enderror" accept=".pdf,.doc,.docx"
                                required>
                            <small class="text-muted">Format: PDF, DOC, DOCX (maksimal 5MB)</small>
                            @error('file_laporan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Unggah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($laporan as $item)
        <div class="modal fade" id="editLaporanModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Laporan Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('ketua.laporan.update', $item->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Kegiatan</label>
                                <input type="text" class="form-control"
                                    value="{{ $item->kegiatan?->nama_kegiatan ?? '-' }} ({{ $item->kegiatan?->tanggal_mulai?->format('d-m-Y') ?? '-' }})"
                                    disabled>
                                <small class="text-muted">Kegiatan tidak dapat diubah untuk menjaga konsistensi 1 kegiatan
                                    1 laporan.</small>
                            </div>

                            <div class="mb-3">
                                <label for="isi_laporan_{{ $item->id }}" class="form-label">Isi Laporan <span
                                        class="text-danger">*</span></label>
                                <textarea name="isi_laporan" id="isi_laporan_{{ $item->id }}" rows="5" class="form-control" required>{{ $item->isi_laporan }}</textarea>
                            </div>

                            <div>
                                <label for="file_laporan_{{ $item->id }}" class="form-label">Ganti File
                                    Laporan</label>
                                <input type="file" name="file_laporan" id="file_laporan_{{ $item->id }}"
                                    class="form-control" accept=".pdf,.doc,.docx">
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti file (PDF, DOC, DOCX
                                    maksimal 5MB)</small>
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

        <div class="modal fade" id="hapusLaporanModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Hapus laporan untuk kegiatan <strong>{{ $item->kegiatan?->nama_kegiatan ?? '-' }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('ketua.laporan.destroy', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
