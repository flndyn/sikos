<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/admin/users', function () {
    return view('admin.users');
});

Route::get('/admin/organisasi', function () {
    return view('admin.organisasi');
});

Route::get('/admin/kegiatan', function () {
    return view('admin.kegiatan');
});

Route::get('/admin/validasi', function () {
    return view('admin.validasi');
});

Route::get('/admin/dokumentasi', function () {
    return view('admin.dokumentasi');
});

Route::get('/admin/laporan', function () {
    return view('admin.laporan');
});