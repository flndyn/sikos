<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanKegiatan;
use App\Models\Kegiatan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    private const REPORT_DIRECTORY = 'laporan-kegiatan';

    public function __invoke(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $query = $this->baseQuery();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('isi_laporan', 'like', "%{$search}%")
                    ->orWhereHas('kegiatan', function ($kegiatanQuery) use ($search) {
                        $kegiatanQuery->where('nama_kegiatan', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kegiatan.organisasi', function ($organisasiQuery) use ($search) {
                        $organisasiQuery->where('nama_organisasi', 'like', "%{$search}%");
                    });
            });
        }

        $laporan = $query->get();

        // Get all activities
        $kegiatanTersedia = Kegiatan::orderByDesc('tanggal_mulai')
            ->get(['id', 'nama_kegiatan', 'tanggal_mulai']);

        return view('admin.laporan', compact('laporan', 'search', 'kegiatanTersedia'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kegiatan_id' => [
                'required',
                'integer',
                'exists:kegiatan,id',
                Rule::unique('laporan_kegiatan', 'kegiatan_id')
            ],
            'isi_laporan' => [
                'required',
                'string'
            ],
            'file_laporan' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120'
            ],
        ], [
            'kegiatan_id.unique' => 'Kegiatan ini sudah memiliki laporan.',
        ]);

        $kegiatan = Kegiatan::findOrFail($validated['kegiatan_id']);
        $organisasi = $kegiatan->organisasi;

        $folder = self::REPORT_DIRECTORY . '/' . Str::slug($organisasi->nama_organisasi);
        $file = $request->file('file_laporan');
        $filename = time() . '_' . $file->getClientOriginalName();

        $validated['file_laporan'] = $file->storeAs(
            $folder,
            $filename,
            'public'
        );

        LaporanKegiatan::create([
            'kegiatan_id' => $validated['kegiatan_id'],
            'isi_laporan' => $validated['isi_laporan'],
            'file_laporan' => $validated['file_laporan'],
            'status' => 'disetujui pembina',
        ]);

        return redirect()
            ->route('admin.laporan')
            ->with('success', 'Laporan kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request, LaporanKegiatan $laporan): RedirectResponse
    {
        $validated = $request->validate([
            'isi_laporan' => [
                'required',
                'string'
            ],
            'file_laporan' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120'
            ],
        ]);

        if ($request->hasFile('file_laporan')) {
            if ($laporan->file_laporan && !str_starts_with($laporan->file_laporan, 'http')) {
                Storage::disk('public')->delete($laporan->file_laporan);
            }

            $kegiatan = $laporan->kegiatan;
            $organisasi = $kegiatan->organisasi;

            $folder = self::REPORT_DIRECTORY . '/' . Str::slug($organisasi->nama_organisasi);
            $file = $request->file('file_laporan');
            $filename = time() . '_' . $file->getClientOriginalName();

            $validated['file_laporan'] = $file->storeAs(
                $folder,
                $filename,
                'public'
            );
        }

        $validated['status'] = 'disetujui pembina';
        $validated['keterangan'] = null;

        $laporan->update($validated);

        return redirect()
            ->route('admin.laporan')
            ->with('success', 'Laporan kegiatan berhasil diperbarui.');
    }

    public function destroy(LaporanKegiatan $laporan): RedirectResponse
    {
        if ($laporan->file_laporan && !str_starts_with($laporan->file_laporan, 'http')) {
            Storage::disk('public')->delete($laporan->file_laporan);
        }

        $laporan->delete();

        return redirect()
            ->route('admin.laporan')
            ->with('success', 'Laporan kegiatan berhasil dihapus.');
    }

    public function exportPdf()
    {
        $laporan = $this->baseQuery()->get();

        $pdf = Pdf::loadView('admin.laporan_pdf', [
            'laporan' => $laporan,
        ])->setPaper('a4', 'landscape');

        return $pdf->download(
            'laporan-kegiatan-' . now()->format('YmdHis') . '.pdf'
        );
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

        if (! Storage::disk('public')->exists($file)) {
            return back()->with('error', 'File laporan tidak ditemukan di storage.');
        }

        return Storage::disk('public')->download($file);
    }

    private function baseQuery(): Builder
    {
        return LaporanKegiatan::with([
            'kegiatan:id,organisasi_id,nama_kegiatan,tanggal_mulai',
            'kegiatan.organisasi:id,nama_organisasi',
        ])
            ->where('status', 'disetujui pembina')
            ->latest('id')
            ->select([
                'id',
                'kegiatan_id',
                'isi_laporan',
                'file_laporan',
                'status',
                'keterangan',
            ]);
    }
}