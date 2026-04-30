<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $kegiatanTerbaru = Kegiatan::with('organisasi:id,nama_organisasi')
            ->latest('created_at')
            ->take(5)
            ->get(['id', 'organisasi_id', 'nama_kegiatan', 'status', 'tanggal_mulai', 'created_at']);

        return view('welcome', compact('kegiatanTerbaru'));
    }
}