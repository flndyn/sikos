<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Models\Organisasi;
use Illuminate\View\View;

class OrganisasiController extends Controller
{
    public function __invoke(): View
    {
        $organisasi = Organisasi::with([
            'ketuaUsers:id,name,profile_photo_path',
        ])
            ->whereHas('pembinaUsers', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->latest('id')
            ->get(['id', 'nama_organisasi', 'deskripsi']);

        return view('pembina.organisasi', compact('organisasi'));
    }
}
