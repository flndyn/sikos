@extends('layouts.admin')

@section('content')

<div class="card shadow-sm">

    <!-- HEADER -->
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Laporan Kegiatan</h5>
    </div>

    <!-- SEARCH + EXPORT -->
    <div class="card-body">

        <div class="d-flex justify-content-between mb-3">

            <!-- SEARCH -->
            <input type="text" class="form-control w-25" placeholder="Search...">

            <!-- EXPORT -->
            <button class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </button>

        </div>

        <!-- TABLE -->
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Organisasi</th>
                    <th>Tanggal</th>
                    <th>Laporan</th>
                </tr>
            </thead>

            <tbody>

                <!-- DATA DUMMY -->
                <tr>
                    <td>1</td>
                    <td>Lomba 17 Agustus</td>
                    <td>OSIS</td>
                    <td>2026-08-17</td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">
                            <i class="bi bi-eye"></i> Lihat
                        </a>
                    </td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>Bakti Sosial</td>
                    <td>PMR</td>
                    <td>2026-09-01</td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">
                            <i class="bi bi-eye"></i> Lihat
                        </a>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>
</div>

@endsection