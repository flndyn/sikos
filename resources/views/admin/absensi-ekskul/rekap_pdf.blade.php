<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rekap Kehadiran — {{ $organisasi->nama_organisasi }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #111827;
        }

        h2 {
            margin: 0 0 4px;
            font-size: 18px;
        }

        h3 {
            margin: 0 0 12px;
            font-size: 14px;
            font-weight: normal;
            color: #4b5563;
        }

        .meta {
            margin-bottom: 16px;
            color: #4b5563;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            vertical-align: top;
            text-align: left;
        }

        th {
            background: #f3f4f6;
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge-success {
            background-color: #16a34a;
            color: #fff;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
        }

        .badge-warning {
            background-color: #d97706;
            color: #fff;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
        }

        .badge-danger {
            background-color: #dc2626;
            color: #fff;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
        }

        .summary {
            margin-top: 16px;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <h2>Rekap Kehadiran Ekstrakurikuler</h2>
    <h3>{{ $organisasi->nama_organisasi }}</h3>

    <p class="meta">
        @if (($exportType ?? 'semester') === 'bulan')
            Bulan: {{ $namaBulan ?? '-' }} {{ $tahun ?? '-' }}
        @else
            Semester: {{ $semester ?? 'Semua Semester' }}
            <br>
            Tahun Ajaran: {{ $tahunAjaran ?? 'Semua Tahun Ajaran' }}
        @endif
        <br>
        Total Pertemuan: {{ $totalPertemuan }}
        <br>
        Dicetak pada: {{ now()->format('d-m-Y H:i') }}
    </p>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 35px;">No</th>
                <th>Nama Lengkap</th>
                <th style="width: 70px;">Kelas</th>
                <th style="width: 100px;">No. HP</th>
                <th class="text-center" style="width: 50px;">Hadir</th>
                <th class="text-center" style="width: 50px;">Izin</th>
                <th class="text-center" style="width: 50px;">Sakit</th>
                <th class="text-center" style="width: 50px;">Alfa</th>
                <th class="text-center" style="width: 70px;">% Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($anggota as $member)
                @php
                    $total = $member->hadir_count + $member->izin_count + $member->sakit_count + $member->alfa_count;
                    $persen = $total > 0 ? round(($member->hadir_count / $total) * 100, 1) : 0;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $member->nama }}</td>
                    <td>{{ $member->kelas }}</td>
                    <td>{{ $member->no_hp ?? '-' }}</td>
                    <td class="text-center">{{ $member->hadir_count }}</td>
                    <td class="text-center">{{ $member->izin_count }}</td>
                    <td class="text-center">{{ $member->sakit_count }}</td>
                    <td class="text-center">{{ $member->alfa_count }}</td>
                    <td class="text-center">
                        @if ($persen >= 75)
                            <span class="badge-success">{{ $persen }}%</span>
                        @elseif ($persen >= 50)
                            <span class="badge-warning">{{ $persen }}%</span>
                        @else
                            <span class="badge-danger">{{ $persen }}%</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Belum ada data anggota.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        @php
            $totalHadir = $anggota->sum('hadir_count');
            $totalIzin = $anggota->sum('izin_count');
            $totalSakit = $anggota->sum('sakit_count');
            $totalAlfa = $anggota->sum('alfa_count');
            $grandTotal = $totalHadir + $totalIzin + $totalSakit + $totalAlfa;
            $avgKehadiran = $grandTotal > 0 ? round(($totalHadir / $grandTotal) * 100, 1) : 0;
        @endphp
        <strong>Ringkasan:</strong>
        Total Anggota: {{ $anggota->count() }} |
        Rata-rata Kehadiran: {{ $avgKehadiran }}% |
        Total Hadir: {{ $totalHadir }} |
        Total Izin: {{ $totalIzin }} |
        Total Sakit: {{ $totalSakit }} |
        Total Alfa: {{ $totalAlfa }}
    </div>
</body>

</html>
