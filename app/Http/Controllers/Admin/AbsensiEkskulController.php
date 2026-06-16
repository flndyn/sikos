<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnggotaOrganisasi;
use App\Models\Organisasi;
use App\Models\PertemuanEkskul;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AbsensiEkskulController extends Controller
{
    /**
     * Daftar semua organisasi dengan ringkasan pertemuan.
     */
    public function __invoke(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $query = Organisasi::withCount('pertemuanEkskul', 'anggota');

        if ($search !== '') {
            $query->where('nama_organisasi', 'like', "%{$search}%");
        }

        $organisasiList = $query->orderBy('nama_organisasi')->get();

        return view('admin.absensi-ekskul.index', compact('organisasiList', 'search'));
    }

    /**
     * Riwayat pertemuan dan rekap kehadiran per organisasi.
     */
    public function show(Request $request, Organisasi $organisasi): View
    {
        $semester = $request->query('semester');
        $tahunAjaran = $request->query('tahun_ajaran');

        // Daftar pertemuan
        $pertemuanQuery = PertemuanEkskul::where('organisasi_id', $organisasi->id)
            ->withCount([
                'absensi as hadir_count' => fn ($q) => $q->where('status', 'hadir'),
                'absensi as izin_count' => fn ($q) => $q->where('status', 'izin'),
                'absensi as sakit_count' => fn ($q) => $q->where('status', 'sakit'),
                'absensi as alfa_count' => fn ($q) => $q->where('status', 'alfa'),
            ]);

        if ($semester) {
            $pertemuanQuery->where('semester', $semester);
        }
        if ($tahunAjaran) {
            $pertemuanQuery->where('tahun_ajaran', $tahunAjaran);
        }

        $pertemuan = $pertemuanQuery->orderByDesc('tanggal')
            ->orderByDesc('pertemuan_ke')
            ->get();

        // Rekap kehadiran
        $pertemuanIds = $pertemuan->pluck('id');
        $totalPertemuan = $pertemuanIds->count();

        $anggota = AnggotaOrganisasi::where('organisasi_id', $organisasi->id)
            ->withCount([
                'absensi as hadir_count' => fn ($q) => $q->whereIn('pertemuan_id', $pertemuanIds)->where('status', 'hadir'),
                'absensi as izin_count' => fn ($q) => $q->whereIn('pertemuan_id', $pertemuanIds)->where('status', 'izin'),
                'absensi as sakit_count' => fn ($q) => $q->whereIn('pertemuan_id', $pertemuanIds)->where('status', 'sakit'),
                'absensi as alfa_count' => fn ($q) => $q->whereIn('pertemuan_id', $pertemuanIds)->where('status', 'alfa'),
            ])
            ->orderBy('nama')
            ->get();

        // Generate list tahun ajaran & semester secara dinamis berdasarkan pertemuan yang ada
        $tahunAjaranList = PertemuanEkskul::where('organisasi_id', $organisasi->id)
            ->distinct()
            ->pluck('tahun_ajaran')
            ->filter()
            ->toArray();
        sort($tahunAjaranList);

        $semesterList = PertemuanEkskul::where('organisasi_id', $organisasi->id)
            ->distinct()
            ->pluck('semester')
            ->filter()
            ->toArray();
        sort($semesterList);

        $tahunList = PertemuanEkskul::where('organisasi_id', $organisasi->id)
            ->selectRaw('YEAR(tanggal) as tahun')
            ->distinct()
            ->orderBy('tahun')
            ->pluck('tahun')
            ->toArray();

        if (empty($tahunList)) {
            $tahunList = [(int) date('Y')];
        }

        return view('admin.absensi-ekskul.show', compact(
            'organisasi',
            'pertemuan',
            'anggota',
            'totalPertemuan',
            'semester',
            'tahunAjaran',
            'tahunAjaranList',
            'semesterList',
            'tahunList'
        ));
    }

    /**
     * Detail pertemuan spesifik.
     */
    public function detail(Organisasi $organisasi, PertemuanEkskul $pertemuan): View
    {
        if ($pertemuan->organisasi_id !== $organisasi->id) {
            abort(404);
        }

        $pertemuan->load(['absensi.anggota', 'fotoKegiatan', 'pembina']);

        return view('admin.absensi-ekskul.detail', compact('organisasi', 'pertemuan'));
    }

    /**
     * Export rekap kehadiran ke PDF.
     */
    public function exportPdf(Request $request, Organisasi $organisasi)
    {
        $exportType = $request->query('export_type', 'semester');
        $semester = $request->query('semester');
        $tahunAjaran = $request->query('tahun_ajaran');
        $bulan = $request->query('bulan');
        $tahun = $request->query('tahun');

        $pertemuanQuery = PertemuanEkskul::where('organisasi_id', $organisasi->id);

        if ($exportType === 'bulan') {
            if ($bulan) {
                $pertemuanQuery->whereMonth('tanggal', $bulan);
            }
            if ($tahun) {
                $pertemuanQuery->whereYear('tanggal', $tahun);
            }
        } else {
            if ($semester) {
                $pertemuanQuery->where('semester', $semester);
            }
            if ($tahunAjaran) {
                $pertemuanQuery->where('tahun_ajaran', $tahunAjaran);
            }
        }

        $pertemuanIds = $pertemuanQuery->pluck('id');
        $totalPertemuan = $pertemuanIds->count();

        if ($totalPertemuan === 0) {
            return redirect()->route('admin.absensi-ekskul.show', $organisasi)->with('error', 'Tidak ada data pertemuan pada filter tersebut untuk diexport.');
        }

        $anggota = AnggotaOrganisasi::where('organisasi_id', $organisasi->id)
            ->withCount([
                'absensi as hadir_count' => fn ($q) => $q->whereIn('pertemuan_id', $pertemuanIds)->where('status', 'hadir'),
                'absensi as izin_count' => fn ($q) => $q->whereIn('pertemuan_id', $pertemuanIds)->where('status', 'izin'),
                'absensi as sakit_count' => fn ($q) => $q->whereIn('pertemuan_id', $pertemuanIds)->where('status', 'sakit'),
                'absensi as alfa_count' => fn ($q) => $q->whereIn('pertemuan_id', $pertemuanIds)->where('status', 'alfa'),
            ])
            ->orderBy('nama')
            ->get();

        $namaBulan = null;
        if ($exportType === 'bulan' && $bulan) {
            $bulanIndo = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];
            $namaBulan = $bulanIndo[(int)$bulan] ?? null;
        }

        $pdf = Pdf::loadView('admin.absensi-ekskul.rekap_pdf', [
            'organisasi' => $organisasi,
            'anggota' => $anggota,
            'totalPertemuan' => $totalPertemuan,
            'exportType' => $exportType,
            'semester' => $semester,
            'tahunAjaran' => $tahunAjaran,
            'bulan' => $bulan,
            'namaBulan' => $namaBulan,
            'tahun' => $tahun,
        ])->setPaper('a4', 'landscape');

        $filename = 'rekap-absensi-' . \Illuminate\Support\Str::slug($organisasi->nama_organisasi) . '-' . now()->format('YmdHis') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export absensi pertemuan spesifik ke PDF.
     */
    public function exportPertemuanPdf(Organisasi $organisasi, PertemuanEkskul $pertemuan)
    {
        if ($pertemuan->organisasi_id !== $organisasi->id) {
            abort(404);
        }

        $pertemuan->load(['absensi.anggota', 'pembina']);

        $pdf = Pdf::loadView('pembina.absensi-ekskul.pertemuan_pdf', [
            'organisasi' => $organisasi,
            'pertemuan' => $pertemuan,
        ]);

        $filename = 'absensi-pertemuan-' . $pertemuan->pertemuan_ke . '-' . \Illuminate\Support\Str::slug($organisasi->nama_organisasi) . '-' . $pertemuan->tanggal->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
