<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DokumentasiController;
use App\Http\Controllers\Admin\KegiatanController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\OrganisasiController as AdminOrganisasiController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ValidasiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Ketua\DashboardController as KetuaDashboardController;
use App\Http\Controllers\Ketua\DokumentasiController as KetuaDokumentasiController;
use App\Http\Controllers\Ketua\JadwalController as KetuaJadwalController;
use App\Http\Controllers\Ketua\LaporanController as KetuaLaporanController;
use App\Http\Controllers\Ketua\OrganisasiController as KetuaOrganisasiController;
use App\Http\Controllers\Ketua\KegiatanController as KetuaKegiatanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Pembina\DashboardController as PembinaDashboardController;
use App\Http\Controllers\Pembina\DokumentasiController as PembinaDokumentasiController;
use App\Http\Controllers\Pembina\JadwalController as PembinaJadwalController;
use App\Http\Controllers\Pembina\KegiatanController as PembinaKegiatanController;
use App\Http\Controllers\Pembina\LaporanController as PembinaLaporanController;
use App\Http\Controllers\Pembina\OrganisasiController as PembinaOrganisasiController;
use App\Http\Controllers\Pembina\ValidasiController as PembinaValidasiController;
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
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::post('/notifications/{notificationId}/read', [NotificationController::class, 'readOne'])->name('notifications.read-one');
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
    Route::get('/admin/laporan', LaporanController::class)->name('admin.laporan');
    Route::get('/admin/laporan/export-pdf', [LaporanController::class, 'exportPdf'])->name('admin.laporan.export-pdf');
    Route::get('/admin/laporan/{laporan}/download', [LaporanController::class, 'download'])->name('admin.laporan.download');
});

Route::middleware(['auth', 'role:ketua'])->group(function (): void {
    Route::get('/ketua/dashboard', KetuaDashboardController::class)->name('ketua.dashboard');
    Route::get('/ketua/organisasi', KetuaOrganisasiController::class)->name('ketua.organisasi');
    Route::get('/ketua/kegiatan', KetuaKegiatanController::class)->name('ketua.kegiatan');
    Route::post('/ketua/kegiatan', [KetuaKegiatanController::class, 'store'])->name('ketua.kegiatan.store');
    Route::put('/ketua/kegiatan/{kegiatan}', [KetuaKegiatanController::class, 'update'])->name('ketua.kegiatan.update');
    Route::delete('/ketua/kegiatan/{kegiatan}', [KetuaKegiatanController::class, 'destroy'])->name('ketua.kegiatan.destroy');
    Route::get('/ketua/jadwal', KetuaJadwalController::class)->name('ketua.jadwal');
    Route::get('/ketua/dokumentasi', KetuaDokumentasiController::class)->name('ketua.dokumentasi');
    Route::post('/ketua/dokumentasi', [KetuaDokumentasiController::class, 'store'])->name('ketua.dokumentasi.store');
    Route::put('/ketua/dokumentasi/{dokumentasi}', [KetuaDokumentasiController::class, 'update'])->name('ketua.dokumentasi.update');
    Route::delete('/ketua/dokumentasi/{dokumentasi}', [KetuaDokumentasiController::class, 'destroy'])->name('ketua.dokumentasi.destroy');
    Route::get('/ketua/laporan', KetuaLaporanController::class)->name('ketua.laporan');
    Route::post('/ketua/laporan', [KetuaLaporanController::class, 'store'])->name('ketua.laporan.store');
    Route::put('/ketua/laporan/{laporan}', [KetuaLaporanController::class, 'update'])->name('ketua.laporan.update');
    Route::delete('/ketua/laporan/{laporan}', [KetuaLaporanController::class, 'destroy'])->name('ketua.laporan.destroy');
    Route::get('/ketua/laporan/{laporan}/download', [KetuaLaporanController::class, 'download'])->name('ketua.laporan.download');
});

Route::middleware(['auth', 'role:pembina'])->group(function (): void {
    Route::get('/pembina/dashboard', PembinaDashboardController::class)->name('pembina.dashboard');
    Route::get('/pembina/organisasi', PembinaOrganisasiController::class)->name('pembina.organisasi');
    Route::get('/pembina/kegiatan', PembinaKegiatanController::class)->name('pembina.kegiatan');
    Route::get('/pembina/validasi', PembinaValidasiController::class)->name('pembina.validasi');
    Route::post('/pembina/validasi/{kegiatan}/approve', [PembinaValidasiController::class, 'approve'])->name('pembina.validasi.approve');
    Route::post('/pembina/validasi/{kegiatan}/reject', [PembinaValidasiController::class, 'reject'])->name('pembina.validasi.reject');
    Route::get('/pembina/jadwal', PembinaJadwalController::class)->name('pembina.jadwal');
    Route::get('/pembina/dokumentasi', PembinaDokumentasiController::class)->name('pembina.dokumentasi');
    Route::get('/pembina/laporan', PembinaLaporanController::class)->name('pembina.laporan');
    Route::get('/pembina/laporan/{laporan}/download', [PembinaLaporanController::class, 'download'])->name('pembina.laporan.download');
});
