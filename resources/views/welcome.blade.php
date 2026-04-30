<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIOKAS - SMAN 1 Paiton</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0d6efd;
            --dark-blue: #0a1628;
            --navy: #0a1a3a;
            --light-bg: #f0f6ff;
            --light-blue-bg: #e8f0fe;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: #333;
            overflow-x: hidden;
            background: #fff;
        }

        /* ========== NAVBAR ========== */
        .navbar-siokas {
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            padding: 12px 0;
        }

        .navbar-siokas .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            color: var(--primary-blue) !important;
            font-size: 1.25rem;
            text-decoration: none;
        }

        .navbar-siokas .navbar-brand small {
            display: block;
            font-size: 0.7rem;
            font-weight: 500;
            color: #555;
            line-height: 1.2;
        }

        .navbar-siokas .logo-badge {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #1565c0, #0d47a1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }

        .navbar-siokas .logo-badge::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 50%;
            transform: translateX(-50%);
            width: 14px;
            height: 14px;
            background: #f5c842;
            border-radius: 50%;
            opacity: 0.9;
        }

        .navbar-siokas .logo-badge i {
            position: relative;
            z-index: 1;
        }

        .nav-link-siokas {
            color: #555 !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 8px 16px !important;
            transition: color 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .nav-link-siokas:hover,
        .nav-link-siokas.active {
            color: var(--primary-blue) !important;
        }

        .nav-link-siokas.active {
            position: relative;
        }

        .nav-link-siokas.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 16px;
            right: 16px;
            height: 2px;
            background: var(--primary-blue);
            border-radius: 2px;
        }

        .btn-login-nav {
            background: var(--primary-blue);
            color: #fff !important;
            border: none;
            border-radius: 8px;
            padding: 8px 24px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-login-nav:hover {
            background: #0b5ed7;
            color: #fff;
            text-decoration: none;
        }

        /* HERO SECTION */
        .hero-section {
            background: linear-gradient(180deg, var(--light-bg) 0%, #e8f0fe 100%);
            padding: 50px 0 60px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(13, 110, 253, 0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .badge-sistem {
            display: inline-block;
            background: #dbeafe;
            color: var(--primary-blue);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 14px;
            border-radius: 20px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }

        .hero-title {
            font-size: 2.1rem;
            font-weight: 800;
            color: var(--dark-blue);
            line-height: 1.25;
            margin-bottom: 10px;
        }

        .hero-title span {
            color: var(--primary-blue);
        }

        .hero-subtitle {
            font-size: 1.05rem;
            font-weight: 600;
            color: #444;
            margin-bottom: 12px;
        }

        .hero-desc {
            font-size: 0.92rem;
            color: #666;
            line-height: 1.7;
            margin-bottom: 28px;
            max-width: 480px;
        }

        .btn-login-hero {
            background: var(--primary-blue);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 28px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-login-hero:hover {
            background: #0b5ed7;
            color: #fff;
            transform: translateY(-1px);
            text-decoration: none;
        }

        .btn-lihat-kegiatan {
            background: #fff;
            color: var(--primary-blue);
            border: 1.5px solid var(--primary-blue);
            border-radius: 8px;
            padding: 12px 28px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-lihat-kegiatan:hover {
            background: #e8f0fe;
            color: var(--primary-blue);
            text-decoration: none;
        }

        /* Hero Illustration */
        .hero-illustration {
            position: relative;
            padding: 20px;
        }

        .hero-illustration .illustration-wrapper {
            position: relative;
            width: 100%;
            max-width: 560px;
            margin: 0 auto;
        }

        .hero-illustration .bg-cloud {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.08) 0%, rgba(13, 110, 253, 0.03) 100%);
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
            z-index: 0;
        }

        .hero-illustration .building {
            position: relative;
            z-index: 1;
            text-align: center;
            margin-bottom: -10px;
        }

        .building-svg {
            width: 100%;
            max-width: 320px;
            margin: 0 auto;
            display: block;
        }

        .hero-illustration .students-group {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: -30px;
            flex-wrap: wrap;
        }

        .student-card {
            background: #fff;
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            width: 100px;
            transition: transform 0.3s;
        }

        .student-card:hover {
            transform: translateY(-4px);
        }

        .student-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            margin: 0 auto 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
        }

        .student-card .student-name {
            font-size: 0.7rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 2px;
        }

        .student-card .student-role {
            font-size: 0.6rem;
            color: #888;
        }

        .avatar-blue {
            background: linear-gradient(135deg, #64b5f6, #1e88e5);
        }

        .avatar-green {
            background: linear-gradient(135deg, #81c784, #43a047);
        }

        .avatar-purple {
            background: linear-gradient(135deg, #ce93d8, #8e24aa);
        }

        .avatar-orange {
            background: linear-gradient(135deg, #ffb74d, #f57c00);
        }

        /* Flag */
        .flag-indonesia {
            position: absolute;
            top: 10px;
            right: 30px;
            z-index: 3;
        }

        .flag-pole {
            width: 3px;
            height: 60px;
            background: #888;
            margin: 0 auto;
        }

        .flag-cloth {
            display: flex;
            width: 30px;
            height: 18px;
            margin-left: 3px;
        }

        .flag-red {
            width: 100%;
            height: 50%;
            background: #e53935;
            border-radius: 1px 1px 0 0;
        }

        .flag-white {
            width: 100%;
            height: 50%;
            background: #fff;
            border: 1px solid #eee;
            border-top: none;
            border-radius: 0 0 1px 1px;
        }

        /*FEATURES SECTION*/
        .features-section {
            padding: 60px 0;
            background: #fff;
        }

        .badge-fitur {
            display: inline-block;
            background: #dbeafe;
            color: var(--primary-blue);
            font-size: 0.7rem;
            font-weight: 600;
            padding: 3px 12px;
            border-radius: 15px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-blue);
            margin-bottom: 40px;
        }

        .feature-card {
            background: #fff;
            border: 1.5px solid #e8ecf1;
            border-radius: 14px;
            padding: 30px 20px;
            text-align: center;
            transition: all 0.3s;
            height: 100%;
        }

        .feature-card:hover {
            border-color: var(--primary-blue);
            box-shadow: 0 8px 30px rgba(13, 110, 253, 0.1);
            transform: translateY(-4px);
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 1.5rem;
        }

        .icon-blue {
            background: #dbeafe;
            color: var(--primary-blue);
        }

        .icon-green {
            background: #d1fae5;
            color: #16a34a;
        }

        .icon-yellow {
            background: #fef3c7;
            color: #d97706;
        }

        .icon-purple {
            background: #ede9fe;
            color: #7c3aed;
        }

        .feature-card h5 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--dark-blue);
            margin-bottom: 10px;
        }

        .feature-card p {
            font-size: 0.82rem;
            color: #666;
            line-height: 1.6;
            margin: 0;
        }

        /*KEGIATAN SECTION*/
        .kegiatan-section {
            padding: 50px 0 60px;
            background: var(--light-bg);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .section-header h4 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark-blue);
            margin: 0;
        }

        .btn-lihat-semua {
            color: var(--primary-blue);
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            transition: color 0.2s;
        }

        .btn-lihat-semua:hover {
            color: #0b5ed7;
            text-decoration: none;
        }

        .table-kegiatan {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            margin: 0 auto;
        }

        .table-responsive {
            display: flex;
            justify-content: center;
        }

        .table-kegiatan thead th {
            background: #f8fafc;
            border-bottom: 1.5px solid #e8ecf1;
            font-size: 0.78rem;
            font-weight: 600;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            white-space: nowrap;
        }

        .table-kegiatan tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            font-size: 0.85rem;
            border-bottom: 1px solid #f0f2f5;
            color: #444;
        }

        .table-kegiatan tbody tr:last-child td {
            border-bottom: none;
        }

        .table-kegiatan tbody tr:hover {
            background: #fafbff;
        }

        .kegiatan-thumb {
            width: 48px;
            height: 36px;
            border-radius: 6px;
            object-fit: cover;
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .kegiatan-nama {
            font-weight: 600;
            color: var(--dark-blue);
            font-size: 0.85rem;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-disetujui {
            background: #d1fae5;
            color: #059669;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-ditolak {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-detail {
            background: none;
            border: 1.5px solid var(--primary-blue);
            color: var(--primary-blue);
            border-radius: 6px;
            padding: 4px 16px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-detail:hover {
            background: var(--primary-blue);
            color: #fff;
        }

        /*FOOTER*/
        .footer-siokas {
            background: var(--dark-blue);
            color: #ccc;
            padding: 50px 0 0;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .footer-brand .logo-badge {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #1565c0, #0d47a1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .footer-brand h5 {
            color: #fff;
            font-weight: 700;
            font-size: 1.2rem;
            margin: 0;
        }

        .footer-brand small {
            color: #aaa;
            font-size: 0.75rem;
            display: block;
        }

        .footer-desc {
            font-size: 0.82rem;
            color: #aaa;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .footer-social a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.08);
            color: #ccc;
            margin-right: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .footer-social a:hover {
            background: var(--primary-blue);
            color: #fff;
        }

        .footer-title {
            color: #fff;
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #aaa;
            text-decoration: none;
            font-size: 0.83rem;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: #fff;
        }

        .footer-info-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 12px;
            font-size: 0.83rem;
            color: #aaa;
        }

        .footer-info-item i {
            color: var(--primary-blue);
            margin-top: 3px;
            flex-shrink: 0;
        }

        .btn-hubungi {
            background: var(--primary-blue);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
            margin-top: 10px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-hubungi:hover {
            background: #0b5ed7;
            color: #fff;
            text-decoration: none;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 20px 0;
            margin-top: 40px;
            text-align: center;
            font-size: 0.8rem;
            color: #888;
        }

        /*RESPONSIVE*/
        @media (max-width: 991px) {
            .hero-title {
                font-size: 1.7rem;
            }

            .hero-illustration {
                margin-top: 30px;
            }

            .student-card {
                width: 80px;
                padding: 12px;
            }

            .student-avatar {
                width: 44px;
                height: 44px;
                font-size: 1.2rem;
            }
        }

        @media (max-width: 767px) {
            .hero-title {
                font-size: 1.4rem;
            }

            .hero-section {
                padding: 30px 0 40px;
            }

            .features-section {
                padding: 40px 0;
            }

            .section-title {
                font-size: 1.25rem;
            }

            .table-kegiatan {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-siokas sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <div class="logo-badge">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div>
                    SIKOS
                    <small>SMAN 1 Paiton</small>
                </div>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center gap-1">
                    <li class="nav-item">
                        <a class="nav-link nav-link-siokas active" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-siokas" href="#fitur">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-siokas" href="#kegiatan">Kegiatan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-siokas" href="#footer">Informasi</a>
                    </li>
                </ul>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-login-nav ms-lg-3">
                        <i class="bi bi-person-fill me-1"></i> Login
                    </a>
                @else
                    <a href="{{ route('dashboard.redirect') }}" class="btn btn-login-nav ms-lg-3">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                @endguest
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="badge-sistem">Sistem Informasi</div>
                    <h1 class="hero-title">
                        Sistem Informasi<br>
                        Administrasi Kegiatan<br>
                        <span>Organisasi Siswa</span>
                    </h1>
                    <p class="hero-subtitle">SMAN 1 Paiton</p>
                    <p class="hero-desc">
                        Kelola kegiatan organisasi, pengajuan proposal, dokumentasi,
                        hingga laporan pertanggungjawaban secara digital,
                        cepat, dan transparan.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-login-hero">
                                <i class="bi bi-lock-fill me-2"></i>Login Sistem
                            </a>
                        @else
                            <a href="{{ route('dashboard.redirect') }}" class="btn btn-login-hero">
                                <i class="bi bi-speedometer2 me-2"></i>Buka Dashboard
                            </a>
                        @endguest
                        <a href="#kegiatan" class="btn btn-lihat-kegiatan">
                            <i class="bi bi-calendar-event me-2"></i>Lihat Kegiatan
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-illustration">
                        <div class="illustration-wrapper">
                            <div class="bg-cloud"></div>

                            <!-- Indonesia Flag -->
                            <div class="flag-indonesia">
                                <div class="flag-pole"></div>
                                <div class="flag-cloth">
                                    <div class="flag-red"></div>
                                    <div class="flag-white"></div>
                                </div>
                            </div>

                            <!-- School Building SVG -->
                            <div class="building">
                                <svg class="building-svg" viewBox="0 0 320 200" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="20" y="10" width="280" height="170" rx="16" fill="#dbeafe"
                                        opacity="0.5" />
                                    <ellipse cx="80" cy="35" rx="25" ry="12" fill="white"
                                        opacity="0.8" />
                                    <ellipse cx="100" cy="32" rx="20" ry="10" fill="white"
                                        opacity="0.9" />
                                    <ellipse cx="220" cy="28" rx="22" ry="10" fill="white"
                                        opacity="0.7" />
                                    <rect x="60" y="60" width="200" height="120" rx="4" fill="#e8f0fe" />
                                    <rect x="60" y="60" width="200" height="120" rx="4" fill="none"
                                        stroke="#93c5fd" stroke-width="1.5" />
                                    <polygon points="50,60 160,25 270,60" fill="#60a5fa" opacity="0.3" />
                                    <polygon points="50,60 160,25 270,60" fill="none" stroke="#60a5fa"
                                        stroke-width="1.5" />
                                    <rect x="110" y="70" width="100" height="20" rx="3"
                                        fill="white" />
                                    <text x="160" y="84" text-anchor="middle" font-size="8" font-weight="700"
                                        fill="#1e40af">SMAN 1 Paiton</text>
                                    <rect x="80" y="100" width="28" height="24" rx="2" fill="white"
                                        stroke="#93c5fd" stroke-width="1" />
                                    <rect x="85" y="104" width="8" height="16" rx="1" fill="#93c5fd"
                                        opacity="0.3" />
                                    <rect x="97" y="104" width="8" height="16" rx="1" fill="#93c5fd"
                                        opacity="0.3" />
                                    <rect x="146" y="100" width="28" height="24" rx="2"
                                        fill="white" stroke="#93c5fd" stroke-width="1" />
                                    <rect x="151" y="104" width="8" height="16" rx="1"
                                        fill="#93c5fd" opacity="0.3" />
                                    <rect x="163" y="104" width="8" height="16" rx="1"
                                        fill="#93c5fd" opacity="0.3" />
                                    <rect x="212" y="100" width="28" height="24" rx="2"
                                        fill="white" stroke="#93c5fd" stroke-width="1" />
                                    <rect x="217" y="104" width="8" height="16" rx="1"
                                        fill="#93c5fd" opacity="0.3" />
                                    <rect x="229" y="104" width="8" height="16" rx="1"
                                        fill="#93c5fd" opacity="0.3" />
                                    <rect x="80" y="135" width="28" height="24" rx="2" fill="white"
                                        stroke="#93c5fd" stroke-width="1" />
                                    <rect x="146" y="135" width="28" height="24" rx="2"
                                        fill="white" stroke="#93c5fd" stroke-width="1" />
                                    <rect x="212" y="135" width="28" height="24" rx="2"
                                        fill="white" stroke="#93c5fd" stroke-width="1" />
                                    <rect x="145" y="140" width="30" height="40" rx="2"
                                        fill="#1e88e5" />
                                    <circle cx="170" cy="162" r="2" fill="white" />
                                    <rect x="10" y="175" width="300" height="25" rx="12" fill="#86efac"
                                        opacity="0.4" />
                                </svg>
                            </div>

                            <!-- Students Group -->
                            <div class="students-group">
                                <div class="student-card">
                                    <div class="student-avatar avatar-blue"><i class="bi bi-person-fill"></i></div>
                                    <div class="student-name">Andi</div>
                                    <div class="student-role">Ketua OSIS</div>
                                </div>
                                <div class="student-card">
                                    <div class="student-avatar avatar-green"><i class="bi bi-person-fill"></i></div>
                                    <div class="student-name">Siti</div>
                                    <div class="student-role">Sekretaris</div>
                                </div>
                                <div class="student-card">
                                    <div class="student-avatar avatar-purple"><i class="bi bi-person-fill"></i></div>
                                    <div class="student-name">Budi</div>
                                    <div class="student-role">Bendahara</div>
                                </div>
                                <div class="student-card">
                                    <div class="student-avatar avatar-orange"><i class="bi bi-person-fill"></i></div>
                                    <div class="student-name">Dewi</div>
                                    <div class="student-role">Anggota</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="features-section" id="fitur">
        <div class="container">
            <div class="text-center">
                <div class="badge-fitur">Fitur Utama</div>
                <h2 class="section-title">Kelola Organisasi dengan Mudah</h2>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon icon-blue"><i class="bi bi-file-earmark-text"></i></div>
                        <h5>Pengajuan Proposal</h5>
                        <p>Ajukan proposal kegiatan secara online dengan mudah dan terstruktur.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon icon-green"><i class="bi bi-graph-up"></i></div>
                        <h5>Monitoring Kegiatan</h5>
                        <p>Pantau status kegiatan mulai dari pengajuan hingga selesai secara real-time.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon icon-yellow"><i class="bi bi-camera"></i></div>
                        <h5>Dokumentasi Digital</h5>
                        <p>Unggah dan kelola dokumentasi kegiatan dengan rapi dalam satu sistem terpusat.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon icon-purple"><i class="bi bi-shield-check"></i></div>
                        <h5>Validasi Pembina</h5>
                        <p>Proses validasi oleh pembina dan admin lebih cepat, akurat, dan transparan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--  KEGIATAN TERBARU SECTION -->
    <section class="kegiatan-section" id="kegiatan">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <div class="table-responsive">
                        <table class="table table-kegiatan mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Kegiatan</th>
                                    <th>Organisasi</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kegiatanTerbaru as $kegiatan)
                                    @php
                                        $badgeClass = match ($kegiatan->status) {
                                            'disetujui admin', 'disetujui pembina' => 'status-disetujui',
                                            'ditolak admin', 'ditolak pembina' => 'status-ditolak',
                                            default => 'status-pending',
                                        };

                                        $statusLabel = match ($kegiatan->status) {
                                            'disetujui admin' => 'Disetujui Admin',
                                            'disetujui pembina' => 'Disetujui Pembina',
                                            'ditolak admin' => 'Ditolak Admin',
                                            'ditolak pembina' => 'Ditolak Pembina',
                                            default => 'Pending',
                                        };

                                        $tanggal =
                                            $kegiatan->tanggal_mulai?->format('d M Y') ??
                                            ($kegiatan->created_at?->format('d M Y') ?? '-');
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="kegiatan-thumb"><i class="bi bi-calendar-event"></i></div>
                                                <span class="kegiatan-nama">{{ $kegiatan->nama_kegiatan }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $kegiatan->organisasi?->nama_organisasi ?? '-' }}</td>
                                        <td>{{ $tanggal }}</td>
                                        <td><span class="status-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada data
                                            kegiatan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer-siokas" id="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand">
                        <div class="logo-badge"><i class="bi bi-shield-check"></i></div>
                        <div>
                            <h5>SIKOS</h5>
                            <small>SMAN 1 Paiton</small>
                        </div>
                    </div>
                    <p class="footer-desc">
                        Sistem Informasi Administrasi Kegiatan Organisasi Siswa SMAN 1 Paiton
                        untuk mendukung pengelolaan kegiatan yang efektif, transparan, dan terstruktur.
                    </p>
                    <div class="footer-social">
                        <a href="https://www.facebook.com/sman1paiton/?locale=id_ID" target="_blank"><i
                                class="bi bi-facebook"></i></a>
                        <a href="https://www.instagram.com/sman1paiton/" target="_blank"><i
                                class="bi bi-instagram"></i></a>
                        <a href="https://www.youtube.com/channel/UC1rcAdV4WtrNldIMMkIeLxQ" target="_blank"><i
                                class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="footer-title">Menu</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="#tentang">Tentang</a></li>
                        <li><a href="#kegiatan">Kegiatan</a></li>
                        <li><a href="#informasi">Informasi</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-title">Informasi</h6>
                    <div class="footer-info-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>SMAN 1 Paiton<br>Jl. PB. Sudirman No. 1 Paiton, Probolinggo 67291</span>
                    </div>
                    <div class="footer-info-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span>(0335) 771732</span>
                    </div>
                    <div class="footer-info-item">
                        <i class="bi bi-envelope-fill"></i>
                        <span>sman1paiton@gmail.com</span>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="footer-title">Kontak</h6>
                    <p class="footer-desc" style="margin-bottom: 10px;">
                        Ada pertanyaan atau butuh bantuan? Hubungi kami untuk informasi lebih lanjut.
                    </p>
                    <a href="https://wa.me/6235771054" target="_blank" class="btn btn-hubungi">
                        <i class="bi bi-headset me-2"></i>Hubungi Kami
                    </a>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; 2024 SIKOS SMAN 1 Paiton. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
