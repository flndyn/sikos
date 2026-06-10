<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrganisasiController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = $request->query('search', '');

        $query = Organisasi::with([
            'pembinaUsers:id,name,profile_photo_path',
            'ketuaUsers:id,name,profile_photo_path',
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_organisasi', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $organisasi = $query->latest('id')->get(['id', 'nama_organisasi', 'deskripsi']);

        $pembinaUsers = User::where('role', 'pembina')
            ->orderBy('name')
            ->get(['id', 'name']);

        $ketuaUsers = User::where('role', 'ketua')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.organisasi', compact('organisasi', 'pembinaUsers', 'ketuaUsers', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_organisasi' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'pembina_ids' => ['nullable', 'array'],
            'pembina_ids.*' => ['integer', 'exists:users,id'],
            'ketua_ids' => ['nullable', 'array'],
            'ketua_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $pembinaIds = $validated['pembina_ids'] ?? [];
        $ketuaIds = $validated['ketua_ids'] ?? [];
        unset($validated['pembina_ids'], $validated['ketua_ids']);

        $organisasi = Organisasi::create($validated);

        $pembinaPivot = [];
        foreach ($pembinaIds as $id) {
            $pembinaPivot[$id] = ['role' => 'pembina'];
        }
        $organisasi->pembinaUsers()->sync($pembinaPivot);

        $ketuaPivot = [];
        foreach ($ketuaIds as $id) {
            $ketuaPivot[$id] = ['role' => 'ketua'];
        }
        $organisasi->ketuaUsers()->sync($ketuaPivot);

        return redirect()
            ->route('admin.organisasi')
            ->with('success', 'Organisasi berhasil ditambahkan.');
    }

    public function update(Request $request, Organisasi $organisasi): RedirectResponse
    {
        $validated = $request->validate([
            'nama_organisasi' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'pembina_ids' => ['nullable', 'array'],
            'pembina_ids.*' => ['integer', 'exists:users,id'],
            'ketua_ids' => ['nullable', 'array'],
            'ketua_ids.*' => ['integer', 'exists:users,id'],
        ]);

        $pembinaIds = $validated['pembina_ids'] ?? [];
        $ketuaIds = $validated['ketua_ids'] ?? [];
        unset($validated['pembina_ids'], $validated['ketua_ids']);

        $organisasi->update($validated);

        $pembinaPivot = [];
        foreach ($pembinaIds as $id) {
            $pembinaPivot[$id] = ['role' => 'pembina'];
        }
        $organisasi->pembinaUsers()->sync($pembinaPivot);

        $ketuaPivot = [];
        foreach ($ketuaIds as $id) {
            $ketuaPivot[$id] = ['role' => 'ketua'];
        }
        $organisasi->ketuaUsers()->sync($ketuaPivot);

        return redirect()
            ->route('admin.organisasi')
            ->with('success', 'Data organisasi berhasil diperbarui.');
    }

    public function destroy(Organisasi $organisasi): RedirectResponse
    {
        $organisasi->delete();

        return redirect()
            ->route('admin.organisasi')
            ->with('success', 'Organisasi berhasil dihapus.');
    }
}
