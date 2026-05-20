<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KegiatanController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $query = Kegiatan::with([
            'organisasi:id,nama_organisasi',
            'penanggungJawab:id,name',
        ])
        ->whereHas('organisasi.pembinaUsers', function ($query) {
            $query->where('users.id', auth()->id());
        });

        // SEARCH
        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $q->where('nama_kegiatan', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('tempat', 'like', "%{$search}%")
                    ->orWhereHas('organisasi', function ($organisasiQuery) use ($search) {

                        $organisasiQuery->where(
                            'nama_organisasi',
                            'like',
                            "%{$search}%"
                        );
                    });
            });
        }

        $kegiatan = $query
            ->latest('id')
            ->get([
                'id',
                'organisasi_id',
                'nama_kegiatan',
                'deskripsi',
                'tanggal_mulai',
                'tempat',
                'penanggung_jawab',
                'tanggal_berakhir',
                'proposal',
                'status',
            ]);

        return view('pembina.kegiatan', compact(
            'kegiatan',
            'search'
        ));
    }
}
