<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ValidasiController extends Controller
{
    public function __invoke(): View
    {
        $kegiatanPending = Kegiatan::with('organisasi:id,nama_organisasi')
            ->where('status', 'pending')
            ->latest('id')
            ->get([
                'id',
                'organisasi_id',
                'nama_kegiatan',
                'deskripsi',
                'tanggal_mulai',
                'proposal',
                'status',
            ]);

        return view('admin.validasi', compact('kegiatanPending'));
    }

    public function approve(Kegiatan $kegiatan): RedirectResponse
    {
        if ($kegiatan->status !== 'pending') {
            return redirect()
                ->route('admin.validasi')
                ->with('error', 'Kegiatan ini tidak dalam status pending.');
        }

        $kegiatan->update(['status' => 'disetujui']);

        return redirect()
            ->route('admin.validasi')
            ->with('success', 'Kegiatan "' . $kegiatan->nama_kegiatan . '" telah disetujui.');
    }

    public function reject(Kegiatan $kegiatan): RedirectResponse
    {
        if ($kegiatan->status !== 'pending') {
            return redirect()
                ->route('admin.validasi')
                ->with('error', 'Kegiatan ini tidak dalam status pending.');
        }

        $kegiatan->update(['status' => 'ditolak']);

        return redirect()
            ->route('admin.validasi')
            ->with('success', 'Kegiatan "' . $kegiatan->nama_kegiatan . '" telah ditolak.');
    }
}
