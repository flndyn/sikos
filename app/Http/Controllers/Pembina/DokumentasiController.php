<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DokumentasiController extends Controller
{
    public function __invoke(Request $request): View
    {
        $filterNamaKegiatan = trim((string) $request->query('nama_kegiatan', ''));

        $dokumentasi = Dokumentasi::with([
            'kegiatan:id,organisasi_id,nama_kegiatan',
            'kegiatan.organisasi:id,nama_organisasi',
        ])
            ->whereHas('kegiatan.organisasi', function ($query) {
                $query->where('pembina_id', auth()->id());
            })
            ->when($filterNamaKegiatan !== '', function ($query) use ($filterNamaKegiatan) {
                $query->whereHas('kegiatan', function ($kegiatanQuery) use ($filterNamaKegiatan) {
                    $kegiatanQuery->where('nama_kegiatan', 'like', '%' . $filterNamaKegiatan . '%');
                });
            })
            ->latest('created_at')
            ->get([
                'id',
                'kegiatan_id',
                'file_dokumentasi',
                'keterangan',
                'created_at',
            ]);

        $kegiatanList = Kegiatan::whereHas('organisasi', function ($query) {
            $query->where('pembina_id', auth()->id());
        })
            ->orderBy('nama_kegiatan')
            ->get(['id', 'nama_kegiatan']);

        return view('pembina.dokumentasi', compact('dokumentasi', 'kegiatanList', 'filterNamaKegiatan'));
    }
}
