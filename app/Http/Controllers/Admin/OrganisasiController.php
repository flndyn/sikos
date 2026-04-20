<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrganisasiController extends Controller
{
    public function __invoke(): View
    {
        $organisasi = Organisasi::with([
            'pembina:id,name',
            'ketua:id,name',
        ])
            ->latest('id')
            ->get(['id', 'nama_organisasi', 'deskripsi', 'pembina_id', 'ketua_id']);

        $pembinaUsers = User::where('role', 'pembina')
            ->orderBy('name')
            ->get(['id', 'name']);

        $ketuaUsers = User::where('role', 'ketua')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('admin.organisasi', compact('organisasi', 'pembinaUsers', 'ketuaUsers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_organisasi' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'pembina_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'pembina')),
            ],
            'ketua_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'ketua')),
            ],
        ]);

        Organisasi::create($validated);

        return redirect()
            ->route('admin.organisasi')
            ->with('success', 'Organisasi berhasil ditambahkan.');
    }

    public function update(Request $request, Organisasi $organisasi): RedirectResponse
    {
        $validated = $request->validate([
            'nama_organisasi' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'pembina_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'pembina')),
            ],
            'ketua_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'ketua')),
            ],
        ]);

        $organisasi->update($validated);

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