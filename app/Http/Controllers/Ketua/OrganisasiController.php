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
            ->with('pembina', 'ketua')
            ->first();

        return view('ketua.organisasi', compact('organisasi'));
    }
}
