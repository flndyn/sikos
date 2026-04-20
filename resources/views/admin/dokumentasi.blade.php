@extends('layouts.admin')

@section('title', 'Dokumentasi Kegiatan')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> {{ session('success') }}
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

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">Dokumentasi Kegiatan</h5>
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
            data-bs-target="#uploadDokumentasiModal">
            <i class="bi bi-plus"></i> Upload Dokumentasi
        </button>
    </div>

    <div class="row">
        @php
            $dokumentasiPerKegiatan = $dokumentasi->groupBy('kegiatan_id');
        @endphp

        @forelse ($dokumentasiPerKegiatan as $kegiatanId => $items)
            @php
                $firstItem = $items->first();
                $coverUrl = \Illuminate\Support\Str::startsWith($firstItem->file_dokumentasi ?? '', 'http')
                    ? $firstItem->file_dokumentasi
                    : asset('storage/' . $firstItem->file_dokumentasi);
            @endphp
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <img src="{{ $coverUrl }}" class="card-img-top"
                        alt="Dokumentasi {{ $firstItem->kegiatan?->nama_kegiatan }}"
                        style="height: 220px; object-fit: cover;">

                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-1">{{ $firstItem->kegiatan?->nama_kegiatan ?? '-' }}</h6>
                        <p class="text-muted small mb-2">Total {{ $items->count() }} gambar</p>

                        <p class="card-text mb-3">
                            {{ \Illuminate\Support\Str::limit($firstItem->keterangan ?? '-', 90) }}
                        </p>

                        <div class="mt-auto d-flex justify-content-end">
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#detailDokumentasiModal{{ $kegiatanId }}">
                                <i class="bi bi-images"></i> Detail
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5 bg-white border rounded">
                    <p class="text-muted mb-0">Belum ada dokumentasi kegiatan.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- MODAL DETAIL PER KEGIATAN -->
    @foreach ($dokumentasiPerKegiatan as $kegiatanId => $items)
        @php
            $firstItem = $items->first();
        @endphp
        <div class="modal fade" id="detailDokumentasiModal{{ $kegiatanId }}" tabindex="-1"
            aria-labelledby="detailDokumentasiModalLabel{{ $kegiatanId }}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="detailDokumentasiModalLabel{{ $kegiatanId }}">
                            Detail Dokumentasi - {{ $firstItem->kegiatan?->nama_kegiatan ?? '-' }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            @foreach ($items as $item)
                                @php
                                    $imageUrl = \Illuminate\Support\Str::startsWith(
                                        $item->file_dokumentasi ?? '',
                                        'http',
                                    )
                                        ? $item->file_dokumentasi
                                        : asset('storage/' . $item->file_dokumentasi);
                                @endphp
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border">
                                        <img src="{{ $imageUrl }}" class="card-img-top"
                                            alt="Dokumentasi {{ $item->kegiatan?->nama_kegiatan }}"
                                            style="height: 210px; object-fit: cover;">

                                        <div class="card-body d-flex flex-column">
                                            <p class="text-muted small mb-2">{{ basename($item->file_dokumentasi ?? '-') }}
                                            </p>
                                            <p class="card-text mb-3">
                                                {{ $item->keterangan ?: '-' }}
                                            </p>

                                            <div class="mt-auto d-flex justify-content-between">
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editDokumentasiModal{{ $item->id }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#hapusDokumentasiModal{{ $item->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- MODAL UPLOAD MULTIPLE -->
    <div class="modal fade" id="uploadDokumentasiModal" tabindex="-1" aria-labelledby="uploadDokumentasiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="uploadDokumentasiModalLabel">Upload Dokumentasi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('admin.dokumentasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kegiatan_id" class="form-label">Nama Kegiatan *</label>
                            <select class="form-select @error('kegiatan_id') is-invalid @enderror" id="kegiatan_id"
                                name="kegiatan_id" required>
                                <option value="">-- Pilih Kegiatan --</option>
                                @foreach ($kegiatanList as $kegiatan)
                                    <option value="{{ $kegiatan->id }}"
                                        {{ old('kegiatan_id') == $kegiatan->id ? 'selected' : '' }}>
                                        {{ $kegiatan->nama_kegiatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kegiatan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="file_dokumentasi" class="form-label">Upload Gambar (Bisa Multiple) *</label>
                            <input type="file" class="form-control @error('file_dokumentasi') is-invalid @enderror"
                                id="file_dokumentasi" name="file_dokumentasi[]" accept="image/png,image/jpeg,image/webp"
                                multiple required>
                            @error('file_dokumentasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('file_dokumentasi.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                rows="3">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT (PER ITEM) -->
    @foreach ($dokumentasi as $item)
        <div class="modal fade" id="editDokumentasiModal{{ $item->id }}" tabindex="-1"
            aria-labelledby="editDokumentasiModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editDokumentasiModalLabel{{ $item->id }}">Edit Dokumentasi
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('admin.dokumentasi.update', $item->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_kegiatan_id_{{ $item->id }}" class="form-label">Nama Kegiatan
                                    *</label>
                                <select class="form-select" id="edit_kegiatan_id_{{ $item->id }}" name="kegiatan_id"
                                    required>
                                    @foreach ($kegiatanList as $kegiatan)
                                        <option value="{{ $kegiatan->id }}"
                                            {{ $item->kegiatan_id == $kegiatan->id ? 'selected' : '' }}>
                                            {{ $kegiatan->nama_kegiatan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_file_dokumentasi_{{ $item->id }}" class="form-label">Ganti
                                    Gambar</label>
                                <input type="file" class="form-control"
                                    id="edit_file_dokumentasi_{{ $item->id }}" name="file_dokumentasi"
                                    accept="image/png,image/jpeg,image/webp">
                                <div class="form-text">Kosongkan jika tidak ingin mengganti gambar.</div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_keterangan_{{ $item->id }}" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="edit_keterangan_{{ $item->id }}" name="keterangan" rows="3">{{ $item->keterangan }}</textarea>
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

    <!-- MODAL HAPUS (PER ITEM) -->
    @foreach ($dokumentasi as $item)
        <div class="modal fade" id="hapusDokumentasiModal{{ $item->id }}" tabindex="-1"
            aria-labelledby="hapusDokumentasiModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="hapusDokumentasiModalLabel{{ $item->id }}">Konfirmasi Hapus
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>Yakin ingin menghapus dokumentasi untuk kegiatan
                            <strong>{{ $item->kegiatan?->nama_kegiatan ?? '-' }}</strong>?
                        </p>
                        <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('admin.dokumentasi.destroy', $item->id) }}" method="POST"
                            class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('uploadDokumentasiModal'));
                modal.show();
            });
        </script>
    @endif

@endsection
