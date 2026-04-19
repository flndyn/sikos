@extends('layouts.admin')

@section('title', 'Validasi Kegiatan')

@section('content')

<div class="card shadow-sm">

    <!-- HEADER -->
    <div class="card-header">
        <h5 class="mb-0">Validasi Kegiatan (Pending)</h5>
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
                    <th>Proposal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <!-- DATA DUMMY (PENDING SAJA) -->
                <tr>
                    <td>1</td>
                    <td>OSIS</td>
                    <td>Lomba 17 Agustus</td>
                    <td>
                        {{ \Illuminate\Support\Str::limit('Lomba untuk memperingati hari kemerdekaan', 40) }}
                    </td>
                    <td>2026-08-17</td>

                    <td>
                        <a href="#" class="btn btn-info btn-sm">
                            <i class="bi bi-file-earmark"></i>
                        </a>
                    </td>

                    <td>
                        <button class="btn btn-success btn-sm">
                            <i class="bi bi-check"></i>
                        </button>
                        <button class="btn btn-danger btn-sm">
                            <i class="bi bi-x"></i>
                        </button>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>
</div>

@endsection