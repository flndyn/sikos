<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiKetua()->first();
        $organisasiId = $organisasi?->id;

        $jadwalKegiatan = Kegiatan::where('organisasi_id', $organisasiId)
            ->whereIn('status', ['disetujui admin'])
            ->orderBy('tanggal_mulai')
            ->get([
                'id',
                'nama_kegiatan',
                'tanggal_mulai',
                'tempat',
                'status',
            ]);

        return view('ketua.jadwal', compact('jadwalKegiatan'));
    }
}
