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

        $organisasiIds = Organisasi::whereHas('pembinaUsers', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->pluck('id');

        $stats = [
            'total_organisasi' => $organisasiIds->count(),
            'total_kegiatan' => Kegiatan::whereIn('organisasi_id', $organisasiIds)->count(),
            'kegiatan_pending' => Kegiatan::whereIn('organisasi_id', $organisasiIds)
                ->where('status', 'pending')
                ->count(),
            'kegiatan_disetujui_pembina' => Kegiatan::whereIn('organisasi_id', $organisasiIds)
                ->where('status', 'disetujui pembina')
                ->count(),
            'kegiatan_disetujui_admin' => Kegiatan::whereIn('organisasi_id', $organisasiIds)
                ->where('status', 'disetujui admin')
                ->count(),
            'kegiatan_ditolak' => Kegiatan::whereIn('organisasi_id', $organisasiIds)
                ->whereIn('status', ['ditolak pembina', 'ditolak admin'])
                ->count(),
            'total_dokumentasi' => Dokumentasi::whereHas('kegiatan', function ($query) use ($organisasiIds) {
                $query->whereIn('organisasi_id', $organisasiIds);
            })->count(),
        ];

        $kegiatanTerbaru = Kegiatan::with('organisasi:id,nama_organisasi')
            ->whereIn('organisasi_id', $organisasiIds)
            ->latest('created_at')
            ->take(5)
            ->get(['id', 'organisasi_id', 'nama_kegiatan', 'status', 'tanggal_mulai', 'created_at']);

        $kegiatanPending = Kegiatan::with('organisasi:id,nama_organisasi')
            ->whereIn('organisasi_id', $organisasiIds)
            ->where('status', 'pending')
            ->latest('created_at')
            ->take(5)
            ->get(['id', 'organisasi_id', 'nama_kegiatan', 'status', 'tanggal_mulai', 'created_at']);

        $jadwalMendatang = Kegiatan::with('organisasi:id,nama_organisasi')
            ->whereIn('organisasi_id', $organisasiIds)
            ->whereIn('status', ['disetujui pembina', 'disetujui admin'])
            ->where('tanggal_mulai', '>=', now()->startOfDay())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(5)
            ->get(['id', 'organisasi_id', 'nama_kegiatan', 'status', 'tanggal_mulai', 'tempat']);

        $dokumentasiTerbaru = Dokumentasi::whereHas('kegiatan', function ($query) use ($organisasiIds) {
            $query->whereIn('organisasi_id', $organisasiIds);
        })
            ->with('kegiatan:id,nama_kegiatan')
            ->latest('created_at')
            ->first();

        $kegiatanPerBulan = Kegiatan::whereIn('organisasi_id', $organisasiIds)
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as bulan, COUNT(*) as jumlah')
            ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m")')
            ->orderBy('bulan')
            ->get()
            ->pluck('jumlah', 'bulan')
            ->toArray();

        $chartLabels = [];
        $chartData   = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan         = now()->subMonths($i)->format('Y-m');
            $chartLabels[] = now()->subMonths($i)->format('M Y');
            $chartData[]   = $kegiatanPerBulan[$bulan] ?? 0;
        }

        return view('pembina.dashboard', compact(
            'stats',
            'kegiatanTerbaru',
            'kegiatanPending',
            'jadwalMendatang',
            'dokumentasiTerbaru',
            'chartLabels',
            'chartData',
        ));
    }
}
