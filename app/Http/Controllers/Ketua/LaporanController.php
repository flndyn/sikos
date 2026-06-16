<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use App\Models\LaporanKegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;


class LaporanController extends Controller
{
    private const REPORT_DIRECTORY = 'laporan-kegiatan';

    public function __invoke(Request $request): View
    {
        $organisasiId = auth()->user()?->organisasiSebagaiKetua()->first()?->id;

        $search = $request->query('search', '');

        $laporan = LaporanKegiatan::with([
            'kegiatan:id,organisasi_id,nama_kegiatan,tanggal_mulai',
            'kegiatan.organisasi:id,nama_organisasi',
        ])
            ->whereHas('kegiatan', function ($query) use ($organisasiId) {
                $query->where('organisasi_id', $organisasiId);
            })

            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {

                    $q->where('isi_laporan', 'like', "%{$search}%")

                        ->orWhereHas('kegiatan', function ($kegiatanQuery) use ($search) {
                            $kegiatanQuery->where(
                                'nama_kegiatan',
                                'like',
                                "%{$search}%"
                            );
                        });
                });
            })

            ->latest('id')

            ->get([
                'id',
                'kegiatan_id',
                'isi_laporan',
                'file_laporan',
                'status',
                'keterangan',
                'created_at'
            ]);

        $kegiatanTersedia = Kegiatan::where('organisasi_id', $organisasiId)

            ->whereIn('status', [
                'disetujui pembina',
                'disetujui admin'
            ])

            ->orderByDesc('tanggal_mulai')

            ->get([
                'id',
                'nama_kegiatan',
                'tanggal_mulai'
            ]);

        return view(
            'ketua.laporan',
            compact(
                'laporan',
                'kegiatanTersedia',
                'search'
            )
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $organisasiId = auth()->user()?->organisasiSebagaiKetua()->first()?->id;

        if (! $organisasiId) {
            return redirect()
                ->route('ketua.laporan')
                ->with(
                    'error',
                    'Anda belum menjadi ketua di organisasi manapun.'
                );
        }

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
            'kegiatan_id.unique' =>
                'Kegiatan ini sudah memiliki laporan.',
        ]);

        $kegiatanValid = Kegiatan::where(
            'id',
            $validated['kegiatan_id']
        )

            ->where('organisasi_id', $organisasiId)

            ->whereIn('status', [
                'disetujui pembina',
                'disetujui admin'
            ])

            ->exists();

        if (! $kegiatanValid) {
            return redirect()
                ->route('ketua.laporan')
                ->with(
                    'error',
                    'Kegiatan tidak valid untuk unggah laporan.'
                );
        }

        $organisasi = auth()->user()?->organisasiSebagaiKetua()->first();

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
        ]);

        return redirect()
            ->route('ketua.laporan')
            ->with(
                'success',
                'Laporan kegiatan berhasil diunggah.'
            );
    }

    public function update(
        Request $request,
        LaporanKegiatan $laporan
    ): RedirectResponse {

        $organisasiId = auth()->user()?->organisasiSebagaiKetua()->first()?->id;

        $isAuthorized = $laporan->kegiatan()

            ->where('organisasi_id', $organisasiId)

            ->exists();

        if (! $isAuthorized) {
            return redirect()
                ->route('ketua.laporan')
                ->with(
                    'error',
                    'Anda tidak memiliki akses ke laporan ini.'
                );
        }

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

            if (
                $laporan->file_laporan &&
                ! str_starts_with($laporan->file_laporan, 'http')
            ) {
                Storage::disk('public')
                    ->delete($laporan->file_laporan);
            }

            $organisasi = auth()->user()?->organisasiSebagaiKetua()->first();

            $folder = self::REPORT_DIRECTORY . '/' . Str::slug($organisasi->nama_organisasi);

            $file = $request->file('file_laporan');

            $filename = time() . '_' . $file->getClientOriginalName();

            $validated['file_laporan'] = $file->storeAs(
                $folder,
                $filename,
                'public'
            );
        }

        $validated['status'] = 'pending';
        $validated['keterangan'] = null;
        $laporan->update($validated);

        return redirect()
            ->route('ketua.laporan')
            ->with(
                'success',
                'Laporan kegiatan berhasil diperbarui.'
            );
    }

    public function destroy(
        LaporanKegiatan $laporan
    ): RedirectResponse {

        $organisasiId = auth()->user()?->organisasiSebagaiKetua()->first()?->id;

        $isAuthorized = $laporan->kegiatan()

            ->where('organisasi_id', $organisasiId)

            ->exists();

        if (! $isAuthorized) {
            return redirect()
                ->route('ketua.laporan')
                ->with(
                    'error',
                    'Anda tidak memiliki akses ke laporan ini.'
                );
        }

        if (
            $laporan->file_laporan &&
            ! str_starts_with($laporan->file_laporan, 'http')
        ) {
            Storage::disk('public')
                ->delete($laporan->file_laporan);
        }

        $laporan->delete();

        return redirect()
            ->route('ketua.laporan')
            ->with(
                'success',
                'Laporan kegiatan berhasil dihapus.'
            );
    }

    public function download(
        LaporanKegiatan $laporan
    ): RedirectResponse|\Symfony\Component\HttpFoundation\StreamedResponse {

        $organisasiId = auth()->user()?->organisasiSebagaiKetua()->first()?->id;

        $isAuthorized = $laporan->kegiatan()
            ->where('organisasi_id', $organisasiId)
            ->exists();

        if (! $isAuthorized) {
            return redirect()
                ->route('ketua.laporan')
                ->with('error', 'Anda tidak memiliki akses ke laporan ini.');
        }

        $file = $laporan->file_laporan;

        if (! $file) {
            return redirect()
                ->route('ketua.laporan')
                ->with('error', 'File laporan tidak tersedia.');
        }

        if (str_starts_with($file, 'http')) {
            return redirect()->away($file);
        }

        $candidatePaths = [$file];

        if (! str_contains($file, '/')) {
            $candidatePaths[] = self::REPORT_DIRECTORY . '/' . $file;
        }

        foreach ($candidatePaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                $fullPath = realpath(Storage::disk('public')->path($path));

                if (! $fullPath || ! is_file($fullPath)) {
                    continue;
                }

                $filename = basename($fullPath);
                $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';
                $fileSize = filesize($fullPath);

                return response()->streamDownload(function () use ($fullPath) {
                    $handle = fopen($fullPath, 'rb');
                    while (! feof($handle)) {
                        echo fread($handle, 8192);
                        ob_flush();
                        flush();
                    }
                    fclose($handle);
                }, $filename, [
                    'Content-Type'              => $mimeType,
                    'Content-Length'            => $fileSize,
                    'Content-Disposition'       => 'attachment; filename="' . $filename . '"',
                    'Cache-Control'             => 'no-cache, no-store, must-revalidate',
                    'Pragma'                    => 'no-cache',
                    'Expires'                   => '0',
                ]);
            }
        }

        return redirect()
            ->route('ketua.laporan')
            ->with('error', 'File laporan tidak ditemukan di storage.');
    }
}
