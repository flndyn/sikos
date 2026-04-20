<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Kegiatan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
        }

        h2 {
            margin: 0 0 12px;
            font-size: 18px;
        }

        .meta {
            margin-bottom: 16px;
            color: #4b5563;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
            text-align: left;
        }

        th {
            background: #f3f4f6;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2>Laporan Kegiatan</h2>
    <p class="meta">Dicetak pada: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th style="width: 48px;">No</th>
                <th>Nama Kegiatan</th>
                <th>Organisasi</th>
                <th style="width: 100px;">Tanggal</th>
                <th>Laporan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporan as $item)
                @php
                    $downloadUrl = route('admin.laporan.download', $item->id);
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->kegiatan?->nama_kegiatan ?? '-' }}</td>
                    <td>{{ $item->kegiatan?->organisasi?->nama_organisasi ?? '-' }}</td>
                    <td>{{ $item->kegiatan?->tanggal_mulai?->format('Y-m-d') ?? '-' }}</td>
                    <td>
                        @if ($item->file_laporan)
                            <a href="{{ $downloadUrl }}">Download laporan</a>
                        @else
                            {{ $item->isi_laporan ?: '-' }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data laporan kegiatan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
