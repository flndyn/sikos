<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Dokumentasi;
use App\Models\LaporanKegiatan;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        // Cari organisasi dimana user adalah ketua
        $organisasi = $user->organisasiSebagaiKetua()->first();
        $organisasiId = $organisasi?->id;

        $stats = [
            'total_kegiatan' => Kegiatan::where('organisasi_id', $organisasiId)->count(),
            'kegiatan_disetujui_admin' => Kegiatan::where('organisasi_id', $organisasiId)->where('status', 'disetujui admin')->count(),
            'kegiatan_disetujui_pembina' => Kegiatan::where('organisasi_id', $organisasiId)->where('status', 'disetujui pembina')->count(),
            'kegiatan_pending' => Kegiatan::where('organisasi_id', $organisasiId)->where('status', 'pending')->count(),
            'kegiatan_ditolak' => Kegiatan::where('organisasi_id', $organisasiId)->whereIn('status', ['ditolak admin', 'ditolak pembina'])->count(),
            'total_dokumentasi' => Kegiatan::where('organisasi_id', $organisasiId)->where('status', 'pending')->count(),
            'total_dokumentasi_items' => Dokumentasi::whereHas('kegiatan', function ($q) use ($organisasiId) {
                $q->where('organisasi_id', $organisasiId);
            })->count(),
        ];

        $kegiatanTerbaru = Kegiatan::where('organisasi_id', $organisasiId)
            ->with('organisasi:id,nama_organisasi')
            ->latest('created_at')
            ->take(10)
            ->get(['id', 'nama_kegiatan', 'status', 'tanggal_mulai', 'organisasi_id', 'created_at']);

        $dokumen = Dokumentasi::whereHas('kegiatan', function ($q) use ($organisasiId) {
            $q->where('organisasi_id', $organisasiId);
        })
            ->with('kegiatan')
            ->latest('created_at')
            ->get()
            ->map(function ($dok) {
                $filePath = storage_path('app/' . $dok->file_dokumentasi);
                return (object) [
                    'id' => 'dok_' . $dok->id,
                    'nama_file' => basename($dok->file_dokumentasi ?? ''),
                    'ukuran' => file_exists($filePath) ? filesize($filePath) : 0,
                    'created_at' => $dok->created_at,
                    'kegiatan' => $dok->kegiatan,
                    'type' => 'dokumentasi',
                ];
            });

        $laporan = LaporanKegiatan::whereHas('kegiatan', function ($q) use ($organisasiId) {
            $q->where('organisasi_id', $organisasiId);
        })
            ->with('kegiatan')
            ->latest('created_at')
            ->get()
            ->map(function ($laporan) {
                $filePath = storage_path('app/' . $laporan->file_laporan);
                return (object) [
                    'id' => 'laporan_' . $laporan->id,
                    'nama_file' => basename($laporan->file_laporan ?? ''),
                    'ukuran' => file_exists($filePath) ? filesize($filePath) : 0,
                    'created_at' => $laporan->created_at,
                    'kegiatan' => $laporan->kegiatan,
                    'type' => 'laporan',
                ];
            });

        // Gabungkan dokumentasi dan laporan, sort by created_at, ambil 3 terbaru
        $dokumentasiTerbaru = $dokumen->concat($laporan)
            ->sortByDesc('created_at')
            ->take(3)
            ->values();

        // Jadwal terdekat (kegiatan dengan tanggal mulai mendatang)
        $jadwalMendatang = Kegiatan::where('organisasi_id', $organisasiId)
            ->where('tanggal_mulai', '>=', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get(['id', 'nama_kegiatan', 'tanggal_mulai', 'tempat']);

        // Data pengajuan kegiatan per bulan (12 bulan terakhir)
        $kegiatanPerBulan = Kegiatan::where('organisasi_id', $organisasiId)
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as bulan, COUNT(*) as jumlah')
            ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m")')
            ->orderBy('bulan')
            ->get()
            ->pluck('jumlah', 'bulan')
            ->toArray();

        // Siapkan data chart untuk 12 bulan
        $chartLabels = [];
        $chartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan = now()->subMonths($i)->format('Y-m');
            $chartLabels[] = now()->subMonths($i)->format('M Y');
            $chartData[] = $kegiatanPerBulan[$bulan] ?? 0;
        }

        return view('ketua.dashboard', compact('stats', 'kegiatanTerbaru', 'dokumentasiTerbaru', 'jadwalMendatang', 'chartLabels', 'chartData'));
    }
}