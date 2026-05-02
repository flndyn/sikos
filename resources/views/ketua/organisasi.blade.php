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
                        <div class="mt-1">
                            <x-user-avatar :user="$organisasi->pembina" :size="36" />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Ketua Organisasi</label>
                        <div class="mt-1">
                            <x-user-avatar :user="$organisasi->ketua" :size="36" />
                        </div>
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
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Anda belum terdaftar di organisasi manapun.
                </div>
            @endif
        </div>
    </div>

@endsection
