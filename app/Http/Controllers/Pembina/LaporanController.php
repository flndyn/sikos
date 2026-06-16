<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\LaporanKegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = $request->query('search', '');

        $query = LaporanKegiatan::with([
            'kegiatan:id,organisasi_id,nama_kegiatan,tanggal_mulai',
            'kegiatan.organisasi:id,nama_organisasi',
        ])
            ->whereHas('kegiatan.organisasi.pembinaUsers', function ($query) {
                $query->where('users.id', auth()->id());
            });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('isi_laporan', 'like', "%{$search}%")
                    ->orWhereHas('kegiatan', function ($kegiatanQuery) use ($search) {
                        $kegiatanQuery->where('nama_kegiatan', 'like', "%{$search}%");
                    });
            });
        }

        $laporan = $query->latest('id')->get(['id', 'kegiatan_id', 'isi_laporan', 'file_laporan', 'status', 'keterangan']);

        return view('pembina.laporan', compact('laporan', 'search'));
    }

    public function download(LaporanKegiatan $laporan): StreamedResponse|RedirectResponse
    {
        $isAuthorized = $laporan->kegiatan()
            ->whereHas('organisasi.pembinaUsers', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->exists();

        if (! $isAuthorized) {
            return back()->with('error', 'Anda tidak memiliki akses ke laporan ini.');
        }

        $file = $laporan->file_laporan;

        if (! $file) {
            return back()->with('error', 'File laporan tidak tersedia.');
        }

        if (str_starts_with($file, 'http')) {
            return redirect()->away($file);
        }

        $candidatePaths = [$file];
        if (! str_contains($file, '/')) {
            $candidatePaths[] = 'laporan-kegiatan/' . $file;
        }

        foreach ($candidatePaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->download($path);
            }
        }

        return back()->with('error', 'File laporan tidak ditemukan di storage.');
    }

    public function approve(LaporanKegiatan $laporan): RedirectResponse
    {
        $isAuthorized = $laporan->kegiatan()
            ->whereHas('organisasi.pembinaUsers', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->exists();

        if (! $isAuthorized) {
            return back()->with('error', 'Anda tidak memiliki akses ke laporan ini.');
        }

        if ($laporan->status !== 'pending') {
            return back()->with('error', 'Laporan sudah divalidasi sebelumnya.');
        }

        $laporan->update([
            'status' => 'disetujui pembina',
            'keterangan' => null,
        ]);

        return back()->with('success', 'Laporan kegiatan telah disetujui.');
    }

    public function reject(Request $request, LaporanKegiatan $laporan): RedirectResponse
    {
        $isAuthorized = $laporan->kegiatan()
            ->whereHas('organisasi.pembinaUsers', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->exists();

        if (! $isAuthorized) {
            return back()->with('error', 'Anda tidak memiliki akses ke laporan ini.');
        }

        if ($laporan->status !== 'pending') {
            return back()->with('error', 'Laporan sudah divalidasi sebelumnya.');
        }

        $validated = $request->validate([
            'keterangan' => [
                'nullable',
                'string',
                'in:Laporan belum lengkap,Format file tidak sesuai,Data dokumentasi kurang jelas',
            ],
            'keterangan_custom' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        $keterangan = trim($validated['keterangan_custom'] ?? '') ?: ($validated['keterangan'] ?? null);

        $laporan->update([
            'status' => 'ditolak pembina',
            'keterangan' => $keterangan,
        ]);

        return back()->with('success', 'Laporan kegiatan telah ditolak.');
    }
}
