<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Halaman login dan proses login
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
});

// Proses logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Group route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    Route::prefix('/profile')->name('profile.')->group(function() {
        Route::get('/', function() {
            return view('pages.profile.index');
        })->name('index');
    });

    // Route khusus admin
    Route::middleware(['admin'])->group(function () {
        // Route::get('/admin/dashboard', function () {
        //     return view('admin.dashboard');
        // })->name('admin.dashboard');

        Route::name('naive-bayes.')->group(function () {
            Route::get('/dataset', function () {
                return view('pages.naive-bayes.dataset');
            })->name('dataset');

            Route::get('/initialprocess', function () {
                return view('pages.naive-bayes.initial-process');
            })->name('initial-process');

            Route::get('/performance', function () {
                return view('pages.naive-bayes.performance');
            })->name('performance');

            Route::get('/prediction', function () {
                return view('pages.naive-bayes.prediction');
            })->name('prediction');
        });

        Route::prefix('/karyawan')->name('karyawan.')->group(function() {
            Route::get('/', function () {
                return view('pages.karyawan.index');
            })->name('index');
            Route::get('/create', function () {
                return view('pages.karyawan.create');
            })->name('create');
            Route::get('/edit/{id}', function () {
                return view('pages.karyawan.edit');
            })->name('edit');
            Route::get('/delete', function () {
                return "Ini karyawan delete";
            })->name('delete');
        });
    });

    // Route khusus karyawan
    // Route::middleware(['karyawan'])->group(function () {
    //     Route::get('/karyawan/dashboard', function () {
    //         return view('karyawan.dashboard');
    //     })->name('karyawan.dashboard');
    // });
});
