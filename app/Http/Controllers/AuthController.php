<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    public function showRegisterForm(): View
    {
        return view('register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:users,name',
                'different:email',
                static function (string $attribute, mixed $value, \Closure $fail): void {
                    if (User::where('email', $value)->exists()) {
                        $fail('Username tidak boleh sama dengan email yang sudah terdaftar.');
                    }
                },
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                'unique:users,email',
                'different:name',
                static function (string $attribute, mixed $value, \Closure $fail): void {
                    if (User::where('name', $value)->exists()) {
                        $fail('Email tidak boleh sama dengan username yang sudah terdaftar.');
                    }
                },
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'required' => ':attribute wajib diisi.',
            'email' => ':attribute harus berupa alamat email yang valid.',
            'max' => ':attribute maksimal :max karakter.',
            'unique' => ':attribute sudah digunakan.',
            'different' => ':attribute tidak boleh sama dengan :other.',
            'min' => ':attribute minimal :min karakter.',
            'confirmed' => 'Konfirmasi password tidak cocok.',
        ], [
            'name' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'ketua',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('ketua.dashboard');
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