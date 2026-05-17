<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login SIKOS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-purple: #7c3aed;
            --gradient-bg: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #6366f1 100%);
            --gradient-btn: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
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
            }

            .login-card {
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

        .logo-shield {
            width: 45px;
            height: 45px;
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
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

        .feature-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .feature-item {
            text-align: center;
            padding: 0.5rem;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.25rem;
            font-size: 1.25rem;
        }

        .feature-item small {
            font-size: 0.7rem;
            line-height: 1.2;
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
            color: var(--primary-purple);
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        /* Right Side Styling */
        .right-panel {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            padding: 1.5rem;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            padding: 2rem 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            max-width: 420px;
            width: 100%;
        }

        .shield-icon-large {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: var(--primary-blue);
            font-size: 1.75rem;
        }

        .login-card h3 {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        .login-card>p {
            font-size: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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

        .input-group>span:last-child .input-group-text {
            border-left: none;
            border-right: 2px solid #e5e7eb;
            border-radius: 0 8px 8px 0;
        }

        .btn-login {
            background: var(--gradient-btn);
            border: none;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: white;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
            color: white;
        }

        .role-btn {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.875rem 0.5rem;
            background: white;
            transition: all 0.3s;
            text-align: center;
            cursor: pointer;
            height: 100%;
        }

        .role-btn:hover {
            border-color: var(--primary-blue);
            background: #f9fafb;
            transform: translateY(-2px);
        }

        .role-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 0.35rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .role-admin .role-icon {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #2563eb;
        }

        .role-ketua .role-icon {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #16a34a;
        }

        .role-pembina .role-icon {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #d97706;
        }

        .role-btn small {
            font-size: 0.7rem;
            line-height: 1.2;
        }

        .divider-text {
            position: relative;
            text-align: center;
            margin: 1rem 0;
        }

        .divider-text::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e7eb;
        }

        .divider-text span {
            background: white;
            padding: 0 0.75rem;
            position: relative;
            color: #6b7280;
            font-size: 0.75rem;
        }

        .form-check-label {
            font-size: 0.8rem;
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

            .login-card {
                border-radius: 24px 24px 0 0;
                margin-top: -18px;
                position: relative;
                z-index: 1;
                padding: 1.5rem 1.25rem 2rem;
                box-shadow: 0 -6px 24px rgba(0, 0, 0, 0.08);
                max-width: 100%;
                min-height: calc(100vh - 140px);
            }

            .shield-icon-large {
                width: 50px;
                height: 50px;
                font-size: 1.4rem;
                margin-bottom: 0.75rem;
            }

            .login-card h3 {
                font-size: 1.25rem;
            }

            .login-card>p {
                font-size: 0.8rem;
            }

            /* Prevent iOS zoom on input focus */
            .form-control {
                font-size: 16px;
                padding: 0.6rem 0.75rem;
            }

            .input-group-text {
                padding: 0.6rem 0.65rem;
            }

            .btn-login {
                padding: 0.8rem;
                font-size: 0.95rem;
            }

            .role-btn {
                padding: 0.65rem 0.25rem;
            }

            .role-icon {
                width: 34px;
                height: 34px;
                font-size: 1rem;
                margin-bottom: 0.2rem;
            }

            .role-btn small {
                font-size: 0.65rem;
            }

            .divider-text {
                margin: 0.875rem 0;
            }

            /* Override Bootstrap row height on mobile */
            .row.g-0.h-100 {
                height: auto !important;
            }
        }

        /* =====================
           SMALL PHONES (< 400px)
        ===================== */
        @media (max-width: 399px) {
            .mobile-banner {
                padding: 1rem 1.25rem 1.75rem;
            }

            .login-card {
                padding: 1.25rem 1rem 1.75rem;
            }

            .role-btn {
                padding: 0.5rem 0.1rem;
            }

            .role-icon {
                width: 30px;
                height: 30px;
                font-size: 0.875rem;
            }

            .role-btn small {
                font-size: 0.6rem;
            }
        }

        /* =====================
           SHORT DESKTOP SCREENS
        ===================== */
        @media (min-width: 992px) and (max-height: 700px) {
            .login-card {
                padding: 1.5rem;
            }

            .shield-icon-large {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
                margin-bottom: 0.75rem;
            }

            .login-card h3 {
                font-size: 1.25rem;
            }

            .mb-3 {
                margin-bottom: 0.75rem !important;
            }

            .role-btn {
                padding: 0.625rem 0.375rem;
            }

            .role-icon {
                width: 35px;
                height: 35px;
                font-size: 1.1rem;
                margin-bottom: 0.25rem;
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
                Kelola organisasi sekolah secara <strong>digital</strong>,
                <strong>transparan</strong>, dan <strong>terstruktur</strong>.
            </p>
        </div>
    </div>

    <div class="container-fluid px-0">
        <div class="row g-0 h-100">

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
                        Kelola Kegiatan Organisasi<br>
                        <span class="text-warning">Lebih Mudah</span><br>
                        dan Terstruktur
                    </h2>

                    <p class="text-white-50 mb-3" style="font-size: 0.85rem;">
                        SIKOS membantu sekolah dalam mengelola kegiatan organisasi siswa
                        secara digital, transparan, dan terstruktur.
                    </p>

                    <!-- Features -->
                    <div class="feature-box d-none d-md-block">
                        <div class="row g-2">
                            <div class="col-4">
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="bi bi-shield-check text-white"></i>
                                    </div>
                                    <small class="text-white">Aman & Terpercaya</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="bi bi-graph-up text-white"></i>
                                    </div>
                                    <small class="text-white">Data Akurat & Transparan</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="bi bi-clock text-white"></i>
                                    </div>
                                    <small class="text-white">Efisien & Hemat Waktu</small>
                                </div>
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
                            <strong class="text-primary">aktif</strong>,
                            <strong class="text-primary">kreatif</strong>, dan
                            <strong class="text-primary">berprestasi</strong>.
                        </p>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="col-lg-6 right-panel">
                <div class="login-card mx-auto">

                    <!-- Shield Icon -->
                    <div class="shield-icon-large">
                        <i class="bi bi-shield-lock"></i>
                    </div>

                    <!-- Welcome Text -->
                    <h3 class="text-center fw-bold mb-1">Selamat Datang!</h3>
                    <p class="text-center text-muted mb-3">Masuk untuk melanjutkan ke SIKOS</p>

                    <!-- Error Alert -->
                    @if ($errors->any())
                        <div class="alert alert-danger py-2 small mb-3" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>{{ $errors->first() }}
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login.attempt') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label small fw-semibold mb-1">Email atau Username</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" name="login" value="{{ old('login') }}" class="form-control"
                                    placeholder="Masukkan email atau username" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold mb-1">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Masukkan password" required>
                                <span class="input-group-text"
                                    style="cursor: pointer; border-left: none; border-radius: 0 8px 8px 0;">
                                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe"
                                    style="width: 1rem; height: 1rem;">
                                <label class="form-check-label ms-1" for="rememberMe">
                                    Ingat saya
                                </label>
                            </div>
                            <a href="#" class="text-decoration-none" style="font-size: 0.8rem;">Lupa
                                password?</a>
                        </div>

                        <button type="submit" class="btn btn-login w-100 mb-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="divider-text">
                        <span>atau masuk sebagai</span>
                    </div>

                    <!-- Role Buttons -->
                    <div class="row g-2 mb-3">
                        <div class="col-4">
                            <div class="role-btn role-admin">
                                <div class="role-icon">
                                    <i class="bi bi-person-check"></i>
                                </div>
                                <small class="fw-semibold d-block">Admin</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="role-btn role-ketua">
                                <div class="role-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <small class="fw-semibold d-block">Ketua Organisasi</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="role-btn role-pembina">
                                <div class="role-icon">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <small class="fw-semibold d-block">Pembina</small>
                            </div>
                        </div>
                    </div>

                    <!-- Register Link -->
                    <p class="text-center small mb-0 text-muted">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">Daftar di sini</a>
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toggle Password Visibility -->
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = this.closest('.input-group').querySelector(
                'input[type="password"], input[type="text"]');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>

</body>

</html>
