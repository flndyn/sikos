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
            'ketua:id,name',
        ])
            ->where('pembina_id', auth()->id())
            ->latest('id')
            ->get(['id', 'nama_organisasi', 'deskripsi', 'ketua_id']);

        return view('pembina.organisasi', compact('organisasi'));
    }
}