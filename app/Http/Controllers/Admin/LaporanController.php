<?php

namespace App\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\LaporanKegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = $request->query('search', '');

        $query = $this->baseQuery();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('isi_laporan', 'like', "%{$search}%")
                    ->orWhereHas('kegiatan', function ($kegiatanQuery) use ($search) {
                        $kegiatanQuery->where('nama_kegiatan', 'like', "%{$search}%");
                    });
            });
        }

        $laporan = $query->get();

        return view('admin.laporan', compact('laporan', 'search'));
    }

    public function exportPdf()
    {
        $laporan = $this->baseQuery()->get();

        $pdf = Pdf::loadView('admin.laporan_pdf', [
            'laporan' => $laporan,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-kegiatan-' . now()->format('YmdHis') . '.pdf');
    }

    public function download(LaporanKegiatan $laporan): StreamedResponse|RedirectResponse
    {
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

    private function baseQuery(): Builder
    {
        return LaporanKegiatan::with([
            'kegiatan:id,organisasi_id,nama_kegiatan,tanggal_mulai',
            'kegiatan.organisasi:id,nama_organisasi',
        ])
            ->latest('id')
            ->select([
                'id',
                'kegiatan_id',
                'isi_laporan',
                'file_laporan',
            ]);
    }
}
