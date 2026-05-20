<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __invoke(Request $request): View
    {
        $search = $request->query('search', '');

        $query = User::with([
            'organisasiSebagaiKetua:id,nama_organisasi',
            'organisasiSebagaiPembina:id,nama_organisasi',
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%");
            });
        }

        $users = $query->latest('id')->get(['id', 'name', 'email', 'role', 'profile_photo_path']);

        return view('admin.users', compact('users', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'ketua', 'pembina'])],
            'organisasi_ids' => ['nullable', 'array'],
            'organisasi_ids.*' => ['integer', Rule::exists('organisasi', 'id')],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $organisasiIds = $validated['organisasi_ids'] ?? [];
        unset($validated['organisasi_ids']);

        $user = User::create($validated);

        if ($validated['role'] === 'ketua' && !empty($organisasiIds)) {
            foreach ($organisasiIds as $orgId) {
                $user->organisasiSebagaiKetua()->attach($orgId, ['role' => 'ketua']);
            }
        }

        if ($validated['role'] === 'pembina' && !empty($organisasiIds)) {
            foreach ($organisasiIds as $orgId) {
                $user->organisasiSebagaiPembina()->attach($orgId, ['role' => 'pembina']);
            }
        }

        return redirect()
            ->route('admin.users')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'ketua', 'pembina'])],
            'organisasi_ids' => ['nullable', 'array'],
            'organisasi_ids.*' => ['integer', Rule::exists('organisasi', 'id')],
            'password' => ['nullable', 'string', 'min:8'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo_path'] = $path;
        }

        $organisasiIds = $validated['organisasi_ids'] ?? [];
        unset($validated['organisasi_ids']);

        $user->update($validated);

        // Detach all existing org relationships first
        $user->organisasiSebagaiKetua()->detach();
        $user->organisasiSebagaiPembina()->detach();

        // Re-attach based on role
        if ($validated['role'] === 'ketua' && !empty($organisasiIds)) {
            foreach ($organisasiIds as $orgId) {
                $user->organisasiSebagaiKetua()->attach($orgId, ['role' => 'ketua']);
            }
        } elseif ($validated['role'] === 'pembina' && !empty($organisasiIds)) {
            foreach ($organisasiIds as $orgId) {
                $user->organisasiSebagaiPembina()->attach($orgId, ['role' => 'pembina']);
            }
        }

        return redirect()
            ->route('admin.users')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('admin.users')
                ->with('error', 'Akun yang sedang login tidak bisa dihapus.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users')
            ->with('success', 'User berhasil dihapus.');
    }
}
