<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Kegiatan;
use App\Models\Organisasi;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'total_pengguna' => User::count(),
            'total_organisasi' => Organisasi::count(),
            'total_kegiatan' => Kegiatan::count(),
            'kegiatan_disetujui' => Kegiatan::where('status', 'disetujui admin')->count(),
            'kegiatan_ditolak' => Kegiatan::where('status', 'ditolak admin')->count(),
            'total_dokumentasi' => Dokumentasi::count(),
        ];

        $kegiatanTerbaru = Kegiatan::with('organisasi:id,nama_organisasi')
            ->latest('created_at')
            ->take(10)
            ->get(['id', 'organisasi_id', 'nama_kegiatan', 'status', 'created_at']);

        // Data pengajuan kegiatan per bulan (12 bulan terakhir)
        $kegiatanPerBulan = Kegiatan::where('created_at', '>=', now()->subMonths(11)->startOfMonth())
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

        return view('admin.dashboard', compact('stats', 'kegiatanTerbaru', 'chartLabels', 'chartData'));
    }
}