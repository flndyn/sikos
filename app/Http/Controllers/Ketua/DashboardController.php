<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Dokumentasi;
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
            'total_dokumentasi' => Dokumentasi::whereHas('kegiatan', function ($q) use ($organisasiId) {
                $q->where('organisasi_id', $organisasiId);
            })->count(),
        ];

        $kegiatanTerbaru = Kegiatan::where('organisasi_id', $organisasiId)
            ->latest('created_at')
            ->take(10)
            ->get(['id', 'nama_kegiatan', 'status', 'created_at']);

        $dokumentasiTerbaru = Dokumentasi::whereHas('kegiatan', function ($q) use ($organisasiId) {
            $q->where('organisasi_id', $organisasiId);
        })
            ->with('kegiatan')
            ->latest('created_at')
            ->first();

        return view('ketua.dashboard', compact('stats', 'kegiatanTerbaru', 'dokumentasiTerbaru'));
    }
}
