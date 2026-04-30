@extends('layouts.pembina')

@section('title', 'Profil Akun')

@section('content')
    @php
        $user = auth()->user();
        $photoUrl = $user?->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : null;
        $userInitial = $user?->name ? strtoupper(mb_substr($user->name, 0, 1)) : 'U';
    @endphp

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <div class="fw-semibold mb-1">Periksa kembali data yang diinput:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="row g-4 align-items-start">
                <div class="col-lg-4">
                    <div class="border rounded-4 p-4 h-100 text-center bg-light">
                        <div class="mb-3 mx-auto rounded-circle overflow-hidden bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                            style="width:120px; height:120px; font-size:2rem;">
                            @if ($photoUrl)
                                <img src="{{ $photoUrl }}" alt="Foto Profil" class="w-100 h-100 object-fit-cover">
                            @else
                                {{ $userInitial }}
                            @endif
                        </div>
                        <h4 class="mb-1">{{ $user?->name ?? '-' }}</h4>
                        <div class="text-muted">{{ $user?->role ?? '-' }}</div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <form action="{{ route('pembina.profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user?->name) }}" required
                                    maxlength="100">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user?->email) }}" required
                                    maxlength="100">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="profile_photo" class="form-label">Foto Profil</label>
                                <input type="file" class="form-control @error('profile_photo') is-invalid @enderror"
                                    id="profile_photo" name="profile_photo" accept="image/jpeg,image/png,image/webp">
                                <div class="form-text">Format: JPG, PNG, atau WEBP. Maksimal 5 MB.</div>
                                @error('profile_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-control" value="{{ $user?->role ?? '-' }}" readonly>
                            </div>

                            <div class="col-12 d-flex gap-2 justify-content-end mt-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
