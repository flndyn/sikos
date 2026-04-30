<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfilController extends Controller
{
    private const PHOTO_DIRECTORY = 'profile-photos';

    public function __invoke(): View
    {
        $user = auth()->user();

        return view('ketua.profil', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $file = $request->file('profile_photo');
            $filename = 'ketua-' . $user->id . '-' . now()->format('YmdHis') . '.' . $file->getClientOriginalExtension();
            $validated['profile_photo_path'] = $file->storeAs(self::PHOTO_DIRECTORY, $filename, 'public');
        }

        unset($validated['profile_photo']);

        $user->update($validated);

        return redirect()
            ->route('ketua.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}