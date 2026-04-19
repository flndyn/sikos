@extends('layouts.admin')

@section('title', 'Data Organisasi')

@section('content')

<div class="card shadow-sm">

    <!-- HEADER -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Organisasi</h5>
        <button class="btn btn-primary btn-sm">
            <i class="bi bi-plus"></i> Tambah Organisasi
        </button>
    </div>

    <!-- BODY -->
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Organisasi</th>
                    <th>Deskripsi</th>
                    <th>Pembina</th>
                    <th>Ketua</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <!-- DATA DUMMY -->
                <tr>
                    <td>1</td>
                    <td>OSIS</td>
                    <td>
                        {{ \Illuminate\Support\Str::limit('Organisasi Siswa Intra Sekolah', 40) }}
                    </td>
                    <td>Pak Budi</td>
                    <td>Andi</td>
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
                    <td>PMR</td>
                    <td>
                        {{ \Illuminate\Support\Str::limit('Palang Merah Remaja', 40) }}
                    </td>
                    <td>Bu Siti</td>
                    <td>Rina</td>
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