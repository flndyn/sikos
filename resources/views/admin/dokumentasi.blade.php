@extends('layouts.admin')

@section('title', 'Dokumentasi Kegiatan')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5>Dokumentasi Kegiatan</h5>
    <button class="btn btn-primary btn-sm">
        <i class="bi bi-plus"></i> Upload Dokumentasi
    </button>
</div>

<div class="row">

    <!-- CARD 1 -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">

            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="">

            <div class="card-body">
                <h6 class="card-title">Lomba 17 Agustus</h6>
                
                <p class="card-text">
                    {{ \Illuminate\Support\Str::limit('Dokumentasi kegiatan lomba kemerdekaan', 50) }}
                </p>

                <div class="d-flex justify-content-between">
                    <button class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- CARD 2 -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">

            <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="">

            <div class="card-body">
                <h6 class="card-title">Bakti Sosial</h6>
                
                <p class="card-text">
                    {{ \Illuminate\Support\Str::limit('Kegiatan sosial bersama masyarakat desa', 50) }}
                </p>

                <div class="d-flex justify-content-between">
                    <button class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection