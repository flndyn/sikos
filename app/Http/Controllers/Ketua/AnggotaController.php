<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\AnggotaOrganisasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnggotaController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiKetua()->first();
        $organisasiId = $organisasi?->id;

        $search = trim((string) $request->query('search', ''));

        $query = AnggotaOrganisasi::where('organisasi_id', $organisasiId);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        $anggota = $query->orderBy('nama')->get();

        return view('ketua.anggota', compact('anggota', 'search', 'organisasi'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiKetua()->first();

        if (! $organisasi) {
            return redirect()->route('ketua.anggota')
                ->with('error', 'Anda belum menjadi ketua di organisasi manapun.');
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'kelas' => ['required', 'string', 'max:50'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ]);

        $validated['organisasi_id'] = $organisasi->id;

        AnggotaOrganisasi::create($validated);

        return redirect()->route('ketua.anggota')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function update(Request $request, AnggotaOrganisasi $anggota): RedirectResponse
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiKetua()->first();

        if (! $organisasi || $anggota->organisasi_id !== $organisasi->id) {
            return redirect()->route('ketua.anggota')
                ->with('error', 'Anda tidak punya akses untuk mengubah data anggota ini.');
        }

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'kelas' => ['required', 'string', 'max:50'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ]);

        $anggota->update($validated);

        return redirect()->route('ketua.anggota')
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(AnggotaOrganisasi $anggota): RedirectResponse
    {
        $user = auth()->user();
        $organisasi = $user->organisasiSebagaiKetua()->first();

        if (! $organisasi || $anggota->organisasi_id !== $organisasi->id) {
            return redirect()->route('ketua.anggota')
                ->with('error', 'Anda tidak punya akses untuk menghapus anggota ini.');
        }

        $anggota->delete();

        return redirect()->route('ketua.anggota')
            ->with('success', 'Anggota berhasil dihapus.');
    }
}
