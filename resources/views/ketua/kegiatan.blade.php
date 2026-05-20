@extends('layouts.ketua')

@section('title', 'Manajemen Kegiatan')

@section('content')

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Kegiatan</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAjukanKegiatan">
                <i class="bi bi-plus-circle me-2"></i>Ajukan Kegiatan
            </button>
        </div>
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Nama Kegiatan</th>
                        <th class="text-nowrap">Penanggung Jawab</th>
                        <th class="text-nowrap">Tanggal Terakhir</th>
                        <th class="text-nowrap">Deskripsi</th>
                        <th class="text-nowrap">Tanggal</th>
                        <th class="text-nowrap">Tempat</th>
                        <th class="text-nowrap">Proposal</th>
                        <th class="text-nowrap">Status</th>
                        <th class="text-nowrap text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kegiatan as $item)
                        @php
                            $badgeClass = match ($item->status) {
                                'disetujui admin' => 'bg-success',
                                'disetujui pembina' => 'bg-info text-dark',
                                'ditolak admin', 'ditolak pembina' => 'bg-danger',
                                default => 'bg-warning text-dark',
                            };

                            $statusLabel = match ($item->status) {
                                'disetujui admin' => 'Disetujui Admin',
                                'disetujui pembina' => 'Menunggu Admin',
                                'ditolak admin' => 'Ditolak Admin',
                                'ditolak pembina' => 'Ditolak Pembina',
                                default => 'Di Proses',
                            };
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_kegiatan }}</td>
                            <td>{{ $item->penanggungJawab->name ?? '-' }}</td>
                            <td>{{ $item->tanggal_berakhir?->format('d-m-Y') ?? '-' }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->deskripsi ?? '-', 50) }}</td>
                            <td>{{ $item->tanggal_mulai->format('d-m-Y') }}</td>
                            <td>{{ $item->tempat }}</td>
                            <td class="text-center">
                                @if ($item->proposal)
                                    <a href="{{ route('proposal.download', $item->id) }}" class="btn btn-danger btn-sm"
                                        target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td><span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span></td>
                            <td class="text-nowrap text-center">
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editKegiatanModal{{ $item->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <p class="text-muted mb-0">Belum ada kegiatan yang diajukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL AJUKAN KEGIATAN -->
    <div class="modal fade" id="modalAjukanKegiatan" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajukan Kegiatan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('ketua.kegiatan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_kegiatan" class="form-label">Nama Kegiatan <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_kegiatan') is-invalid @enderror"
                                id="nama_kegiatan" name="nama_kegiatan" value="{{ old('nama_kegiatan') }}"
                                placeholder="Masukkan nama kegiatan" required>
                            @error('nama_kegiatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3"
                                placeholder="Masukkan deskripsi kegiatan">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="penanggung_jawab" class="form-label">Penanggung Jawab (Pembina)</label>
                                    <select class="form-select @error('penanggung_jawab') is-invalid @enderror"
                                        id="penanggung_jawab" name="penanggung_jawab">
                                        <option value="">-- Pilih Pembina --</option>
                                        @if ($pembina)
                                            <option value="{{ $pembina->id }}" {{ old('penanggung_jawab') == $pembina->id ? 'selected' : '' }}>
                                                {{ $pembina->name }}
                                            </option>
                                        @endif
                                    </select>
                                    @error('penanggung_jawab')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_berakhir" class="form-label">Tanggal Berakhir</label>
                                    <input type="date" class="form-control @error('tanggal_berakhir') is-invalid @enderror"
                                        id="tanggal_berakhir" name="tanggal_berakhir" value="{{ old('tanggal_berakhir') }}">
                                    @error('tanggal_berakhir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                        id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                                        required>
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tempat" class="form-label">Tempat <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('tempat') is-invalid @enderror"
                                        id="tempat" name="tempat" value="{{ old('tempat') }}"
                                        placeholder="Masukkan lokasi kegiatan" required>
                                    @error('tempat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="proposal" class="form-label">File Proposal (Opsional)</label>
                            <input type="file" class="form-control @error('proposal') is-invalid @enderror"
                                id="proposal" name="proposal" accept=".pdf,.doc,.docx">
                            <small class="text-muted">Format: PDF, DOC, DOCX (Max 5MB)</small>
                            @error('proposal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Ajukan Kegiatan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT KEGIATAN -->
    @foreach ($kegiatan as $item)
        <div class="modal fade" id="editKegiatanModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kegiatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('ketua.kegiatan.update', $item->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_nama_kegiatan_{{ $item->id }}" class="form-label">Nama Kegiatan
                                    <span class="text-danger">*</span></label>
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
                                        <label for="edit_penanggung_jawab_{{ $item->id }}" class="form-label">Penanggung Jawab (Pembina)</label>
                                        <select class="form-select" id="edit_penanggung_jawab_{{ $item->id }}"
                                            name="penanggung_jawab">
                                            <option value="">-- Pilih Pembina --</option>
                                            @if ($pembina)
                                                <option value="{{ $pembina->id }}" {{ $item->penanggung_jawab == $pembina->id ? 'selected' : '' }}>
                                                    {{ $pembina->name }}
                                                </option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_tanggal_berakhir_{{ $item->id }}" class="form-label">Tanggal Berakhir</label>
                                        <input type="date" class="form-control"
                                            id="edit_tanggal_berakhir_{{ $item->id }}" name="tanggal_berakhir"
                                            value="{{ $item->tanggal_berakhir?->format('Y-m-d') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_tanggal_mulai_{{ $item->id }}" class="form-label">Tanggal
                                            Mulai
                                            <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control"
                                            id="edit_tanggal_mulai_{{ $item->id }}" name="tanggal_mulai"
                                            value="{{ $item->tanggal_mulai?->format('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_tempat_{{ $item->id }}" class="form-label">Tempat <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_tempat_{{ $item->id }}"
                                            name="tempat" value="{{ $item->tempat }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_proposal_{{ $item->id }}" class="form-label">Ganti Proposal
                                    (Opsional)
                                </label>
                                <input type="file" class="form-control" id="edit_proposal_{{ $item->id }}"
                                    name="proposal" accept=".pdf,.doc,.docx">
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti file proposal.</small>
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

@endsection
