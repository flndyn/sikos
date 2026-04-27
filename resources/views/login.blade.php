<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login SIOKAS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container-fluid px-0">

        <div class="row g-0 min-vh-100">

            <!-- LEFT SIDE -->
            <div class="col-md-6 bg-primary text-white d-flex flex-column justify-content-center p-4 p-lg-5">
                <h3 class="fw-bold">SIOKAS</h3>
                <p class="mb-2">Sistem Informasi Administrasi</p>
                <p class="small">
                    Mengelola kegiatan organisasi siswa secara digital,
                    transparan, dan terstruktur di SMAN 1 Paiton.
                </p>
            </div>

            <!-- RIGHT SIDE -->
            <div class="col-md-6 bg-white d-flex align-items-center justify-content-center p-4 p-lg-5">
                <div class="w-100" style="max-width: 420px;">
                    <h5 class="text-center mb-4">Login Sistem</h5>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.attempt') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email atau Username</label>
                            <input type="text" name="login" value="{{ old('login') }}" class="form-control"
                                placeholder="Masukkan email atau username" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                                required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Login
                        </button>
                    </form>

                    <p class="text-center small mt-3 text-muted">
                        Akses untuk Admin, Ketua Organisasi, dan Pembina
                    </p>

                    <p class="text-center small mb-0">
                        Belum punya akun Ketua?
                        <a href="{{ route('register') }}">Daftar di sini</a>
                    </p>
                </div>
            </div>

        </div>

    </div>

</body>

</html>
