<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }

        .message {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 30px;
            font-size: 15px;
            line-height: 1.8;
            color: #555;
        }

        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff !important;
            padding: 14px 40px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            margin: 30px 0;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .action-button-wrapper {
            text-align: center;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.6);
        }

        .divider {
            border: none;
            border-top: 1px solid #e5e7eb;
            margin: 30px 0;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .footer p {
            font-size: 13px;
            color: #666;
            margin-bottom: 10px;
        }

        .footer-links {
            margin-top: 15px;
        }

        .footer-links a {
            color: #667eea;
            text-decoration: none;
            font-size: 12px;
            margin: 0 10px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .app-name {
            font-size: 12px;
            color: #999;
            margin-top: 15px;
        }

        @media (max-width: 600px) {
            .container {
                border-radius: 0;
            }

            .header {
                padding: 20px 15px;
            }

            .header h1 {
                font-size: 24px;
            }

            .content {
                padding: 25px 15px;
            }

            .action-button {
                width: 100%;
                text-align: center;
                padding: 16px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>{{ $title }}</h1>
            <p>SIKOS - Sistem Informasi Kegiatan Organisasi</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Halo {{ $notifiable->name }},</div>

            <div class="message">
                {{ $contentMessage }}
            </div>

            @if ($actionUrl && $actionLabel)
                <div class="action-button-wrapper">
                    <a href="{{ $actionUrl }}" class="action-button">{{ $actionLabel }}</a>
                </div>
            @endif

            <hr class="divider">

            <p style="font-size: 14px; color: #666; line-height: 1.8;">
                Jika tombol di atas tidak berfungsi, silakan salin dan tempel URL berikut ke browser Anda:
            </p>
            <p
                style="font-size: 12px; color: #667eea; word-break: break-all; background-color: #f0f4ff; padding: 10px; border-radius: 4px; margin-top: 10px;">
                {{ $actionUrl ?? 'N/A' }}
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih telah menggunakan SIKOS.</p>
            <p style="font-size: 12px; color: #999;">Sistem Informasi Kegiatan Organisasi</p>
            <div class="footer-links">
                <a href="{{ url('/') }}">Kembali ke Aplikasi</a>
                <a href="mailto:support@sikos.local">Dukungan</a>
            </div>
            <div class="app-name">
                &copy; 2026 SIKOS. Semua hak dilindungi.
            </div>
        </div>
    </div>
</body>

</html>
