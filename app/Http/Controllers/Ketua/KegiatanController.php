<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Notifications\KegiatanWorkflowNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KegiatanController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = auth()->user();

        $organisasi = $user->organisasiSebagaiKetua()->with('pembina')->first();
        $organisasiId = $organisasi?->id;

        $search = $request->query('search', '');

        $query = Kegiatan::where('organisasi_id', $organisasiId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kegiatan', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('tempat', 'like', "%{$search}%");
            });
        }

        $kegiatan = $query->with('penanggungJawab:id,name')->latest('id')->get([
            'id',
            'nama_kegiatan',
            'deskripsi',
            'tanggal_mulai',
            'tempat',
            'penanggung_jawab',
            'tanggal_berakhir',
            'proposal',
            'status',
        ]);

        $pembina = $organisasi?->pembina;

        return view('ketua.kegiatan', compact('kegiatan', 'search', 'pembina'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $organisasi = $user->organisasiSebagaiKetua()->first();
        $organisasiId = $organisasi?->id;

        if (!$organisasiId) {
            return redirect()->route('ketua.kegiatan')
                ->with('error', 'Anda belum menjadi ketua di organisasi manapun');
        }

        $validated = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_berakhir' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'penanggung_jawab' => ['nullable', 'integer', 'exists:users,id'],
            'tempat' => ['required', 'string', 'max:150'],
            'proposal' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        // upload proposal
        if ($request->hasFile('proposal')) {

            // folder berdasarkan nama organisasi
            $folder = 'proposal/' . Str::slug($organisasi->nama_organisasi);

            $file = $request->file('proposal');

            // nama file unik
            $filename = time() . '_' . $file->getClientOriginalName();

            // simpan file
            $validated['proposal'] = $file->storeAs(
                $folder,
                $filename,
                'public'
            );
        }

        $validated['organisasi_id'] = $organisasiId;
        $validated['status'] = 'pending';

        $kegiatan = Kegiatan::create($validated);

        $this->notifyPembinaProposalDiajukan($kegiatan);

        return redirect()->route('ketua.kegiatan')
            ->with('success', 'Kegiatan berhasil diajukan');
    }

    public function update(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        $user = auth()->user();

        $organisasi = $user->organisasiSebagaiKetua()->first();
        $organisasiId = $organisasi?->id;

        if ($kegiatan->organisasi_id !== $organisasiId) {
            return redirect()->route('ketua.kegiatan')
                ->with('error', 'Anda tidak punya akses untuk mengubah kegiatan ini.');
        }

        $validated = $request->validate([
            'nama_kegiatan' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_berakhir' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'penanggung_jawab' => ['nullable', 'integer', 'exists:users,id'],
            'tempat' => ['required', 'string', 'max:150'],
            'proposal' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        // upload proposal baru
        if ($request->hasFile('proposal')) {

            // hapus file lama
            if ($kegiatan->proposal && !str_starts_with($kegiatan->proposal, 'http')) {
                Storage::disk('public')->delete($kegiatan->proposal);
            }

            // folder berdasarkan nama organisasi
            $folder = 'proposal/' . Str::slug($organisasi->nama_organisasi);

            $file = $request->file('proposal');

            // nama file unik
            $filename = time() . '_' . $file->getClientOriginalName();

            // simpan file baru
            $validated['proposal'] = $file->storeAs(
                $folder,
                $filename,
                'public'
            );

        } else {
            unset($validated['proposal']);
        }

        // jika sebelumnya ditolak
        $perluDikirimUlangKePembina = in_array(
            $kegiatan->status,
            ['ditolak admin', 'ditolak pembina'],
            true
        );

        if ($perluDikirimUlangKePembina) {
            $validated['status'] = 'pending';
        }

        $kegiatan->update($validated);

        if ($perluDikirimUlangKePembina) {
            $this->notifyPembinaProposalDiajukan($kegiatan->fresh());
        }

        return redirect()->route('ketua.kegiatan')
            ->with('success', 'Kegiatan berhasil diperbarui');
    }

    public function destroy(Kegiatan $kegiatan): RedirectResponse
    {
        $user = auth()->user();

        $organisasi = $user->organisasiSebagaiKetua()->first();
        $organisasiId = $organisasi?->id;

        if ($kegiatan->organisasi_id !== $organisasiId) {
            return redirect()->route('ketua.kegiatan')
                ->with('error', 'Anda tidak punya akses untuk menghapus kegiatan ini.');
        }

        // hapus file proposal
        if ($kegiatan->proposal && !str_starts_with($kegiatan->proposal, 'http')) {
            Storage::disk('public')->delete($kegiatan->proposal);
        }

        $kegiatan->delete();

        return redirect()->route('ketua.kegiatan')
            ->with('success', 'Kegiatan berhasil dihapus');
    }

    private function notifyPembinaProposalDiajukan(Kegiatan $kegiatan): void
    {
        $organisasi = $kegiatan->organisasi()->with('pembina')->first();

        $pembina = $organisasi?->pembina;

        if (!$pembina) {
            return;
        }

        $pembina->notify(new KegiatanWorkflowNotification(
            'Pengajuan Proposal Baru',
            'Proposal kegiatan "' . $kegiatan->nama_kegiatan .
            '" dari ' . ($organisasi?->nama_organisasi ?? 'organisasi') .
            ' menunggu validasi Anda.',
            route('pembina.validasi'),
            'Buka Validasi'
        ));
    }
}
