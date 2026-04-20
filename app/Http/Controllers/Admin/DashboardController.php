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

        return view('admin.dashboard', compact('stats', 'kegiatanTerbaru'));
    }
}