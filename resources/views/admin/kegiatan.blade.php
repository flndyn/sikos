@extends('layouts.admin')

@section('title', 'Data Kegiatan')

@section('content')

    <!-- Alert Messages -->
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

    <div class="card shadow-sm">

        <!-- HEADER -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex flex-column flex-sm-row gap-2 align-items-sm-center">
                <h5 class="mb-0">Data Kegiatan</h5>

                <form method="GET" action="{{ url()->current() }}" class="d-flex gap-2 align-items-center"
                    id="filterStatusForm">
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui (Semua)
                        </option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak (Semua)
                        </option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui pembina" {{ request('status') == 'disetujui pembina' ? 'selected' : '' }}>
                            Disetujui Pembina</option>
                        <option value="disetujui admin" {{ request('status') == 'disetujui admin' ? 'selected' : '' }}>
                            Disetujui Admin</option>
                        <option value="ditolak pembina" {{ request('status') == 'ditolak pembina' ? 'selected' : '' }}>
                            Ditolak Pembina</option>
                        <option value="ditolak admin" {{ request('status') == 'ditolak admin' ? 'selected' : '' }}>Ditolak
                            Admin</option>
                    </select>
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary btn-sm"
                        title="Reset filter">Reset</a>
                </form>
            </div>

            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                data-bs-target="#tambahKegiatanModal">
                <i class="bi bi-plus"></i> Tambah Kegiatan
            </button>
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
                        <th class="text-nowrap">Tempat</th>
                        <th class="text-nowrap">Status</th>
                        <th class="text-nowrap text-center">Proposal</th>
                        <th class="text-nowrap">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($kegiatan as $item)
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
                            <td>{{ $item->tempat ?? '-' }}</td>
                            <td>
                                @php
                                    $badgeClass = match ($item->status) {
                                        'disetujui admin' => 'bg-success',
                                        'disetujui pembina' => 'bg-warning text-dark',
                                        'ditolak admin', 'ditolak pembina' => 'bg-danger',
                                        'pending' => 'bg-secondary',
                                        default => 'bg-light text-dark',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($item->status) }}</span>
                            </td>
                            <td class="text-center">
                                @if ($item->proposal)
                                    @php
                                        $proposalUrl = \Illuminate\Support\Str::startsWith($item->proposal, 'http')
                                            ? $item->proposal
                                            : route('proposal.download', $item);
                                    @endphp
                                    <a href="{{ $proposalUrl }}" class="btn btn-info btn-sm" target="_blank"
                                        title="Lihat Proposal">
                                        <i class="bi bi-file-earmark"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-nowrap">
                                <div class="d-inline-flex flex-nowrap gap-1">
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editKegiatanModal{{ $item->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#hapusKegiatanModal{{ $item->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <p class="text-muted mb-0">Tidak ada data kegiatan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

    <!-- MODAL TAMBAH KEGIATAN -->
    <div class="modal fade" id="tambahKegiatanModal" tabindex="-1" aria-labelledby="tambahKegiatanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tambahKegiatanModalLabel">Tambah Kegiatan Baru</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('admin.kegiatan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="organisasi_id" class="form-label">Organisasi *</label>
                            <select class="form-select @error('organisasi_id') is-invalid @enderror" id="organisasi_id"
                                name="organisasi_id" required>
                                <option value="" selected>-- Pilih Organisasi --</option>
                                @foreach ($organisasiList as $org)
                                    <option value="{{ $org->id }}"
                                        {{ old('organisasi_id') == $org->id ? 'selected' : '' }}>
                                        {{ $org->nama_organisasi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('organisasi_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nama_kegiatan" class="form-label">Nama Kegiatan *</label>
                            <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror"
                                id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}" required>
                            @error('nama_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi"
                                rows="3">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                    <input type="date"
                                        class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                        id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tempat" class="form-label">Tempat</label>
                                    <input type="text" class="form-control @error('tempat') is-invalid @enderror"
                                        id="tempat" name="tempat" value="{{ old('tempat') }}">
                                    @error('tempat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="disetujui pembina"
                                            {{ old('status') == 'disetujui pembina' ? 'selected' : '' }}>
                                            Disetujui Pembina
                                        </option>
                                        <option value="disetujui admin"
                                            {{ old('status') == 'disetujui admin' ? 'selected' : '' }}>
                                            Disetujui Admin
                                        </option>
                                        <option value="ditolak pembina"
                                            {{ old('status') == 'ditolak pembina' ? 'selected' : '' }}>
                                            Ditolak Pembina
                                        </option>
                                        <option value="ditolak admin"
                                            {{ old('status') == 'ditolak admin' ? 'selected' : '' }}>
                                            Ditolak Admin
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                        rows="2" placeholder="Wajib diisi jika status ditolak">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="proposal" class="form-label">Proposal (File)</label>
                                    <input type="file" class="form-control @error('proposal') is-invalid @enderror"
                                        id="proposal" name="proposal" accept=".pdf,.doc,.docx">
                                    @error('proposal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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

    <!-- MODAL EDIT KEGIATAN (Per Row) -->
    @foreach ($kegiatan as $item)
        <div class="modal fade" id="editKegiatanModal{{ $item->id }}" tabindex="-1"
            aria-labelledby="editKegiatanModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editKegiatanModalLabel{{ $item->id }}">Edit Kegiatan</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('admin.kegiatan.update', $item->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_organisasi_id_{{ $item->id }}" class="form-label">Organisasi
                                    *</label>
                                <select class="form-select" id="edit_organisasi_id_{{ $item->id }}"
                                    name="organisasi_id" required>
                                    @foreach ($organisasiList as $org)
                                        <option value="{{ $org->id }}"
                                            {{ $item->organisasi_id == $org->id ? 'selected' : '' }}>
                                            {{ $org->nama_organisasi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="edit_nama_kegiatan_{{ $item->id }}" class="form-label">Nama Kegiatan
                                    *</label>
                                <input type="text" class="form-control" id="edit_nama_kegiatan_{{ $item->id }}"
                                    name="nama_kegiatan" value="{{ $item->nama_kegiatan }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_deskripsi_{{ $item->id }}" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="edit_deskripsi_{{ $item->id }}" name="deskripsi" rows="3">{{ $item->deskripsi }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_tanggal_mulai_{{ $item->id }}" class="form-label">Tanggal
                                            Mulai</label>
                                        <input type="date" class="form-control"
                                            id="edit_tanggal_mulai_{{ $item->id }}" name="tanggal_mulai"
                                            value="{{ $item->tanggal_mulai?->format('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_tempat_{{ $item->id }}" class="form-label">Tempat</label>
                                        <input type="text" class="form-control" id="edit_tempat_{{ $item->id }}"
                                            name="tempat" value="{{ $item->tempat }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_status_{{ $item->id }}" class="form-label">Status *</label>
                                        <select class="form-select" id="edit_status_{{ $item->id }}" name="status"
                                            required>
                                            <option value="pending" {{ $item->status == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="disetujui pembina"
                                                {{ $item->status == 'disetujui pembina' ? 'selected' : '' }}>
                                                Disetujui Pembina
                                            </option>
                                            <option value="disetujui admin"
                                                {{ $item->status == 'disetujui admin' ? 'selected' : '' }}>
                                                Disetujui Admin
                                            </option>
                                            <option value="ditolak pembina"
                                                {{ $item->status == 'ditolak pembina' ? 'selected' : '' }}>
                                                Ditolak Pembina
                                            </option>
                                            <option value="ditolak admin"
                                                {{ $item->status == 'ditolak admin' ? 'selected' : '' }}>
                                                Ditolak Admin
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_keterangan_{{ $item->id }}"
                                            class="form-label">Keterangan</label>
                                        <textarea class="form-control" id="edit_keterangan_{{ $item->id }}" name="keterangan" rows="2"
                                            placeholder="Wajib diisi jika status ditolak">{{ $item->keterangan }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_proposal_{{ $item->id }}" class="form-label">Proposal
                                            (File)</label>
                                        @if ($item->proposal)
                                            @php
                                                $proposalUrl = \Illuminate\Support\Str::startsWith(
                                                    $item->proposal,
                                                    'http',
                                                )
                                                    ? $item->proposal
                                                    : route('proposal.download', $item);
                                            @endphp
                                            <div class="mb-2">
                                                <a href="{{ $proposalUrl }}" target="_blank" class="small">
                                                    Lihat file proposal saat ini
                                                </a>
                                            </div>
                                        @endif
                                        <input type="file" class="form-control"
                                            id="edit_proposal_{{ $item->id }}" name="proposal"
                                            accept=".pdf,.doc,.docx">
                                        <div class="form-text">Kosongkan jika tidak ingin mengganti file proposal.</div>
                                    </div>
                                </div>
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

    <!-- MODAL HAPUS KEGIATAN (Per Row) -->
    @foreach ($kegiatan as $item)
        <div class="modal fade" id="hapusKegiatanModal{{ $item->id }}" tabindex="-1"
            aria-labelledby="hapusKegiatanModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="hapusKegiatanModalLabel{{ $item->id }}">Konfirmasi Hapus
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus kegiatan <strong>{{ $item->nama_kegiatan }}</strong>?</p>
                        <p class="text-muted small mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('admin.kegiatan.destroy', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Auto-open modal if validation error -->
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('tambahKegiatanModal'));
                modal.show();
            });
        </script>
    @endif

@endsection
