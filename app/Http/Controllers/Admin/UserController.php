<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __invoke(): View
    {
        $users = User::with([
            'organisasiSebagaiKetua:id,nama_organisasi,ketua_id',
            'organisasiSebagaiPembina:id,nama_organisasi,pembina_id',
        ])
            ->latest('id')
            ->get(['id', 'name', 'email', 'role', 'profile_photo_path']);

        return view('admin.users', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'role' => ['required', Rule::in(['admin', 'ketua', 'pembina'])],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create($validated);

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

        $user->update($validated);

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
