<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use App\Models\Kegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DokumentasiController extends Controller
{
    private const DIRECTORY = 'dokumentasi-kegiatan';

    public function __invoke(Request $request): View
    {
        $filterNamaKegiatan = trim((string) $request->query('nama_kegiatan', ''));
        $search = trim((string) $request->query('search', ''));

        $dokumentasi = Dokumentasi::with([
            'kegiatan:id,organisasi_id,nama_kegiatan',
            'kegiatan.organisasi:id,nama_organisasi',
        ])
            ->whereHas('kegiatan.organisasi', function ($query) {
                $query->where('pembina_id', auth()->id());
            })
            ->when($filterNamaKegiatan !== '', function ($query) use ($filterNamaKegiatan) {
                $query->whereHas('kegiatan', function ($kegiatanQuery) use ($filterNamaKegiatan) {
                    $kegiatanQuery->where('nama_kegiatan', 'like', '%' . $filterNamaKegiatan . '%');
                });
            })
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('keterangan', 'like', "%{$search}%")
                        ->orWhereHas('kegiatan', function ($kegiatanQuery) use ($search) {
                            $kegiatanQuery->where('nama_kegiatan', 'like', "%{$search}%");
                        });
                });
            })
            ->latest('created_at')
            ->get([
                'id',
                'kegiatan_id',
                'file_dokumentasi',
                'keterangan',
                'created_at',
            ]);

        $kegiatanList = Kegiatan::where('status', 'disetujui admin')
            ->whereHas('organisasi', function ($query) {
                $query->where('pembina_id', auth()->id());
            })
            ->orderBy('nama_kegiatan')
            ->get(['id', 'nama_kegiatan']);

        return view('pembina.dokumentasi', compact('dokumentasi', 'kegiatanList', 'filterNamaKegiatan', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kegiatan_id' => [
                'required',
                'integer',
            ],
            'keterangan' => [
                'nullable',
                'array',
            ],
            'keterangan.*' => [
                'nullable',
                'string',
            ],
            'file_dokumentasi' => [
                'required',
                'array',
                'min:1',
            ],
            'file_dokumentasi.*' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $kegiatan = Kegiatan::with('organisasi')
            ->where('id', $validated['kegiatan_id'])
            ->where('status', 'disetujui admin')
            ->whereHas('organisasi', fn ($organisasiQuery) => $organisasiQuery->where('pembina_id', auth()->id()))
            ->first();

        if (! $kegiatan) {
            return redirect()
                ->route('pembina.dokumentasi')
                ->withErrors(['kegiatan_id' => 'Kegiatan tidak ditemukan atau tidak tersedia untuk Anda.'])
                ->withInput();
        }
        $folder = self::DIRECTORY . '/' . Str::slug($kegiatan->organisasi?->nama_organisasi ?? 'pembina-' . auth()->id());

        foreach ($request->file('file_dokumentasi') as $index => $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $storedPath = $file->storeAs($folder, $filename, 'public');

            Dokumentasi::create([
                'kegiatan_id' => $validated['kegiatan_id'],
                'keterangan' => $validated['keterangan'][$index] ?? null,
                'file_dokumentasi' => $storedPath,
            ]);
        }

        return redirect()
            ->route('pembina.dokumentasi')
            ->with('success', 'Dokumentasi berhasil diunggah.');
    }

    public function update(Request $request, Dokumentasi $dokumentasi): RedirectResponse
    {
        $isAuthorized = Kegiatan::where('id', $dokumentasi->kegiatan_id)
            ->whereHas('organisasi', fn ($organisasiQuery) => $organisasiQuery->where('pembina_id', auth()->id()))
            ->exists();

        if (! $isAuthorized) {
            return redirect()
                ->route('pembina.dokumentasi')
                ->with('error', 'Anda tidak punya akses ke dokumentasi ini.');
        }

        $validated = $request->validate([
            'kegiatan_id' => [
                'required',
                'integer',
            ],
            'keterangan' => [
                'nullable',
                'string',
            ],
            'file_dokumentasi' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $kegiatan = Kegiatan::where('id', $validated['kegiatan_id'])
            ->where('status', 'disetujui admin')
            ->whereHas('organisasi', fn ($organisasiQuery) => $organisasiQuery->where('pembina_id', auth()->id()))
            ->first();

        if (! $kegiatan) {
            return redirect()
                ->route('pembina.dokumentasi')
                ->withErrors(['kegiatan_id' => 'Kegiatan tidak ditemukan atau tidak tersedia untuk Anda.'])
                ->withInput();
        }

        if ($request->hasFile('file_dokumentasi')) {
            if (
                $dokumentasi->file_dokumentasi &&
                ! str_starts_with($dokumentasi->file_dokumentasi, 'http')
            ) {
                Storage::disk('public')->delete($dokumentasi->file_dokumentasi);
            }

            $folder = self::DIRECTORY . '/' . Str::slug($kegiatan->organisasi?->nama_organisasi ?? 'pembina-' . auth()->id());
            $file = $request->file('file_dokumentasi');
            $filename = time() . '_' . $file->getClientOriginalName();
            $validated['file_dokumentasi'] = $file->storeAs($folder, $filename, 'public');
        }

        $updateData = [
            'kegiatan_id' => $validated['kegiatan_id'],
            'keterangan' => $validated['keterangan'] ?? $dokumentasi->keterangan,
        ];

        if ($request->hasFile('file_dokumentasi')) {
            $updateData['file_dokumentasi'] = $validated['file_dokumentasi'];
        }

        $dokumentasi->update($updateData);

        return redirect()
            ->route('pembina.dokumentasi')
            ->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    public function destroy(Dokumentasi $dokumentasi): RedirectResponse
    {
        $isAuthorized = Kegiatan::where('id', $dokumentasi->kegiatan_id)
            ->whereHas('organisasi', fn ($organisasiQuery) => $organisasiQuery->where('pembina_id', auth()->id()))
            ->exists();

        if (! $isAuthorized) {
            return redirect()
                ->route('pembina.dokumentasi')
                ->with('error', 'Anda tidak punya akses ke dokumentasi ini.');
        }

        if (
            $dokumentasi->file_dokumentasi &&
            ! str_starts_with($dokumentasi->file_dokumentasi, 'http')
        ) {
            Storage::disk('public')->delete($dokumentasi->file_dokumentasi);
        }

        $dokumentasi->delete();

        return redirect()
            ->route('pembina.dokumentasi')
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }
}

