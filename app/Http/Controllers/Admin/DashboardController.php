<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Kegiatan;
use App\Models\LaporanKegiatan;
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
            'kegiatan_disetujui' => Kegiatan::whereIn('status', ['disetujui admin', 'disetujui pembina'])->count(),
            'kegiatan_ditolak' => Kegiatan::whereIn('status', ['ditolak admin', 'ditolak pembina'])->count(),
            'kegiatan_pending' => Kegiatan::where('status', 'pending')->count(),
            'kegiatan_butuh_validasi' => Kegiatan::where('status', 'disetujui pembina')->count(),
            'total_dokumentasi' => Dokumentasi::count(),
        ];

        $kegiatanTerbaru = Kegiatan::with('organisasi:id,nama_organisasi')
            ->where('status', 'disetujui admin')
            ->latest('created_at')
            ->take(10)
            ->get(['id', 'organisasi_id', 'nama_kegiatan', 'status', 'tanggal_mulai', 'created_at']);

        $pengajuanTerbaru = Kegiatan::with('organisasi:id,nama_organisasi')
            ->where('status', 'disetujui pembina')
            ->latest('created_at')
            ->take(10)
            ->get(['id', 'organisasi_id', 'nama_kegiatan', 'status', 'tanggal_mulai', 'created_at']);

        // Data chart: organisasi paling aktif berdasarkan jumlah laporan kegiatan
        $laporanPerOrganisasi = LaporanKegiatan::join('kegiatan', 'laporan_kegiatan.kegiatan_id', '=', 'kegiatan.id')
            ->join('organisasi', 'kegiatan.organisasi_id', '=', 'organisasi.id')
            ->selectRaw('organisasi.id as organisasi_id, organisasi.nama_organisasi, COUNT(laporan_kegiatan.id) as jumlah')
            ->whereNotNull('kegiatan.organisasi_id')
            ->groupBy('organisasi.id', 'organisasi.nama_organisasi')
            ->orderByDesc('jumlah')
            ->take(10)
            ->get();

        $chartLabels = $laporanPerOrganisasi
            ->map(fn ($item) => $item->nama_organisasi ?? 'Tidak Diketahui')
            ->values()
            ->all();

        $chartData = $laporanPerOrganisasi
            ->map(fn ($item) => (int) $item->jumlah)
            ->values()
            ->all();

        return view('admin.dashboard', compact('stats', 'kegiatanTerbaru', 'pengajuanTerbaru', 'chartLabels', 'chartData'));
    }
}
