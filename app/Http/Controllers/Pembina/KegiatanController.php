<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\View\View;

class KegiatanController extends Controller
{
    public function __invoke(): View
    {
        $kegiatan = Kegiatan::with('organisasi:id,nama_organisasi')
            ->whereHas('organisasi', function ($query) {
                $query->where('pembina_id', auth()->id());
            })
            ->latest('id')
            ->get([
                'id',
                'organisasi_id',
                'nama_kegiatan',
                'deskripsi',
                'tanggal_mulai',
                'tempat',
                'proposal',
                'status',
                'keterangan',
            ]);

        return view('pembina.kegiatan', compact('kegiatan'));
    }
}
