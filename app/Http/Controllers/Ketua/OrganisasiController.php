<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class OrganisasiController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        $organisasi = $user->organisasiSebagaiKetua()
            ->with(['pembina:id,name,email,profile_photo_path', 'ketua:id,name,profile_photo_path'])
            ->first();

        return view('ketua.organisasi', compact('organisasi'));
    }
}
