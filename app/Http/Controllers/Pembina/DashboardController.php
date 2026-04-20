<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('pembina.dashboard');
    }
}