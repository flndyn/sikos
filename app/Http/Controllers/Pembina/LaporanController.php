<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\LaporanKegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function __invoke(): View
    {
        $laporan = LaporanKegiatan::with([
            'kegiatan:id,organisasi_id,nama_kegiatan,tanggal_mulai',
            'kegiatan.organisasi:id,nama_organisasi',
        ])
            ->whereHas('kegiatan.organisasi', function ($query) {
                $query->where('pembina_id', auth()->id());
            })
            ->latest('id')
            ->get(['id', 'kegiatan_id', 'isi_laporan', 'file_laporan']);

        return view('pembina.laporan', compact('laporan'));
    }

    public function download(LaporanKegiatan $laporan): StreamedResponse|RedirectResponse
    {
        $isAuthorized = $laporan->kegiatan()
            ->whereHas('organisasi', function ($query) {
                $query->where('pembina_id', auth()->id());
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
}
