<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register Ketua SIOKAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container-fluid px-0">
        <div class="row g-0 min-vh-100">
            <div class="col-md-6 bg-success text-white d-flex flex-column justify-content-center p-4 p-lg-5">
                <h3 class="fw-bold">Registrasi Ketua</h3>
                <p class="mb-2">Buat akun Ketua Organisasi</p>
                <p class="small mb-0">
                    Isi data dengan benar. Username dan email harus unik,
                    serta tidak boleh menggunakan nilai yang sama.
                </p>
            </div>

            <div class="col-md-6 bg-white d-flex align-items-center justify-content-center p-4 p-lg-5">
                <div class="w-100" style="max-width: 460px;">
                    <h5 class="text-center mb-4">Daftar Akun Ketua</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                placeholder="Masukkan username" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                placeholder="Masukkan email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Ulangi password" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            Daftar sebagai Ketua
                        </button>
                    </form>

                    <p class="text-center small mt-3 mb-0">
                        Sudah punya akun?
                        <a href="{{ route('login') }}">Kembali ke login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
