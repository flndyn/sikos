<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Notifications\KegiatanWorkflowNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class KegiatanController extends Controller
{
    private const PROPOSAL_DIRECTORY = 'proposal-kegiatan';

    public function __invoke(): View
    {
        $user = auth()->user();

        $organisasi = $user->organisasiSebagaiKetua()->first();
        $organisasiId = $organisasi?->id;

        $kegiatan = Kegiatan::where('organisasi_id', $organisasiId)
            ->latest('id')
            ->get([
                'id',
                'nama_kegiatan',
                'deskripsi',
                'tanggal_mulai',
                'tempat',
                'proposal',
                'status',
                'keterangan',
            ]);

        return view('ketua.kegiatan', compact('kegiatan'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $organisasi = $user->organisasiSebagaiKetua()->first();
        $organisasiId = $organisasi?->id;

        if (!$organisasiId) {
            return redirect()->route('ketua.kegiatan')->with('error', 'Anda belum menjadi ketua di organisasi manapun');
        }

        $validated = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal_mulai' => ['required', 'date'],
            'tempat' => ['required', 'string', 'max:150'],
            'proposal' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        if ($request->hasFile('proposal')) {
            $file = $request->file('proposal');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['proposal'] = $file->storeAs(self::PROPOSAL_DIRECTORY, $filename, 'public');
        }

        $validated['organisasi_id'] = $organisasiId;
        $validated['status'] = 'pending';

        $kegiatan = Kegiatan::create($validated);

        $this->notifyPembinaProposalDiajukan($kegiatan);

        return redirect()->route('ketua.kegiatan')->with('success', 'Kegiatan berhasil diajukan');
    }

    public function update(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiKetua()->first();
        $organisasiId = $organisasi?->id;

        if ($kegiatan->organisasi_id !== $organisasiId) {
            return redirect()->route('ketua.kegiatan')->with('error', 'Anda tidak punya akses untuk mengubah kegiatan ini.');
        }

        $validated = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal_mulai' => ['required', 'date'],
            'tempat' => ['required', 'string', 'max:150'],
            'proposal' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        if ($request->hasFile('proposal')) {
            if ($kegiatan->proposal && ! str_starts_with($kegiatan->proposal, 'http')) {
                Storage::disk('public')->delete($kegiatan->proposal);
            }

            $file = $request->file('proposal');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['proposal'] = $file->storeAs(self::PROPOSAL_DIRECTORY, $filename, 'public');
        } else {
            unset($validated['proposal']);
        }

        // Jika sebelumnya ditolak, saat ketua mengedit status kembali ke proses review.
        $perluDikirimUlangKePembina = in_array($kegiatan->status, ['ditolak admin', 'ditolak pembina'], true);

        if ($perluDikirimUlangKePembina) {
            $validated['status'] = 'pending';
            $validated['keterangan'] = null;
        }

        $kegiatan->update($validated);

        if ($perluDikirimUlangKePembina) {
            $this->notifyPembinaProposalDiajukan($kegiatan->fresh());
        }

        return redirect()->route('ketua.kegiatan')->with('success', 'Kegiatan berhasil diperbarui');
    }

    public function destroy(Kegiatan $kegiatan): RedirectResponse
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiKetua()->first();
        $organisasiId = $organisasi?->id;

        if ($kegiatan->organisasi_id !== $organisasiId) {
            return redirect()->route('ketua.kegiatan')->with('error', 'Anda tidak punya akses untuk menghapus kegiatan ini.');
        }

        if ($kegiatan->proposal && ! str_starts_with($kegiatan->proposal, 'http')) {
            Storage::disk('public')->delete($kegiatan->proposal);
        }

        $kegiatan->delete();

        return redirect()->route('ketua.kegiatan')->with('success', 'Kegiatan berhasil dihapus');
    }

    private function notifyPembinaProposalDiajukan(Kegiatan $kegiatan): void
    {
        $organisasi = $kegiatan->organisasi()->with('pembina')->first();
        $pembina = $organisasi?->pembina;

        if (! $pembina) {
            return;
        }

        $pembina->notify(new KegiatanWorkflowNotification(
            'Pengajuan Proposal Baru',
            'Proposal kegiatan "' . $kegiatan->nama_kegiatan . '" dari ' . ($organisasi?->nama_organisasi ?? 'organisasi') . ' menunggu validasi Anda.',
            route('pembina.validasi'),
            'Buka validasi'
        ));
    }
}
