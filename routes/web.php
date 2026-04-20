<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DokumentasiController;
use App\Http\Controllers\Admin\KegiatanController;
use App\Http\Controllers\Admin\OrganisasiController as AdminOrganisasiController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ValidasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Ketua\DashboardController as KetuaDashboardController;
use App\Http\Controllers\Pembina\DashboardController as PembinaDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [AuthController::class, 'redirectDashboard'])->name('dashboard.redirect');
});

Route::middleware(['auth', 'role:admin'])->group(function (): void {
    Route::get('/admin/dashboard', AdminDashboardController::class)->name('admin.dashboard');
    Route::get('/admin/users', AdminUserController::class)->name('admin.users');
    Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::put('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/organisasi', AdminOrganisasiController::class)->name('admin.organisasi');
    Route::post('/admin/organisasi', [AdminOrganisasiController::class, 'store'])->name('admin.organisasi.store');
    Route::put('/admin/organisasi/{organisasi}', [AdminOrganisasiController::class, 'update'])->name('admin.organisasi.update');
    Route::delete('/admin/organisasi/{organisasi}', [AdminOrganisasiController::class, 'destroy'])->name('admin.organisasi.destroy');
    Route::get('/admin/kegiatan', KegiatanController::class)->name('admin.kegiatan');
    Route::post('/admin/kegiatan', [KegiatanController::class, 'store'])->name('admin.kegiatan.store');
    Route::put('/admin/kegiatan/{kegiatan}', [KegiatanController::class, 'update'])->name('admin.kegiatan.update');
    Route::delete('/admin/kegiatan/{kegiatan}', [KegiatanController::class, 'destroy'])->name('admin.kegiatan.destroy');
    Route::get('/admin/validasi', ValidasiController::class)->name('admin.validasi');
    Route::post('/admin/validasi/{kegiatan}/approve', [ValidasiController::class, 'approve'])->name('admin.validasi.approve');
    Route::post('/admin/validasi/{kegiatan}/reject', [ValidasiController::class, 'reject'])->name('admin.validasi.reject');
    Route::get('/admin/dokumentasi', DokumentasiController::class)->name('admin.dokumentasi');
    Route::post('/admin/dokumentasi', [DokumentasiController::class, 'store'])->name('admin.dokumentasi.store');
    Route::put('/admin/dokumentasi/{dokumentasi}', [DokumentasiController::class, 'update'])->name('admin.dokumentasi.update');
    Route::delete('/admin/dokumentasi/{dokumentasi}', [DokumentasiController::class, 'destroy'])->name('admin.dokumentasi.destroy');
    Route::get('/admin/laporan', fn () => view('admin.laporan'))->name('admin.laporan');
});

Route::middleware(['auth', 'role:ketua'])->group(function (): void {
    Route::get('/ketua/dashboard', KetuaDashboardController::class)->name('ketua.dashboard');
});

Route::middleware(['auth', 'role:pembina'])->group(function (): void {
    Route::get('/pembina/dashboard', PembinaDashboardController::class)->name('pembina.dashboard');
});
