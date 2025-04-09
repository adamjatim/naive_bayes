<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\InitialProcessController;
use App\Http\Controllers\PerformanceController;

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

    Route::prefix('/profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/edit/{id}', [ProfileController::class, 'update'])->name('edit');
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/', [ProfileController::class, 'index'])->name('index');
    });

    // Route khusus admin
    Route::middleware(['admin'])->group(function () {
        // Route::get('/admin/dashboard', function () {
        //     return view('admin.dashboard');
        // })->name('admin.dashboard');

        Route::name('naive-bayes.')->group(function () {
            Route::prefix('/dataset')->name('dataset.')->group(function () {
                Route::get('/', [DatasetController::class, 'index'])->name('index');
                Route::post('/import', [DatasetController::class, 'import'])->name('import');
            });

            Route::get('/initial-process', [InitialProcessController::class, 'index'])->name('initial-process');

            Route::prefix('/performance')->name('performance.')->group(function () {
                Route::get('/', [PerformanceController::class, 'index'])->name('index');
                Route::post('/calculate', [PerformanceController::class, 'calculate'])->name('calculate');
            });

            Route::get('/prediction', function () {
                return view('pages.naive-bayes.prediction');
            })->name('prediction');
        });

        Route::prefix('/karyawan')->name('karyawan.')->group(function () {
            Route::get('/', [KaryawanController::class, 'index'])->name('index');
            Route::post('/store', [KaryawanController::class, 'store'])->name('store');
            Route::put('/update/{id}', [KaryawanController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [KaryawanController::class, 'destroy'])->name('delete');
            Route::get('/create', function () {
                return view('pages.karyawan.create');
            })->name('create');
        });

        // Route::prefix('/karyawan')->name('karyawan.')->group(function() {
        //     Route::get('/', function () {
        //         return view('pages.karyawan.index');
        //     })->name('index');
        //
        //     Route::get('/edit/{id}', function () {
        //         return view('pages.karyawan.edit');
        //     })->name('edit');
        //     Route::get('/delete', function () {
        //         return "Ini karyawan delete";
        //     })->name('delete');
        // });
    });

    // Route khusus karyawan
    // Route::middleware(['karyawan'])->group(function () {
    //     Route::get('/karyawan/dashboard', function () {
    //         return view('karyawan.dashboard');
    //     })->name('karyawan.dashboard');
    // });
});
