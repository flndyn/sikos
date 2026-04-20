@extends('layouts.admin')

@section('title', 'Data User')

@section('content')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data User</h5>
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                <i class="bi bi-plus"></i> Tambah User
            </button>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Organisasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $roleBadge = match ($user->role) {
                                'admin' => 'bg-primary',
                                'ketua' => 'bg-success',
                                'pembina' => 'bg-info text-dark',
                                default => 'bg-secondary',
                            };

                            $roleLabel = ucfirst($user->role);

                            $organisasi = match ($user->role) {
                                'ketua' => $user->organisasiSebagaiKetua->pluck('nama_organisasi')->implode(', '),
                                'pembina' => $user->organisasiSebagaiPembina->pluck('nama_organisasi')->implode(', '),
                                default => '',
                            };
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge {{ $roleBadge }}">{{ $roleLabel }}</span></td>
                            <td>{{ $organisasi !== '' ? $organisasi : '-' }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalEditUser{{ $user->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalHapusUser{{ $user->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data user.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @foreach ($users as $user)
        <div class="modal fade" id="modalEditUser{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                    <option value="ketua" @selected($user->role === 'ketua')>Ketua</option>
                                    <option value="pembina" @selected($user->role === 'pembina')>Pembina</option>
                                </select>
                            </div>

                            <div class="mb-1">
                                <label class="form-label">Password Baru (opsional)</label>
                                <input type="password" name="password" class="form-control" minlength="8">
                                <small class="text-muted">Kosongkan jika tidak ingin mengganti password.</small>
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

        <div class="modal fade" id="modalHapusUser{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Yakin ingin menghapus user <strong>{{ $user->name }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                                <option value="ketua" @selected(old('role', 'ketua') === 'ketua')>Ketua</option>
                                <option value="pembina" @selected(old('role') === 'pembina')>Pembina</option>
                            </select>
                        </div>

                        <div class="mb-1">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" minlength="8" required>
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

@endsection
