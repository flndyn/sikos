@extends('layouts.ketua')

@section('content')
    <div class="row g-3">

        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h6>Total Pengguna</h6>
                <h3>10</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h6>Total Organisasi</h6>
                <h3>5</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h6>Total Kegiatan</h6>
                <h3>15</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h6>Kegiatan Disetujui</h6>
                <h3>12</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h6>Kegiatan Ditolak</h6>
                <h3>3</h3>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-center p-3 shadow-sm">
                <h6>Dokumentasi</h6>
                <h3>24</h3>
            </div>
        </div>

    </div>

    <!-- TABEL KEGIATAN -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header">
            Data Kegiatan Terbaru
        </div>
        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kegiatan</th>
                        <th>Organisasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Lomba Voli</td>
                        <td>OSIS</td>
                        <td><span class="badge bg-success">Disetujui</span></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Bakti Sosial</td>
                        <td>PMR</td>
                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
@endsection
