<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ValidasiController extends Controller
{
    public function __invoke(): View
    {
        $kegiatanMenungguValidasiPembina = Kegiatan::with('organisasi:id,nama_organisasi')
            ->where('status', 'pending')
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
                'proposal',
                'status',
            ]);

        return view('pembina.validasi', compact('kegiatanMenungguValidasiPembina'));
    }

    public function approve(Kegiatan $kegiatan): RedirectResponse
    {
        if (! $this->canValidate($kegiatan)) {
            return redirect()->route('pembina.validasi')->with('error', 'Kegiatan tidak ditemukan atau bukan milik organisasi binaan Anda.');
        }

        if ($kegiatan->status !== 'pending') {
            return redirect()->route('pembina.validasi')->with('error', 'Kegiatan sudah divalidasi sebelumnya.');
        }

        $kegiatan->update([
            'status' => 'disetujui pembina',
            'keterangan' => null,
        ]);

        return redirect()->route('pembina.validasi')->with('success', 'Kegiatan "' . $kegiatan->nama_kegiatan . '" telah disetujui pembina.');
    }

    public function reject(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        if (! $this->canValidate($kegiatan)) {
            return redirect()->route('pembina.validasi')->with('error', 'Kegiatan tidak ditemukan atau bukan milik organisasi binaan Anda.');
        }

        if ($kegiatan->status !== 'pending') {
            return redirect()->route('pembina.validasi')->with('error', 'Kegiatan sudah divalidasi sebelumnya.');
        }

        $validated = $request->validate([
            'keterangan' => ['required', 'string'],
        ]);

        $kegiatan->update([
            'status' => 'ditolak pembina',
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()->route('pembina.validasi')->with('success', 'Kegiatan "' . $kegiatan->nama_kegiatan . '" telah ditolak pembina.');
    }

    private function canValidate(Kegiatan $kegiatan): bool
    {
        return $kegiatan->organisasi()
            ->where('pembina_id', auth()->id())
            ->exists();
    }
}
