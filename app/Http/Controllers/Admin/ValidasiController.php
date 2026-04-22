<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Notifications\KegiatanWorkflowNotification;
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

        $this->notifyKetuaSetelahDisetujuiAdmin($kegiatan->fresh());

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
            'keterangan' => [
                'required',
                'string',
                'in:Jadwal bentrok dengan kegiatan lain,Melanggar kebijakan sekolah',
            ],
        ]);

        $kegiatan->update([
            'status' => 'ditolak admin',
            'keterangan' => $validated['keterangan'],
        ]);

        $this->notifyKetuaDanPembinaSetelahDitolakAdmin($kegiatan->fresh());

        return redirect()
            ->route('admin.validasi')
            ->with('success', 'Kegiatan "' . $kegiatan->nama_kegiatan . '" telah ditolak admin.');
    }

    private function notifyKetuaSetelahDisetujuiAdmin(Kegiatan $kegiatan): void
    {
        $organisasi = $kegiatan->organisasi()->with('ketua')->first();
        $ketua = $organisasi?->ketua;

        if (! $ketua) {
            return;
        }

        $ketua->notify(new KegiatanWorkflowNotification(
            'Proposal Disetujui Admin',
            'Proposal kegiatan "' . $kegiatan->nama_kegiatan . '" telah disetujui admin.',
            route('ketua.kegiatan'),
            'Lihat kegiatan'
        ));
    }

    private function notifyKetuaDanPembinaSetelahDitolakAdmin(Kegiatan $kegiatan): void
    {
        $organisasi = $kegiatan->organisasi()->with(['ketua', 'pembina'])->first();
        $ketua = $organisasi?->ketua;
        $pembina = $organisasi?->pembina;
        $keterangan = $kegiatan->keterangan ?: '-';

        if ($ketua) {
            $ketua->notify(new KegiatanWorkflowNotification(
                'Proposal Ditolak Admin',
                'Proposal kegiatan "' . $kegiatan->nama_kegiatan . '" ditolak admin. Keterangan: ' . $keterangan,
                route('ketua.kegiatan'),
                'Lihat kegiatan'
            ));
        }

        if ($pembina) {
            $pembina->notify(new KegiatanWorkflowNotification(
                'Proposal Binaan Ditolak Admin',
                'Proposal kegiatan "' . $kegiatan->nama_kegiatan . '" ditolak admin. Keterangan: ' . $keterangan,
                route('pembina.validasi'),
                'Lihat validasi'
            ));
        }
    }
}
