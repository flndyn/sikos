@extends('layouts.admin')

@section('title', 'Data Kegiatan')

@section('content')

<div class="card shadow-sm">

    <!-- HEADER -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Data Kegiatan</h5>
        <button class="btn btn-primary btn-sm">
            <i class="bi bi-plus"></i> Tambah Kegiatan
        </button>
    </div>

    <!-- BODY -->
    <div class="card-body">

        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Organisasi</th>
                    <th>Nama Kegiatan</th>
                    <th>Deskripsi</th>
                    <th>Tanggal</th>
                    <th>Tempat</th>
                    <th>Status</th>
                    <th>Proposal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <!-- DATA DUMMY -->
                <tr>
                    <td>1</td>
                    <td>OSIS</td>
                    <td>Lomba 17 Agustus</td>
                    <td>
                        {{ \Illuminate\Support\Str::limit('Lomba untuk memperingati hari kemerdekaan', 40) }}
                    </td>
                    <td>2026-08-17</td>
                    <td>Lapangan Sekolah</td>
                    <td>
                        <span class="badge bg-warning text-dark">Pending</span>
                    </td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">
                            <i class="bi bi-file-earmark"></i>
                        </a>
                    </td>
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
                    <td>Bakti Sosial</td>
                    <td>
                        {{ \Illuminate\Support\Str::limit('Kegiatan sosial membantu masyarakat desa', 40) }}
                    </td>
                    <td>2026-09-01</td>
                    <td>Desa Paiton</td>
                    <td>
                        <span class="badge bg-success">Disetujui</span>
                    </td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">
                            <i class="bi bi-file-earmark"></i>
                        </a>
                    </td>
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