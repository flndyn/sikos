<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Absensi Pertemuan {{ $pertemuan->pertemuan_ke }} — {{ $organisasi->nama_organisasi }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #111827;
        }

        h2 {
            margin: 0 0 4px;
            font-size: 16px;
            text-align: center;
        }

        h3 {
            margin: 0 0 16px;
            font-size: 13px;
            font-weight: normal;
            color: #4b5563;
            text-align: center;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            border: none;
            padding: 4px 0;
            font-size: 11px;
        }

        .info-label {
            font-weight: bold;
            width: 120px;
        }

        .info-value {
            color: #1f2937;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            font-size: 11px;
        }

        .data-table th {
            background: #f3f4f6;
            font-weight: bold;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
        }

        .badge-hadir {
            background-color: #dcfce7;
            color: #15803d;
        }

        .badge-izin {
            background-color: #fef9c3;
            color: #a16207;
        }

        .badge-sakit {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .badge-alfa {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .signature-container {
            margin-top: 50px;
            width: 100%;
        }

        .signature-box {
            width: 45%;
            float: left;
            text-align: center;
        }

        .signature-box.right {
            float: right;
        }

        .signature-space {
            height: 60px;
        }
    </style>
</head>

<body>
    <h2>LAPORAN KEHADIRAN PERTEMUAN</h2>
    <h3>{{ $organisasi->nama_organisasi }}</h3>

    <table class="info-table">
        <tr>
            <td class="info-label">Pertemuan Ke</td>
            <td style="width: 10px;">:</td>
            <td class="info-value">{{ $pertemuan->pertemuan_ke }}</td>
            <td class="info-label">Tanggal</td>
            <td style="width: 10px;">:</td>
            <td class="info-value">{{ $pertemuan->tanggal->format('d F Y') }}</td>
        </tr>
        <tr>
            <td class="info-label">Semester</td>
            <td>:</td>
            <td class="info-value">{{ $pertemuan->semester }}</td>
            <td class="info-label">Tahun Ajaran</td>
            <td>:</td>
            <td class="info-value">{{ $pertemuan->tahun_ajaran }}</td>
        </tr>
        <tr>
            <td class="info-label">Pembina</td>
            <td>:</td>
            <td class="info-value">{{ $pertemuan->pembina?->name ?? '-' }}</td>
            <td class="info-label">Total Hadir</td>
            <td>:</td>
            <td class="info-value">
                @php
                    $hadir = $pertemuan->absensi->where('status', 'hadir')->count();
                    $izin = $pertemuan->absensi->where('status', 'izin')->count();
                    $sakit = $pertemuan->absensi->where('status', 'sakit')->count();
                    $alfa = $pertemuan->absensi->where('status', 'alfa')->count();
                @endphp
                {{ $hadir }} Hadir, {{ $izin }} Izin, {{ $sakit }} Sakit, {{ $alfa }} Alfa
            </td>
        </tr>
        @if($pertemuan->deskripsi_kegiatan)
        <tr>
            <td class="info-label">Deskripsi Kegiatan</td>
            <td>:</td>
            <td class="info-value" colspan="4">{{ $pertemuan->deskripsi_kegiatan }}</td>
        </tr>
        @endif
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 40px;">No</th>
                <th>Nama Lengkap</th>
                <th style="width: 120px;">Kelas</th>
                <th style="width: 120px;">No. HP</th>
                <th class="text-center" style="width: 80px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pertemuan->absensi->sortBy('anggota.nama') as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td style="font-weight: bold;">{{ $item->anggota?->nama ?? '-' }}</td>
                    <td>{{ $item->anggota?->kelas ?? '-' }}</td>
                    <td>{{ $item->anggota?->no_hp ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $item->status }}">
                            {{ $item->status }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data absensi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-container">
        <div class="signature-box">
            <p>Mengetahui,</p>
            <p style="font-weight: bold; margin-bottom: 0;">Ketua Organisasi</p>
            <div class="signature-space"></div>
            <p style="text-decoration: underline; font-weight: bold; margin-bottom: 0;">(......................................)</p>
        </div>
        <div class="signature-box right">
            <p>Disahkan oleh,</p>
            <p style="font-weight: bold; margin-bottom: 0;">Pembina Ekstrakurikuler</p>
            <div class="signature-space"></div>
            <p style="text-decoration: underline; font-weight: bold; margin-bottom: 0;">{{ $pertemuan->pembina?->name ?? '(......................................)' }}</p>
        </div>
    </div>
</body>

</html>
