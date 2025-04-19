<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\InitialProcessController;
use App\Http\Controllers\PerformanceController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

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

// // Group route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export', [DashboardController::class, 'exportPdf'])->name('export.report');

    Route::prefix('/profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/edit/{id}', [ProfileController::class, 'update'])->name('edit');
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/', [ProfileController::class, 'index'])->name('index');
    });

    // Route khusus admin
    Route::middleware(['admin'])->group(function () {

        Route::name('naive-bayes.')->group(function () {
            Route::prefix('/dataset')->name('dataset.')->group(function () {
                Route::get('/', [DatasetController::class, 'index'])->name('index');
                Route::post('/import', [DatasetController::class, 'import'])->name('import');
                Route::delete('/delete-all', [DatasetController::class, 'deleteAll'])->name('deleteAll');
                Route::post('/clear-session', [DatasetController::class, 'clearSession'])->name('clear-session');
                Route::get('/clear-session', [DatasetController::class, 'clearSession'])->name('clear-session');
            });

            Route::get('/initial-process', [InitialProcessController::class, 'index'])->name('initial-process');

            Route::prefix('/performance')->name('performance.')->group(function () {
                Route::get('/', [PerformanceController::class, 'index'])->name('index');
                Route::post('/calculate', [PerformanceController::class, 'calculate'])->name('calculate');
            });

            Route::prefix('/prediction')->name('prediction.')->group(function () {
                Route::match(['get', 'post'], '/', [PredictionController::class, 'index'])->name('index');
            });
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
    });

    // Route khusus karyawan
    // Route::middleware(['karyawan'])->group(function () {
    //     Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('keryawan.dashboard');
    // });
});
