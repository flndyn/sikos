@extends('layouts.admin')

@section('title', 'Data User')

@section('content')

<div class="card shadow-sm">

    <!-- HEADER -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data User</h5>
        <button class="btn btn-primary btn-sm">
            <i class="bi bi-plus"></i> Tambah User
        </button>
    </div>

    <!-- BODY -->
    <div class="card-body">

        <table class="table table-bordered table-striped">
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
                <!-- DATA DUMMY -->
                <tr>
                    <td>1</td>
                    <td>Admin</td>
                    <td>admin@mail.com</td>
                    <td><span class="badge bg-primary">Admin</span></td>
                    <td>-</td>
                    <td>
                        <button class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>Ketua OSIS</td>
                    <td>ketua@mail.com</td>
                    <td><span class="badge bg-success">Ketua</span></td>
                    <td>OSIS</td>
                    <td>
                        <button class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>
</div>

@endsection