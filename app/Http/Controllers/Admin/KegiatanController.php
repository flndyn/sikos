<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\Organisasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KegiatanController extends Controller
{
    private const PROPOSAL_DIRECTORY = 'proposal-kegiatan';
    private const ALLOWED_STATUSES = [
        'pending',
        'disetujui pembina',
        'disetujui admin',
        'ditolak pembina',
        'ditolak admin',
    ];
    private const REJECTED_STATUSES = [
        'ditolak pembina',
        'ditolak admin',
    ];

    public function __invoke(): View
    {
        $kegiatan = Kegiatan::with([
            'organisasi:id,nama_organisasi',
        ])
            ->latest('id')
            ->get([
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

        $organisasiList = Organisasi::orderBy('nama_organisasi')
            ->get(['id', 'nama_organisasi']);

        return view('admin.kegiatan', compact('kegiatan', 'organisasiList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'organisasi_id' => ['required', 'integer', Rule::exists('organisasi', 'id')],
            'nama_kegiatan' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tempat' => ['nullable', 'string', 'max:150'],
            'proposal' => ['required'],
            'status' => ['required', Rule::in(self::ALLOWED_STATUSES)],
            'keterangan' => ['nullable', 'string'],
        ]);

        if (in_array($validated['status'], self::REJECTED_STATUSES, true) && blank($validated['keterangan'] ?? null)) {
            return back()
                ->withErrors(['keterangan' => 'Keterangan wajib diisi jika status ditolak.'])
                ->withInput();
        }

        if (! $request->hasFile('proposal')) {
            return back()
                ->withErrors(['proposal' => 'File proposal wajib diunggah.'])
                ->withInput();
        }

        $proposalFile = $request->file('proposal');

        if (! $proposalFile->isValid()) {
            return back()
                ->withErrors(['proposal' => 'File proposal gagal diunggah.'])
                ->withInput();
        }

        $validated['proposal'] = $this->storeProposalFile($proposalFile);

        if (! $validated['proposal']) {
            return back()
                ->withErrors(['proposal' => 'File proposal gagal disimpan ke storage.'])
                ->withInput();
        }

        Kegiatan::create($validated);

        return redirect()
            ->route('admin.kegiatan')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        $validated = $request->validate([
            'organisasi_id' => ['required', 'integer', Rule::exists('organisasi', 'id')],
            'nama_kegiatan' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tempat' => ['nullable', 'string', 'max:150'],
            'status' => ['required', Rule::in(self::ALLOWED_STATUSES)],
            'keterangan' => ['nullable', 'string'],
        ]);

        if (in_array($validated['status'], self::REJECTED_STATUSES, true) && blank($validated['keterangan'] ?? null)) {
            return back()
                ->withErrors(['keterangan' => 'Keterangan wajib diisi jika status ditolak.'])
                ->withInput();
        }

        if ($request->hasFile('proposal')) {
            $proposalFile = $request->file('proposal');

            if (! $proposalFile->isValid()) {
                return back()
                    ->withErrors(['proposal' => 'File proposal gagal diunggah.'])
                    ->withInput();
            }

            $extension = strtolower($proposalFile->getClientOriginalExtension());
            if (! in_array($extension, ['pdf', 'doc', 'docx'], true)) {
                return back()
                    ->withErrors(['proposal' => 'Format proposal harus PDF, DOC, atau DOCX.'])
                    ->withInput();
            }

            $existingProposalPath = $this->getProposalStoragePath($kegiatan->proposal);
            if ($existingProposalPath) {
                Storage::disk('public')->delete($existingProposalPath);
            }

            $validated['proposal'] = $this->storeProposalFile($proposalFile);

            if (! $validated['proposal']) {
                return back()
                    ->withErrors(['proposal' => 'File proposal gagal disimpan ke storage.'])
                    ->withInput();
            }
        } else {
            unset($validated['proposal']);
        }

        $kegiatan->update($validated);

        return redirect()
            ->route('admin.kegiatan')
            ->with('success', 'Data kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan): RedirectResponse
    {
        $existingProposalPath = $this->getProposalStoragePath($kegiatan->proposal);
        if ($existingProposalPath) {
            Storage::disk('public')->delete($existingProposalPath);
        }

        $kegiatan->delete();

        return redirect()
            ->route('admin.kegiatan')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    private function getProposalStoragePath(?string $proposal): ?string
    {
        if (! $proposal) {
            return null;
        }

        if (str_starts_with($proposal, 'http')) {
            return null;
        }

        if (str_contains($proposal, '/')) {
            return $proposal;
        }

        return self::PROPOSAL_DIRECTORY . '/' . $proposal;
    }

    private function storeProposalFile(UploadedFile $proposalFile): ?string
    {
        $extension = strtolower($proposalFile->getClientOriginalExtension());
        if (! in_array($extension, ['pdf', 'doc', 'docx'], true)) {
            return null;
        }

        $originalName = pathinfo($proposalFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = Str::slug($originalName, '_');
        $storedFileName = $safeName . '_' . now()->format('YmdHis') . '.' . $extension;

        $storedPath = $proposalFile->storeAs(self::PROPOSAL_DIRECTORY, $storedFileName, 'public');

        return $storedPath ? $storedFileName : null;
    }
}
