<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Halaman login dan proses login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.perform');

// Proses logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Group route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });

    Route::middleware(['karyawan'])->group(function () {
        Route::get('/karyawan/dashboard', function () {
            return view('karyawan.dashboard');
        })->name('karyawan.dashboard');
    });
    Route::get('/', function () {
        return view('layout.app');
    });
});

