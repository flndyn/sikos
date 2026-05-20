<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\User;
use App\Notifications\KegiatanWorkflowNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ValidasiController extends Controller
{
    public function __invoke(): View
    {
        $kegiatanMenungguValidasiPembina = Kegiatan::with('organisasi:id,nama_organisasi')
            ->where('status', 'pending')
            ->whereHas('organisasi.pembinaUsers', function ($query) {
                $query->where('users.id', auth()->id());
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

        $this->notifyAdminSetelahDisetujuiPembina($kegiatan->fresh());

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
            'keterangan' => [
                'nullable',
                'string',
                'in:Proposal belum lengkap,Anggaran tidak sesuai,Deskripsi atau data kegiatan kurang jelas',
            ],
            'keterangan_custom' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        $keterangan = trim($validated['keterangan_custom'] ?? '') ?: ($validated['keterangan'] ?? null);

        $kegiatan->update([
            'status' => 'ditolak pembina',
            'keterangan' => $keterangan,
        ]);

        $this->notifyKetuaSetelahDitolakPembina($kegiatan->fresh());

        return redirect()->route('pembina.validasi')->with('success', 'Kegiatan "' . $kegiatan->nama_kegiatan . '" telah ditolak pembina.');
    }

    private function canValidate(Kegiatan $kegiatan): bool
    {
        return $kegiatan->organisasi()
            ->whereHas('pembinaUsers', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->exists();
    }

    private function notifyAdminSetelahDisetujuiPembina(Kegiatan $kegiatan): void
    {
        $organisasi = $kegiatan->organisasi()->first();

        $admins = User::query()
            ->where('role', 'admin')
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new KegiatanWorkflowNotification(
                'Validasi Admin Dibutuhkan',
                'Kegiatan "' . $kegiatan->nama_kegiatan . '" dari ' . ($organisasi?->nama_organisasi ?? 'organisasi') . ' telah disetujui pembina dan menunggu persetujuan admin.',
                route('admin.validasi'),
                'Buka validasi admin'
            ));
        }
    }

    private function notifyKetuaSetelahDitolakPembina(Kegiatan $kegiatan): void
    {
        $organisasi = $kegiatan->organisasi()->first();
        $ketuaUsers = $organisasi?->ketuaUsers;

        if (! $ketuaUsers || $ketuaUsers->isEmpty()) {
            return;
        }

        $keterangan = $kegiatan->keterangan ?: '-';

        foreach ($ketuaUsers as $ketua) {
            $ketua->notify(new KegiatanWorkflowNotification(
                'Proposal Ditolak Pembina',
                'Proposal kegiatan "' . $kegiatan->nama_kegiatan . '" ditolak pembina. Keterangan: ' . $keterangan,
                route('ketua.kegiatan'),
                'Lihat kegiatan'
            ));
        }
    }
}
