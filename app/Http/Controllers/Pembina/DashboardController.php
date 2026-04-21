<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Kegiatan;
use App\Models\Organisasi;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $userId = auth()->id();

        $organisasiIds = Organisasi::where('pembina_id', $userId)
            ->pluck('id');

        $stats = [
            'total_organisasi' => $organisasiIds->count(),
            'total_kegiatan' => Kegiatan::whereIn('organisasi_id', $organisasiIds)->count(),
            'kegiatan_pending' => Kegiatan::whereIn('organisasi_id', $organisasiIds)->where('status', 'pending')->count(),
            'kegiatan_disetujui_pembina' => Kegiatan::whereIn('organisasi_id', $organisasiIds)
                ->where('status', 'disetujui pembina')
                ->count(),
            'kegiatan_disetujui_admin' => Kegiatan::whereIn('organisasi_id', $organisasiIds)
                ->where('status', 'disetujui admin')
                ->count(),
            'total_dokumentasi' => Dokumentasi::whereHas('kegiatan', function ($query) use ($organisasiIds) {
                $query->whereIn('organisasi_id', $organisasiIds);
            })->count(),
        ];

        $kegiatanTerbaru = Kegiatan::with('organisasi:id,nama_organisasi')
            ->whereIn('organisasi_id', $organisasiIds)
            ->latest('created_at')
            ->take(10)
            ->get(['id', 'organisasi_id', 'nama_kegiatan', 'status', 'created_at']);

        $dokumentasiTerbaru = Dokumentasi::whereHas('kegiatan', function ($query) use ($organisasiIds) {
            $query->whereIn('organisasi_id', $organisasiIds);
        })
            ->with('kegiatan:id,nama_kegiatan')
            ->latest('created_at')
            ->first();

        // Data pengajuan kegiatan per bulan (12 bulan terakhir)
        $kegiatanPerBulan = Kegiatan::whereIn('organisasi_id', $organisasiIds)
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

        return view('pembina.dashboard', compact('stats', 'kegiatanTerbaru', 'dokumentasiTerbaru', 'chartLabels', 'chartData'));
    }
}