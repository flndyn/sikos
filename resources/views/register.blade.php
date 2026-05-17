<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Register Ketua SIKOS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-purple: #7c3aed;
            --primary-green: #16a34a;
            --gradient-bg: linear-gradient(135deg, #14532d 0%, #16a34a 50%, #22c55e 100%);
            --gradient-btn: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* =====================
           DESKTOP LAYOUT
        ===================== */
        @media (min-width: 992px) {
            body {
                overflow: hidden;
            }

            .container-fluid {
                height: 100vh;
            }

            .left-panel {
                height: 100vh;
            }

            .right-panel {
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow-y: auto;
            }

            .register-card {
                max-height: 95vh;
                overflow-y: auto;
            }
        }

        /* Left Side Styling */
        .left-panel {
            background: var(--gradient-bg);
            position: relative;
            overflow: hidden;
            padding: 2rem;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
        }

        .logo-shield {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.6);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0.75rem;
        }

        .left-content h1 {
            font-size: 2rem;
            line-height: 1.2;
        }

        .left-content h2 {
            font-size: 1.5rem;
            line-height: 1.3;
            margin: 1rem 0;
        }

        .left-content p {
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }

        .info-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.5rem 0;
        }

        .info-item+.info-item {
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }

        .info-icon {
            width: 36px;
            height: 36px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .info-item small {
            font-size: 0.75rem;
            line-height: 1.4;
            opacity: 0.9;
        }

        .info-item .info-title {
            font-size: 0.8rem;
            font-weight: 600;
            display: block;
            margin-bottom: 0.1rem;
        }

        .quote-box {
            background: white;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
            color: #374151;
        }

        .quote-box p {
            font-size: 0.8rem;
            margin: 0;
            line-height: 1.4;
        }

        .quote-icon {
            color: var(--primary-green);
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        .decoration-dots {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            opacity: 0.3;
        }

        .decoration-dots i {
            margin: 0 0.2rem;
            font-size: 0.4rem;
        }

        /* Right Side Styling */
        .right-panel {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            padding: 1.5rem;
        }

        .register-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            max-width: 440px;
            width: 100%;
        }

        .shield-icon-large {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: var(--primary-green);
            font-size: 1.75rem;
        }

        .register-card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.1);
        }

        .input-group-text {
            background: transparent;
            border: 2px solid #e5e7eb;
            border-right: none;
            border-radius: 8px 0 0 8px;
            color: #6b7280;
            padding: 0.625rem 0.75rem;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 8px 8px 0;
        }

        .input-group-append .input-group-text {
            border-left: none;
            border-right: 2px solid #e5e7eb;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
        }

        .btn-register {
            background: var(--gradient-btn);
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: white;
            transition: all 0.3s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(22, 163, 74, 0.35);
            color: white;
        }

        /* =====================
           MOBILE BANNER
        ===================== */
        .mobile-banner {
            display: none;
            background: var(--gradient-bg);
            padding: 1.25rem 1.5rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .mobile-banner::before {
            content: '';
            position: absolute;
            top: -70px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.06);
            border-radius: 50%;
        }

        .mobile-banner::after {
            content: '';
            position: absolute;
            bottom: -50px;
            left: -30px;
            width: 140px;
            height: 140px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .mobile-banner .banner-inner {
            position: relative;
            z-index: 1;
        }

        .mobile-logo-shield {
            width: 38px;
            height: 38px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* =====================
           MOBILE OVERRIDES (< 992px)
        ===================== */
        @media (max-width: 991.98px) {

            html,
            body {
                overflow-x: hidden;
                height: auto;
                min-height: 100%;
            }

            .mobile-banner {
                display: block;
            }

            .left-panel {
                display: none !important;
            }

            .right-panel {
                padding: 0;
                background: transparent;
                min-height: auto;
                display: block !important;
            }

            .register-card {
                border-radius: 24px 24px 0 0;
                margin-top: -18px;
                position: relative;
                z-index: 1;
                padding: 1.5rem 1.25rem 2rem;
                box-shadow: 0 -6px 24px rgba(0, 0, 0, 0.08);
                max-width: 100%;
                min-height: calc(100vh - 130px);
            }

            .shield-icon-large {
                width: 50px;
                height: 50px;
                font-size: 1.4rem;
                margin-bottom: 0.75rem;
            }

            .register-card h3 {
                font-size: 1.25rem;
            }

            /* Prevent iOS zoom on input focus */
            .form-control {
                font-size: 16px;
                padding: 0.6rem 0.75rem;
            }

            .input-group-text {
                padding: 0.6rem 0.65rem;
            }

            .btn-register {
                padding: 0.8rem;
                font-size: 0.95rem;
            }

            .mb-3 {
                margin-bottom: 0.875rem !important;
            }

            .row.g-0.min-vh-100 {
                min-height: auto !important;
            }
        }

        /* =====================
           SMALL PHONES (< 400px)
        ===================== */
        @media (max-width: 399px) {
            .mobile-banner {
                padding: 1rem 1.25rem 1.75rem;
            }

            .register-card {
                padding: 1.25rem 1rem 1.75rem;
            }
        }

        /* =====================
           SHORT DESKTOP SCREENS
        ===================== */
        @media (min-width: 992px) and (max-height: 750px) {
            .register-card {
                padding: 1.5rem;
            }

            .shield-icon-large {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
                margin-bottom: 0.75rem;
            }

            .register-card h3 {
                font-size: 1.25rem;
            }

            .mb-3 {
                margin-bottom: 0.625rem !important;
            }
        }
    </style>
</head>

<body>

    <!-- MOBILE BANNER: tampil hanya di layar < 992px -->
    <div class="mobile-banner">
        <div class="banner-inner">
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="mobile-logo-shield">
                    <i class="bi bi-people-fill text-white"></i>
                </div>
                <div>
                    <div class="fw-bold text-white" style="font-size: 1.4rem; line-height: 1.1;">SIKOS</div>
                    <div class="text-white-50" style="font-size: 0.7rem;">Sistem Informasi Administrasi</div>
                </div>
            </div>
            <p class="text-white mb-0" style="font-size: 0.8rem; opacity: 0.85;">
                Buat akun <strong>Ketua Organisasi</strong> untuk mulai mengelola kegiatan secara digital.
            </p>
        </div>
    </div>

    <div class="container-fluid px-0">
        <div class="row g-0 min-vh-100">

            <!-- LEFT SIDE (desktop only) -->
            <div class="col-lg-6 left-panel d-none d-lg-flex flex-column justify-content-center position-relative">

                <!-- Decoration Dots -->
                <div class="decoration-dots d-none d-lg-block">
                    <i class="bi bi-circle-fill"></i>
                    <i class="bi bi-circle-fill"></i>
                    <i class="bi bi-circle-fill"></i>
                    <br>
                    <i class="bi bi-circle-fill"></i>
                    <i class="bi bi-circle-fill"></i>
                    <i class="bi bi-circle-fill"></i>
                    <br>
                    <i class="bi bi-circle-fill"></i>
                    <i class="bi bi-circle-fill"></i>
                    <i class="bi bi-circle-fill"></i>
                </div>

                <div class="left-content position-relative z-1">
                    <!-- Logo & Title -->
                    <div class="mb-3">
                        <div class="logo-shield">
                            <i class="bi bi-people-fill text-white"></i>
                        </div>
                        <h1 class="fw-bold text-white mb-0">SIKOS</h1>
                        <p class="text-white-50 mb-0" style="font-size: 0.85rem;">Sistem Informasi Administrasi</p>
                        <p class="text-white-50 small mb-0">Organisasi & Kegiatan</p>
                    </div>

                    <!-- Main Content -->
                    <h2 class="text-white fw-bold mb-2">
                        Registrasi Akun<br>
                        <span class="text-warning">Ketua Organisasi</span><br>
                        Sekolah
                    </h2>

                    <p class="text-white-50 mb-3" style="font-size: 0.85rem;">
                        Isi data dengan benar dan lengkap. Username dan email harus unik
                        serta tidak boleh sama satu sama lain.
                    </p>

                    <!-- Info Steps -->
                    <div class="info-box d-none d-md-block">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-person-plus text-white"></i>
                            </div>
                            <div>
                                <span class="info-title text-white">Username Unik</span>
                                <small class="text-white">Gunakan username yang belum pernah dipakai pengguna
                                    lain.</small>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-envelope-check text-white"></i>
                            </div>
                            <div>
                                <span class="info-title text-white">Email Valid</span>
                                <small class="text-white">Pastikan email aktif dan berbeda dari username Anda.</small>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-shield-lock text-white"></i>
                            </div>
                            <div>
                                <span class="info-title text-white">Password Aman</span>
                                <small class="text-white">Minimal 8 karakter, konfirmasi harus cocok dengan
                                    password.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Quote Box -->
                    <div class="quote-box d-none d-md-block">
                        <div class="quote-icon">
                            <i class="bi bi-quote"></i>
                        </div>
                        <p class="mb-0">
                            Bersama SIKOS, wujudkan organisasi yang
                            <strong class="text-success">aktif</strong>,
                            <strong class="text-success">kreatif</strong>, dan
                            <strong class="text-success">berprestasi</strong>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="col-lg-6 right-panel">
                <div class="register-card mx-auto">

                    <!-- Icon -->
                    <div class="shield-icon-large">
                        <i class="bi bi-person-plus"></i>
                    </div>

                    <!-- Title -->
                    <h3 class="text-center fw-bold mb-1">Daftar Akun Ketua</h3>
                    <p class="text-center text-muted mb-3" style="font-size: 0.85rem;">Isi formulir di bawah untuk
                        membuat akun</p>

                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div class="alert alert-danger py-2 small mb-3" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Register Form -->
                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                    placeholder="Masukkan username" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                                    placeholder="Masukkan email" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Minimal 8 karakter" required>
                                <span class="input-group-text input-group-append"
                                    style="border-left: none; border-radius: 0 8px 8px 0;">
                                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" placeholder="Ulangi password" required>
                                <span class="input-group-text input-group-append"
                                    style="border-left: none; border-radius: 0 8px 8px 0;">
                                    <i class="bi bi-eye-slash" id="toggleConfirm"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-register w-100 mb-3">
                            <i class="bi bi-person-check me-2"></i>Daftar sebagai Ketua
                        </button>
                    </form>

                    <p class="text-center small mb-0 text-muted">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Kembali ke login</a>
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toggle Password Visibility -->
    <script>
        function setupToggle(toggleId, inputId) {
            document.getElementById(toggleId).addEventListener('click', function() {
                const input = document.getElementById(inputId);
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        }

        setupToggle('togglePassword', 'password');
        setupToggle('toggleConfirm', 'password_confirmation');
    </script>

</body>

</html>
