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
    private const ALLOWED_STATUSES = [
        'pending',
        'disetujui pembina',
        'disetujui admin',
        'ditolak pembina',
        'ditolak admin',
    ];

    public function __invoke(): View
    {
        $query = Kegiatan::with([
            'organisasi:id,nama_organisasi',
        ])->latest('id');

        $status = request('status');
        $search = request('search', '');

        // filter status
        if ($status) {

            if ($status === 'disetujui') {

                $query->whereIn('status', [
                    'disetujui pembina',
                    'disetujui admin'
                ]);

            } elseif ($status === 'ditolak') {

                $query->whereIn('status', [
                    'ditolak pembina',
                    'ditolak admin'
                ]);

            } elseif (in_array($status, self::ALLOWED_STATUSES, true)) {

                $query->where('status', $status);
            }
        }

        // search
        if ($search) {

            $query->where(function ($q) use ($search) {

                $q->where('nama_kegiatan', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('tempat', 'like', "%{$search}%");
            });
        }

        $kegiatan = $query->get([
            'id',
            'organisasi_id',
            'nama_kegiatan',
            'deskripsi',
            'tanggal_mulai',
            'tempat',
            'proposal',
            'status',
        ]);

        $organisasiList = Organisasi::orderBy('nama_organisasi')
            ->get([
                'id',
                'nama_organisasi'
            ]);

        return view('admin.kegiatan', compact(
            'kegiatan',
            'organisasiList',
            'search',
            'status'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'organisasi_id' => [
                'required',
                'integer',
                Rule::exists('organisasi', 'id')
            ],

            'nama_kegiatan' => [
                'required',
                'string',
                'max:150'
            ],

            'deskripsi' => [
                'nullable',
                'string'
            ],

            'tanggal_mulai' => [
                'nullable',
                'date'
            ],

            'tempat' => [
                'nullable',
                'string',
                'max:150'
            ],

            'proposal' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120'
            ],

            'status' => [
                'required',
                Rule::in(self::ALLOWED_STATUSES)
            ],
        ]);

        // upload proposal
        if ($request->hasFile('proposal')) {

            $organisasi = Organisasi::find($validated['organisasi_id']);

            $folder = 'proposal/' . Str::slug($organisasi->nama_organisasi);

            $file = $request->file('proposal');

            $filename = time() . '_' . $file->getClientOriginalName();

            $validated['proposal'] = $file->storeAs(
                $folder,
                $filename,
                'public'
            );
        }

        Kegiatan::create($validated);

        return redirect()
            ->route('admin.kegiatan')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function update(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        $validated = $request->validate([
            'organisasi_id' => [
                'required',
                'integer',
                Rule::exists('organisasi', 'id')
            ],

            'nama_kegiatan' => [
                'required',
                'string',
                'max:150'
            ],

            'deskripsi' => [
                'nullable',
                'string'
            ],

            'tanggal_mulai' => [
                'nullable',
                'date'
            ],

            'tempat' => [
                'nullable',
                'string',
                'max:150'
            ],

            'proposal' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120'
            ],

            'status' => [
                'required',
                Rule::in(self::ALLOWED_STATUSES)
            ],
        ]);

        // upload proposal baru
        if ($request->hasFile('proposal')) {

            // hapus file lama
            if ($kegiatan->proposal && !str_starts_with($kegiatan->proposal, 'http')) {

                Storage::disk('public')->delete($kegiatan->proposal);
            }

            $organisasi = Organisasi::find($validated['organisasi_id']);

            $folder = 'proposal/' . Str::slug($organisasi->nama_organisasi);

            $file = $request->file('proposal');

            $filename = time() . '_' . $file->getClientOriginalName();

            $validated['proposal'] = $file->storeAs(
                $folder,
                $filename,
                'public'
            );

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
        // hapus proposal
        if ($kegiatan->proposal && !str_starts_with($kegiatan->proposal, 'http')) {

            Storage::disk('public')->delete($kegiatan->proposal);
        }

        $kegiatan->delete();

        return redirect()
            ->route('admin.kegiatan')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}