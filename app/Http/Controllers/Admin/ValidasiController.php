<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ValidasiController extends Controller
{
    public function __invoke(): View
    {
        $kegiatanMenungguValidasiAdmin = Kegiatan::with('organisasi:id,nama_organisasi')
            ->where('status', 'disetujui pembina')
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

        return view('admin.validasi', compact('kegiatanMenungguValidasiAdmin'));
    }

    public function approve(Kegiatan $kegiatan): RedirectResponse
    {
        if ($kegiatan->status !== 'disetujui pembina') {
            return redirect()
                ->route('admin.validasi')
                ->with('error', 'Kegiatan hanya bisa divalidasi admin setelah disetujui pembina.');
        }

        $kegiatan->update([
            'status' => 'disetujui admin',
            'keterangan' => null,
        ]);

        return redirect()
            ->route('admin.validasi')
            ->with('success', 'Kegiatan "' . $kegiatan->nama_kegiatan . '" telah disetujui admin.');
    }

    public function reject(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        if ($kegiatan->status !== 'disetujui pembina') {
            return redirect()
                ->route('admin.validasi')
                ->with('error', 'Kegiatan hanya bisa divalidasi admin setelah disetujui pembina.');
        }

        $validated = $request->validate([
            'keterangan' => ['required', 'string'],
        ]);

        $kegiatan->update([
            'status' => 'ditolak admin',
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()
            ->route('admin.validasi')
            ->with('success', 'Kegiatan "' . $kegiatan->nama_kegiatan . '" telah ditolak admin.');
    }
}
