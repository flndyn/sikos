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

<div class="container d-flex align-items-center justify-content-center vh-100">
    
    <div class="row shadow rounded overflow-hidden" style="max-width: 800px;">
        
        <!-- LEFT SIDE -->
        <div class="col-md-6 bg-primary text-white d-flex flex-column justify-content-center p-4">
            <h3 class="fw-bold">SIOKAS</h3>
            <p class="mb-2">Sistem Informasi Administrasi</p>
            <p class="small">
                Mengelola kegiatan organisasi siswa secara digital, 
                transparan, dan terstruktur di SMAN 1 Paiton.
            </p>
        </div>

        <!-- RIGHT SIDE -->
        <div class="col-md-6 bg-white p-4">
            <h5 class="text-center mb-4">Login Sistem</h5>

            <form method="POST" action="#">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password">
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Login
                </button>
            </form>

            <p class="text-center small mt-3 text-muted">
                Akses untuk Admin, Ketua Organisasi, dan Pembina
            </p>
        </div>

    </div>

</div>

</body>
</html>