<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\View\View;

use Illuminate\Http\Request;
class KegiatanController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = $request->query('search', '');

        $query = Kegiatan::with('organisasi:id,nama_organisasi')
            ->whereHas('organisasi', function ($query) {
                $query->where('pembina_id', auth()->id());
            });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kegiatan', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('tempat', 'like', "%{$search}%");
            });
        }

        $kegiatan = $query->latest('id')->get([
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

        return view('pembina.kegiatan', compact('kegiatan', 'search'));
    }
}
