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
    public function __invoke(Request $request): View
    {
        $search = $request->query('search', '');

        $query = Kegiatan::with('organisasi:id,nama_organisasi')
            ->where('status', 'disetujui pembina');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kegiatan', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $kegiatanMenungguValidasiAdmin = $query->latest('id')->get([
            'id',
            'organisasi_id',
            'nama_kegiatan',
            'deskripsi',
            'tanggal_mulai',
            'proposal',
            'status',
        ]);

        return view('admin.validasi', compact('kegiatanMenungguValidasiAdmin', 'search'));
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
                'nullable',
                'string',
                'in:Jadwal bentrok dengan kegiatan lain,Melanggar kebijakan sekolah',
            ],
            'keterangan_custom' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        $keterangan = trim($validated['keterangan_custom'] ?? '') ?: ($validated['keterangan'] ?? null);

        $kegiatan->update([
            'status' => 'ditolak admin',
            'keterangan' => $keterangan,
        ]);

        $this->notifyKetuaDanPembinaSetelahDitolakAdmin($kegiatan->fresh());

        return redirect()
            ->route('admin.validasi')
            ->with('success', 'Kegiatan "' . $kegiatan->nama_kegiatan . '" telah ditolak admin.');
    }

    private function notifyKetuaSetelahDisetujuiAdmin(Kegiatan $kegiatan): void
    {
        $organisasi = $kegiatan->organisasi()->with('ketuaUsers')->first();
        $ketuaUsers = $organisasi?->ketuaUsers;

        if (! $ketuaUsers || $ketuaUsers->isEmpty()) {
            return;
        }

        foreach ($ketuaUsers as $ketua) {
            $ketua->notify(new KegiatanWorkflowNotification(
                'Proposal Disetujui Admin',
                'Proposal kegiatan "' . $kegiatan->nama_kegiatan . '" telah disetujui admin.',
                route('ketua.kegiatan'),
                'Lihat kegiatan'
            ));
        }
    }

    private function notifyKetuaDanPembinaSetelahDitolakAdmin(Kegiatan $kegiatan): void
    {
        $organisasi = $kegiatan->organisasi()->with(['ketuaUsers', 'pembinaUsers'])->first();
        $keterangan = $kegiatan->keterangan ?: '-';

        foreach ($organisasi?->ketuaUsers ?? [] as $ketua) {
            $ketua->notify(new KegiatanWorkflowNotification(
                'Proposal Ditolak Admin',
                'Proposal kegiatan "' . $kegiatan->nama_kegiatan . '" ditolak admin. Keterangan: ' . $keterangan,
                route('ketua.kegiatan'),
                'Lihat kegiatan'
            ));
        }

        foreach ($organisasi?->pembinaUsers ?? [] as $pembina) {
            $pembina->notify(new KegiatanWorkflowNotification(
                'Proposal Binaan Ditolak Admin',
                'Proposal kegiatan "' . $kegiatan->nama_kegiatan . '" ditolak admin. Keterangan: ' . $keterangan,
                route('pembina.validasi'),
                'Lihat validasi'
            ));
        }
    }
}
