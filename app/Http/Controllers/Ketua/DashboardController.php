<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('ketua.dashboard');
    }
}