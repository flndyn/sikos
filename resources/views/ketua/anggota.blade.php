@extends('layouts.ketua')

@section('title', 'Anggota Ekstrakurikuler')

@section('content')

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($organisasi)
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Anggota {{ $organisasi->nama_organisasi }}
                </h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="fas fa-plus me-1"></i> Tambah Anggota
                </button>
            </div>

            <div class="card-body">
                <!-- {{-- Search --}}
                <form method="GET" action="{{ route('ketua.anggota') }}" class="mb-3">
                    <div class="input-group" style="max-width: 400px;">
                        <input type="text" name="search" class="form-control form-control-sm"
                            placeholder="Cari nama, kelas, atau No. HP..." value="{{ $search }}">
                        <button class="btn btn-outline-secondary btn-sm" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                        @if ($search)
                            <a href="{{ route('ketua.anggota') }}" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-times"></i>
                            </a>
                        @endif
                    </div>
                </form> -->

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Nama Lengkap</th>
                                <th>Kelas</th>
                                <th>Jenis Kelamin</th>
                                <th>No. HP</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($anggota as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $item->nama }}</td>
                                    <td>{{ $item->kelas }}</td>
                                    <td>
                                        @if ($item->jenis_kelamin === 'L')
                                            <span class="badge bg-primary">Laki-laki</span>
                                        @elseif ($item->jenis_kelamin === 'P')
                                            <span class="badge bg-danger">Perempuan</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $item->no_hp ?? '-' }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit{{ $item->id }}" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm text-white" data-bs-toggle="modal"
                                            data-bs-target="#modalHapus{{ $item->id }}" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                {{-- Modal Edit --}}
                                <div class="modal fade" id="modalEdit{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('ketua.anggota.update', $item) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Anggota</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Lengkap <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="nama" class="form-control"
                                                            value="{{ $item->nama }}" required maxlength="100">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Kelas <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" name="kelas" class="form-control"
                                                            value="{{ $item->kelas }}" required maxlength="50">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label d-block">Jenis Kelamin <span
                                                                class="text-danger">*</span></label>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="jenis_kelamin"
                                                                id="edit_jk_l_{{ $item->id }}" value="L"
                                                                {{ $item->jenis_kelamin === 'L' ? 'checked' : '' }} required>
                                                            <label class="form-check-label" for="edit_jk_l_{{ $item->id }}">Laki-laki</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio" name="jenis_kelamin"
                                                                id="edit_jk_p_{{ $item->id }}" value="P"
                                                                {{ $item->jenis_kelamin === 'P' ? 'checked' : '' }} required>
                                                            <label class="form-check-label" for="edit_jk_p_{{ $item->id }}">Perempuan</label>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">No. HP <span
                                                                class="text-muted">(Opsional)</span></label>
                                                        <input type="text" name="no_hp" class="form-control"
                                                            value="{{ $item->no_hp }}" maxlength="20">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal Hapus --}}
                                <div class="modal fade" id="modalHapus{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-danger me-2"></i>Konfirmasi Hapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus anggota <strong>{{ $item->nama }}</strong>?</p>
                                                <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan dan akan menghapus semua riwayat absensi anggota tersebut.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('ketua.anggota.destroy', $item) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-2x mb-2 d-block opacity-50"></i>
                                        Belum ada data anggota.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer text-muted small">
                Total: {{ $anggota->count() }} anggota
            </div>
        </div>

        {{-- Modal Tambah --}}
        <div class="modal fade" id="modalTambah" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('ketua.anggota.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Anggota Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control" required maxlength="100"
                                    value="{{ old('nama') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kelas <span class="text-danger">*</span></label>
                                <input type="text" name="kelas" class="form-control" required maxlength="50"
                                    value="{{ old('kelas') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label d-block">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin"
                                        id="tambah_jk_l" value="L"
                                        {{ old('jenis_kelamin') === 'L' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="tambah_jk_l">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin"
                                        id="tambah_jk_p" value="P"
                                        {{ old('jenis_kelamin') === 'P' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="tambah_jk_p">Perempuan</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">No. HP <span class="text-muted">(Opsional)</span></label>
                                <input type="text" name="no_hp" class="form-control" maxlength="20"
                                    value="{{ old('no_hp') }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Anda belum terdaftar di organisasi manapun.
        </div>
    @endif

@endsection
