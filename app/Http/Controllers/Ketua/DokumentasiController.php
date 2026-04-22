<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Kegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DokumentasiController extends Controller
{
    private const DIRECTORY = 'dokumentasi-kegiatan';

    public function __invoke(Request $request): View
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiKetua()->first();
        $organisasiId = $organisasi?->id;
        $filterNamaKegiatan = trim((string) $request->query('nama_kegiatan', ''));

        $dokumentasi = Dokumentasi::with('kegiatan:id,nama_kegiatan')
            ->whereHas('kegiatan', function ($query) use ($organisasiId) {
                $query->where('organisasi_id', $organisasiId);
            })
            ->when($filterNamaKegiatan !== '', function ($query) use ($filterNamaKegiatan) {
                $query->whereHas('kegiatan', function ($kegiatanQuery) use ($filterNamaKegiatan) {
                    $kegiatanQuery->where('nama_kegiatan', 'like', '%' . $filterNamaKegiatan . '%');
                });
            })
            ->latest('id')
            ->get(['id', 'kegiatan_id', 'file_dokumentasi', 'keterangan']);

        $kegiatanList = Kegiatan::where('organisasi_id', $organisasiId)
            ->where('status', 'disetujui admin')
            ->orderBy('nama_kegiatan')
            ->get(['id', 'nama_kegiatan']);

        return view('ketua.dokumentasi', compact('dokumentasi', 'kegiatanList', 'filterNamaKegiatan'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiKetua()->first();
        $organisasiId = $organisasi?->id;

        if (! $organisasiId) {
            return redirect()->route('ketua.dokumentasi')->with('error', 'Anda belum menjadi ketua di organisasi manapun.');
        }

        $files = $request->file('file_dokumentasi', []);
        $totalFiles = count($files);

        $validated = $request->validate([
            'kegiatan_id' => [
                'required',
                'integer',
                Rule::exists('kegiatan', 'id')->where(
                    fn ($query) => $query
                        ->where('organisasi_id', $organisasiId)
                        ->where('status', 'disetujui admin')
                ),
            ],
            'keterangan' => ['required', 'array', 'size:' . $totalFiles],
            'keterangan.*' => ['required', 'string'],
            'file_dokumentasi' => ['required', 'array', 'min:1'],
            'file_dokumentasi.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        foreach ($files as $index => $file) {
            $storedPath = $file->store(self::DIRECTORY, 'public');

            Dokumentasi::create([
                'kegiatan_id' => $validated['kegiatan_id'],
                'keterangan' => $validated['keterangan'][$index],
                'file_dokumentasi' => $storedPath,
            ]);
        }

        return redirect()
            ->route('ketua.dokumentasi')
            ->with('success', 'Dokumentasi berhasil diunggah.');
    }

    public function update(Request $request, Dokumentasi $dokumentasi): RedirectResponse
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiKetua()->first();
        $organisasiId = $organisasi?->id;

        $isAuthorized = Kegiatan::where('id', $dokumentasi->kegiatan_id)
            ->where('organisasi_id', $organisasiId)
            ->exists();

        if (! $isAuthorized) {
            return redirect()->route('ketua.dokumentasi')->with('error', 'Anda tidak punya akses ke dokumentasi ini.');
        }

        $validated = $request->validate([
            'kegiatan_id' => [
                'required',
                'integer',
                Rule::exists('kegiatan', 'id')->where(fn ($query) => $query->where('organisasi_id', $organisasiId)),
            ],
            'keterangan' => ['nullable', 'string'],
            'file_dokumentasi' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($request->hasFile('file_dokumentasi')) {
            if ($dokumentasi->file_dokumentasi && ! str_starts_with($dokumentasi->file_dokumentasi, 'http')) {
                Storage::disk('public')->delete($dokumentasi->file_dokumentasi);
            }

            $validated['file_dokumentasi'] = $request->file('file_dokumentasi')->store(self::DIRECTORY, 'public');
        } else {
            unset($validated['file_dokumentasi']);
        }

        $dokumentasi->update($validated);

        return redirect()
            ->route('ketua.dokumentasi')
            ->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    public function destroy(Dokumentasi $dokumentasi): RedirectResponse
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiKetua()->first();
        $organisasiId = $organisasi?->id;

        $isAuthorized = Kegiatan::where('id', $dokumentasi->kegiatan_id)
            ->where('organisasi_id', $organisasiId)
            ->exists();

        if (! $isAuthorized) {
            return redirect()->route('ketua.dokumentasi')->with('error', 'Anda tidak punya akses ke dokumentasi ini.');
        }

        if ($dokumentasi->file_dokumentasi && ! str_starts_with($dokumentasi->file_dokumentasi, 'http')) {
            Storage::disk('public')->delete($dokumentasi->file_dokumentasi);
        }

        $dokumentasi->delete();

        return redirect()
            ->route('ketua.dokumentasi')
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }
}
