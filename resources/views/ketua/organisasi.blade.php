@extends('layouts.ketua')

@section('title', 'Profile Organisasi')

@section('content')

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Profile Organisasi</h5>
        </div>
        <div class="card-body">
            @if ($organisasi)
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Nama Organisasi</label>
                        <p class="form-control-plaintext">{{ $organisasi->nama_organisasi ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Pembina</label>
                        <p class="form-control-plaintext">{{ $organisasi->pembina?->name ?? '-' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Ketua Organisasi</label>
                        <p class="form-control-plaintext">{{ $organisasi->ketua?->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email Pembina</label>
                        <p class="form-control-plaintext">{{ $organisasi->pembina?->email ?? '-' }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <p class="form-control-plaintext">{{ $organisasi->deskripsi ?? '-' }}</p>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Anda belum menjadi ketua dari organisasi manapun.
                </div>
            @endif
        </div>
    </div>

@endsection
