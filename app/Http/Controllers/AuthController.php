<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        if (! Auth::attempt([$loginField => $validated['login'], 'password' => $validated['password']])) {
            return back()
                ->withErrors(['login' => 'Login atau password tidak valid.'])
                ->onlyInput('login');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard.redirect'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function redirectDashboard(): RedirectResponse
    {
        $role = Auth::user()?->role;

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'ketua' => redirect()->route('ketua.dashboard'),
            'pembina' => redirect()->route('pembina.dashboard'),
            default => abort(403, 'Role tidak dikenali.'),
        };
    }
}