<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\AbsensiEkskul;
use App\Models\AnggotaOrganisasi;
use App\Models\FotoPertemuanEkskul;
use App\Models\PertemuanEkskul;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AbsensiEkskulController extends Controller
{
    /**
     * Riwayat pertemuan (halaman utama).
     */
    public function __invoke(Request $request): View
    {
        $user = auth()->user();
        $organisasiList = $user->organisasiSebagaiPembina()->get();
        $selectedOrgId = $request->query('organisasi_id') ?: $organisasiList->first()?->id;
        $organisasi = $organisasiList->firstWhere('id', $selectedOrgId);

        $pertemuan = PertemuanEkskul::where('organisasi_id', $selectedOrgId)
            ->withCount([
                'absensi as hadir_count' => fn ($q) => $q->where('status', 'hadir'),
                'absensi as izin_count' => fn ($q) => $q->where('status', 'izin'),
                'absensi as sakit_count' => fn ($q) => $q->where('status', 'sakit'),
                'absensi as alfa_count' => fn ($q) => $q->where('status', 'alfa'),
            ])
            ->orderByDesc('tanggal')
            ->orderByDesc('pertemuan_ke')
            ->get();

        $totalAnggota = AnggotaOrganisasi::where('organisasi_id', $selectedOrgId)->count();

        return view('pembina.absensi-ekskul.index', compact('pertemuan', 'organisasi', 'totalAnggota', 'organisasiList', 'selectedOrgId'));
    }

    /**
     * Form input absensi pertemuan baru.
     */
    public function create(Request $request): View
    {
        $user = auth()->user();
        $organisasiList = $user->organisasiSebagaiPembina()->get();
        $selectedOrgId = $request->query('organisasi_id') ?: $organisasiList->first()?->id;
        $organisasi = $organisasiList->firstWhere('id', $selectedOrgId);

        $anggota = AnggotaOrganisasi::where('organisasi_id', $selectedOrgId)
            ->orderBy('nama')
            ->get();

        // Hitung nomor pertemuan berikutnya
        $nextPertemuan = PertemuanEkskul::where('organisasi_id', $selectedOrgId)->max('pertemuan_ke') + 1;

        return view('pembina.absensi-ekskul.create', compact('anggota', 'organisasi', 'nextPertemuan', 'organisasiList', 'selectedOrgId'));
    }

    /**
     * Simpan pertemuan baru + absensi + foto.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'organisasi_id' => ['required', 'exists:organisasi,id'],
            'tanggal' => ['required', 'date'],
            'pertemuan_ke' => ['required', 'integer', 'min:1'],
            'semester' => ['required', 'in:Ganjil,Genap'],
            'tahun_ajaran' => ['required', 'string', 'max:15'],
            'deskripsi_kegiatan' => ['nullable', 'string'],
            'absensi' => ['required', 'array'],
            'absensi.*' => ['required', 'in:hadir,izin,sakit,alfa'],
            'foto_kegiatan' => ['nullable', 'array'],
            'foto_kegiatan.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'keterangan_foto' => ['nullable', 'array'],
            'keterangan_foto.*' => ['nullable', 'string', 'max:255'],
        ]);

        $organisasi = $user->organisasiSebagaiPembina()->firstWhere('organisasi.id', $validated['organisasi_id']);

        if (! $organisasi) {
            return redirect()->route('pembina.absensi-ekskul')
                ->with('error', 'Anda tidak memiliki akses ke organisasi ini.');
        }

        DB::transaction(function () use ($validated, $organisasi, $user, $request) {
            // Simpan pertemuan
            $pertemuan = PertemuanEkskul::create([
                'organisasi_id' => $organisasi->id,
                'pembina_id' => $user->id,
                'tanggal' => $validated['tanggal'],
                'pertemuan_ke' => $validated['pertemuan_ke'],
                'semester' => $validated['semester'],
                'tahun_ajaran' => $validated['tahun_ajaran'],
                'deskripsi_kegiatan' => $validated['deskripsi_kegiatan'] ?? null,
            ]);

            // Simpan absensi per anggota
            foreach ($validated['absensi'] as $anggotaId => $status) {
                AbsensiEkskul::create([
                    'pertemuan_id' => $pertemuan->id,
                    'anggota_id' => $anggotaId,
                    'status' => $status,
                ]);
            }

            // Simpan foto kegiatan (multiple)
            if ($request->hasFile('foto_kegiatan')) {
                $folder = 'absensi-ekskul/' . Str::slug($organisasi->nama_organisasi);

                foreach ($request->file('foto_kegiatan') as $index => $file) {
                    $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();
                    $storedPath = $file->storeAs($folder, $filename, 'public');

                    FotoPertemuanEkskul::create([
                        'pertemuan_id' => $pertemuan->id,
                        'file_path' => $storedPath,
                        'keterangan' => $validated['keterangan_foto'][$index] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('pembina.absensi-ekskul')
            ->with('success', 'Absensi pertemuan berhasil disimpan.');
    }

    /**
     * Detail pertemuan (absensi, foto, deskripsi).
     */
    public function show(PertemuanEkskul $pertemuan): View
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiPembina()->firstWhere('organisasi.id', $pertemuan->organisasi_id);

        if (! $organisasi) {
            abort(403);
        }

        $pertemuan->load(['absensi.anggota', 'fotoKegiatan', 'pembina']);

        return view('pembina.absensi-ekskul.show', compact('pertemuan', 'organisasi'));
    }

    /**
     * Form edit pertemuan.
     */
    public function edit(PertemuanEkskul $pertemuan): View
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiPembina()->firstWhere('organisasi.id', $pertemuan->organisasi_id);

        if (! $organisasi) {
            abort(403);
        }

        $pertemuan->load(['absensi', 'fotoKegiatan']);

        $anggota = AnggotaOrganisasi::where('organisasi_id', $organisasi->id)
            ->orderBy('nama')
            ->get();

        // Map existing absensi data
        $absensiMap = $pertemuan->absensi->pluck('status', 'anggota_id')->toArray();

        return view('pembina.absensi-ekskul.edit', compact('pertemuan', 'organisasi', 'anggota', 'absensiMap'));
    }

    /**
     * Update pertemuan + absensi.
     */
    public function update(Request $request, PertemuanEkskul $pertemuan): RedirectResponse
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiPembina()->firstWhere('organisasi.id', $pertemuan->organisasi_id);

        if (! $organisasi) {
            return redirect()->route('pembina.absensi-ekskul')
                ->with('error', 'Anda tidak punya akses ke pertemuan ini.');
        }

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'pertemuan_ke' => ['required', 'integer', 'min:1'],
            'semester' => ['required', 'in:Ganjil,Genap'],
            'tahun_ajaran' => ['required', 'string', 'max:15'],
            'deskripsi_kegiatan' => ['nullable', 'string'],
            'absensi' => ['required', 'array'],
            'absensi.*' => ['required', 'in:hadir,izin,sakit,alfa'],
            'foto_kegiatan' => ['nullable', 'array'],
            'foto_kegiatan.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'keterangan_foto' => ['nullable', 'array'],
            'keterangan_foto.*' => ['nullable', 'string', 'max:255'],
            'hapus_foto' => ['nullable', 'array'],
            'hapus_foto.*' => ['integer'],
        ]);

        DB::transaction(function () use ($validated, $pertemuan, $organisasi, $request) {
            // Update pertemuan
            $pertemuan->update([
                'tanggal' => $validated['tanggal'],
                'pertemuan_ke' => $validated['pertemuan_ke'],
                'semester' => $validated['semester'],
                'tahun_ajaran' => $validated['tahun_ajaran'],
                'deskripsi_kegiatan' => $validated['deskripsi_kegiatan'] ?? null,
            ]);

            // Update absensi (upsert)
            foreach ($validated['absensi'] as $anggotaId => $status) {
                AbsensiEkskul::updateOrCreate(
                    [
                        'pertemuan_id' => $pertemuan->id,
                        'anggota_id' => $anggotaId,
                    ],
                    ['status' => $status]
                );
            }

            // Hapus foto yang ditandai
            if (! empty($validated['hapus_foto'])) {
                $fotosToDelete = FotoPertemuanEkskul::where('pertemuan_id', $pertemuan->id)
                    ->whereIn('id', $validated['hapus_foto'])
                    ->get();

                foreach ($fotosToDelete as $foto) {
                    if ($foto->file_path && ! str_starts_with($foto->file_path, 'http')) {
                        Storage::disk('public')->delete($foto->file_path);
                    }
                    $foto->delete();
                }
            }

            // Tambah foto baru
            if ($request->hasFile('foto_kegiatan')) {
                $folder = 'absensi-ekskul/' . Str::slug($organisasi->nama_organisasi);

                foreach ($request->file('foto_kegiatan') as $index => $file) {
                    $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();
                    $storedPath = $file->storeAs($folder, $filename, 'public');

                    FotoPertemuanEkskul::create([
                        'pertemuan_id' => $pertemuan->id,
                        'file_path' => $storedPath,
                        'keterangan' => $validated['keterangan_foto'][$index] ?? null,
                    ]);
                }
            }
        });

        return redirect()->route('pembina.absensi-ekskul.show', $pertemuan)
            ->with('success', 'Data pertemuan berhasil diperbarui.');
    }

    /**
     * Hapus pertemuan beserta absensi dan foto.
     */
    public function destroy(PertemuanEkskul $pertemuan): RedirectResponse
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiPembina()->firstWhere('organisasi.id', $pertemuan->organisasi_id);

        if (! $organisasi) {
            return redirect()->route('pembina.absensi-ekskul')
                ->with('error', 'Anda tidak punya akses ke pertemuan ini.');
        }

        // Hapus file foto
        foreach ($pertemuan->fotoKegiatan as $foto) {
            if ($foto->file_path && ! str_starts_with($foto->file_path, 'http')) {
                Storage::disk('public')->delete($foto->file_path);
            }
        }

        $pertemuan->delete(); // cascade deletes absensi + foto

        return redirect()->route('pembina.absensi-ekskul')
            ->with('success', 'Pertemuan berhasil dihapus.');
    }

    /**
     * Rekap kehadiran seluruh anggota.
     */
    public function rekap(Request $request): View
    {
        $user = auth()->user();
        $organisasiList = $user->organisasiSebagaiPembina()->get();
        $selectedOrgId = $request->query('organisasi_id') ?: $organisasiList->first()?->id;
        $organisasi = $organisasiList->firstWhere('id', $selectedOrgId);

        $semester = $request->query('semester');
        $tahunAjaran = $request->query('tahun_ajaran');

        // Ambil semua pertemuan (dengan filter optional)
        $pertemuanQuery = PertemuanEkskul::where('organisasi_id', $selectedOrgId);

        if ($semester) {
            $pertemuanQuery->where('semester', $semester);
        }
        if ($tahunAjaran) {
            $pertemuanQuery->where('tahun_ajaran', $tahunAjaran);
        }

        $pertemuanIds = $pertemuanQuery->pluck('id');
        $totalPertemuan = $pertemuanIds->count();

        // Ambil semua anggota dengan rekap absensi
        $anggota = AnggotaOrganisasi::where('organisasi_id', $selectedOrgId)
            ->withCount([
                'absensi as hadir_count' => fn ($q) => $q->whereIn('pertemuan_id', $pertemuanIds)->where('status', 'hadir'),
                'absensi as izin_count' => fn ($q) => $q->whereIn('pertemuan_id', $pertemuanIds)->where('status', 'izin'),
                'absensi as sakit_count' => fn ($q) => $q->whereIn('pertemuan_id', $pertemuanIds)->where('status', 'sakit'),
                'absensi as alfa_count' => fn ($q) => $q->whereIn('pertemuan_id', $pertemuanIds)->where('status', 'alfa'),
            ])
            ->orderBy('nama')
            ->get();

        // Generate list tahun ajaran & semester secara dinamis berdasarkan pertemuan yang ada
        $tahunAjaranList = PertemuanEkskul::where('organisasi_id', $selectedOrgId)
            ->distinct()
            ->pluck('tahun_ajaran')
            ->filter()
            ->toArray();
        sort($tahunAjaranList);

        $semesterList = PertemuanEkskul::where('organisasi_id', $selectedOrgId)
            ->distinct()
            ->pluck('semester')
            ->filter()
            ->toArray();
        sort($semesterList);

        return view('pembina.absensi-ekskul.rekap', compact(
            'anggota',
            'organisasi',
            'totalPertemuan',
            'semester',
            'tahunAjaran',
            'tahunAjaranList',
            'semesterList',
            'organisasiList',
            'selectedOrgId'
        ));
    }

    /**
     * Export absensi pertemuan spesifik ke PDF.
     */
    public function exportPertemuanPdf(PertemuanEkskul $pertemuan)
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiPembina()->firstWhere('organisasi.id', $pertemuan->organisasi_id);

        if (!$organisasi) {
            abort(403);
        }

        $pertemuan->load(['absensi.anggota', 'pembina']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pembina.absensi-ekskul.pertemuan_pdf', [
            'organisasi' => $organisasi,
            'pertemuan' => $pertemuan,
        ]);

        $filename = 'absensi-pertemuan-' . $pertemuan->pertemuan_ke . '-' . \Illuminate\Support\Str::slug($organisasi->nama_organisasi) . '-' . $pertemuan->tanggal->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
