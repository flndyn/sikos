@extends('layouts.admin')

@section('title', 'Data Organisasi')

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    @php
        $usedKetuaIds = $organisasi->pluck('ketua_id')->filter()->map(fn($id) => (int) $id)->all();
    @endphp

    <div class="card shadow-sm">

        <!-- HEADER -->
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Organisasi</h5>
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                data-bs-target="#modalTambahOrganisasi">
                <i class="bi bi-plus"></i> Tambah Organisasi
            </button>
        </div>

        <!-- BODY -->
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th class="text-nowrap">No</th>
                        <th class="text-nowrap">Nama Organisasi</th>
                        <th class="text-nowrap">Deskripsi</th>
                        <th class="text-nowrap">Pembina</th>
                        <th class="text-nowrap">Ketua</th>
                        <th class="text-nowrap">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($organisasi as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama_organisasi }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->deskripsi ?? '-', 40) }}</td>
                            <td>
                                <x-user-avatar :user="$item->pembina" :size="34" />
                            </td>
                            <td>
                                <x-user-avatar :user="$item->ketua" :size="34" />
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalEditOrganisasi{{ $item->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalHapusOrganisasi{{ $item->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data organisasi.</td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>
    </div>

    @foreach ($organisasi as $item)
        <div class="modal fade" id="modalEditOrganisasi{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Organisasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('admin.organisasi.update', $item) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Organisasi</label>
                                <input type="text" name="nama_organisasi" class="form-control"
                                    value="{{ $item->nama_organisasi }}" maxlength="100" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="3">{{ $item->deskripsi }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pembina</label>
                                <select name="pembina_id" class="form-select">
                                    <option value="">- Pilih Pembina -</option>
                                    @foreach ($pembinaUsers as $pembina)
                                        <option value="{{ $pembina->id }}" @selected((int) $item->pembina_id === (int) $pembina->id)>
                                            {{ $pembina->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-1">
                                <label class="form-label">Ketua</label>
                                <select name="ketua_id" class="form-select">
                                    <option value="">- Pilih Ketua -</option>
                                    @foreach ($ketuaUsers as $ketua)
                                        @php
                                            $isUsedByOtherOrganisasi =
                                                in_array((int) $ketua->id, $usedKetuaIds, true) &&
                                                (int) $item->ketua_id !== (int) $ketua->id;
                                        @endphp
                                        <option value="{{ $ketua->id }}" @selected((int) $item->ketua_id === (int) $ketua->id)
                                            @disabled($isUsedByOtherOrganisasi)>
                                            {{ $ketua->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Ketua yang sudah dipakai organisasi lain tidak bisa
                                    dipilih.</small>
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

        <div class="modal fade" id="modalHapusOrganisasi{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Organisasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Yakin ingin menghapus organisasi <strong>{{ $item->nama_organisasi }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form method="POST" action="{{ route('admin.organisasi.destroy', $item) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="modalTambahOrganisasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Organisasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.organisasi.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Organisasi</label>
                            <input type="text" name="nama_organisasi" class="form-control"
                                value="{{ old('nama_organisasi') }}" maxlength="100" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pembina</label>
                            <select name="pembina_id" class="form-select">
                                <option value="">- Pilih Pembina -</option>
                                @foreach ($pembinaUsers as $pembina)
                                    <option value="{{ $pembina->id }}" @selected((string) old('pembina_id') === (string) $pembina->id)>
                                        {{ $pembina->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-1">
                            <label class="form-label">Ketua</label>
                            <select name="ketua_id" class="form-select">
                                <option value="">- Pilih Ketua -</option>
                                @foreach ($ketuaUsers as $ketua)
                                    <option value="{{ $ketua->id }}" @selected((string) old('ketua_id') === (string) $ketua->id)
                                        @disabled(in_array((int) $ketua->id, $usedKetuaIds, true))>
                                        {{ $ketua->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Ketua yang sudah dipakai organisasi lain tidak bisa dipilih.</small>
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

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modalElement = document.getElementById('modalTambahOrganisasi');
                if (modalElement) {
                    var modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });
        </script>
    @endif

@endsection
