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
            'organisasiSebagaiKetua:id,nama_organisasi,ketua_id',
            'organisasiSebagaiPembina:id,nama_organisasi,pembina_id',
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
            'organisasi_id' => ['nullable', 'integer', Rule::exists('organisasi', 'id')],
            'organisasi_ids' => ['nullable', 'array'],
            'organisasi_ids.*' => ['integer', Rule::exists('organisasi', 'id')],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $organisasiId = $validated['organisasi_id'] ?? null;
        $organisasiIds = collect($validated['organisasi_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->values();
        unset($validated['organisasi_id'], $validated['organisasi_ids']);

        $user = User::create($validated);

        if ($validated['role'] === 'ketua' && !empty($organisasiId)) {
            Organisasi::where('id', (int) $organisasiId)->update(['ketua_id' => $user->id]);
        }

        if ($validated['role'] === 'pembina' && $organisasiIds->isNotEmpty()) {
            Organisasi::whereIn('id', $organisasiIds->all())->update(['pembina_id' => $user->id]);
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
            'organisasi_id' => ['nullable', 'integer', Rule::exists('organisasi', 'id')],
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

        // Remove non-user columns before updating users table.
        $organisasiId = $validated['organisasi_id'] ?? null;
        $organisasiIds = collect($validated['organisasi_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->values();
        unset($validated['organisasi_id'], $validated['organisasi_ids']);

        $user->update($validated);

        if ($validated['role'] === 'ketua') {
            Organisasi::where('ketua_id', $user->id)->update(['ketua_id' => null]);

            if (!empty($organisasiId)) {
                Organisasi::where('id', (int) $organisasiId)->update(['ketua_id' => $user->id]);
            }

            // Ketua should not remain as pembina assignment.
            Organisasi::where('pembina_id', $user->id)->update(['pembina_id' => null]);
        } elseif ($validated['role'] === 'pembina') {
            Organisasi::where('ketua_id', $user->id)->update(['ketua_id' => null]);

            Organisasi::where('pembina_id', $user->id)
                ->whereNotIn('id', $organisasiIds->all())
                ->update(['pembina_id' => null]);

            if ($organisasiIds->isNotEmpty()) {
                Organisasi::whereIn('id', $organisasiIds->all())->update(['pembina_id' => $user->id]);
            }
        } else {
            Organisasi::where('ketua_id', $user->id)->update(['ketua_id' => null]);
            Organisasi::where('pembina_id', $user->id)->update(['pembina_id' => null]);
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
