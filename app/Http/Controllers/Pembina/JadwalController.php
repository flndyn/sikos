<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\View\View;

class JadwalController extends Controller
{
    public function __invoke(): View
    {
        $jadwalKegiatan = Kegiatan::with('organisasi:id,nama_organisasi')
            ->whereHas('organisasi.pembinaUsers', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->whereIn('status', ['disetujui pembina', 'disetujui admin'])
            ->orderBy('tanggal_mulai')
            ->get([
                'id',
                'organisasi_id',
                'nama_kegiatan',
                'tanggal_mulai',
                'tempat',
                'status',
            ]);

        return view('pembina.jadwal', compact('jadwalKegiatan'));
    }
}
