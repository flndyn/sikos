<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use Illuminate\View\View;

class DokumentasiController extends Controller
{
    public function __invoke(): View
    {
        $dokumentasi = Dokumentasi::with([
            'kegiatan:id,organisasi_id,nama_kegiatan',
            'kegiatan.organisasi:id,nama_organisasi',
        ])
            ->whereHas('kegiatan.organisasi', function ($query) {
                $query->where('pembina_id', auth()->id());
            })
            ->latest('created_at')
            ->get([
                'id',
                'kegiatan_id',
                'file_dokumentasi',
                'keterangan',
                'created_at',
            ]);

        return view('pembina.dokumentasi', compact('dokumentasi'));
    }
}
