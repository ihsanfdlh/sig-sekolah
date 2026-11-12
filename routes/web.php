<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\ProfilSekolahController;
use App\Http\Controllers\ProfilPublikController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Publik
Route::get('/', [PetaController::class, 'index'])
    ->name('peta.index');

Route::controller(PetaController::class)->group(function () {
    Route::get('/', 'index')->name('peta.index');
    Route::get('/data/sekolah', 'geojson')->name('peta.data');
    Route::get('/pencarian', 'cari')->name('peta.cari');
    Route::get('/klaster', 'klaster')->name('peta.klaster');
});


Route::get('/data/sekolah', [PetaController::class, 'geojson'])
    ->name('peta.data');

// Halaman profil sekolah untuk pengunjung umum
Route::get('/sekolah/{sekolah}/profil-publik', [
    ProfilPublikController::class, 'show'
])->name('sekolah.publik');

// Tamu
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1');
});

// Pengguna yang sudah login
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/dashboard', DashboardController::class)
        ->name('dashboard');

    // Dinas: daftar, tambah, dan hapus sekolah
    Route::middleware('role:'.UserRole::DINAS->value)
        ->prefix('dinas')
        ->name('dinas.')
        ->group(function () {
            Route::resource('sekolah', SekolahController::class)
                ->only(['index', 'create', 'store', 'destroy']);
        });

    // Dinas dan Sekolah: ubah data
    Route::middleware(
        'role:'.UserRole::DINAS->value.','.UserRole::SEKOLAH->value
    )->group(function () {

        Route::get('sekolah/{sekolah}/edit', [
            SekolahController::class, 'edit',
        ])->name('sekolah.edit');

        Route::put('sekolah/{sekolah}', [
            SekolahController::class, 'update',
        ])->name('sekolah.update');

        Route::get('sekolah/{sekolah}/profil', [
            ProfilSekolahController::class, 'edit',
        ])->name('sekolah.profil.edit');

        Route::put('sekolah/{sekolah}/profil', [
            ProfilSekolahController::class, 'update',
        ])->name('sekolah.profil.update');
    });

    // Admin: pengguna dan log aktivitas
    Route::middleware('role:'.UserRole::ADMIN->value)
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::resource('users', UserController::class)
                ->except('show');

            Route::patch('users/{user}/toggle-status', [
                UserController::class, 'toggleStatus',
            ])->name('users.toggle-status');

            Route::get('log-aktivitas', [
                LogAktivitasController::class, 'index',
            ])->name('log-aktivitas.index');
        });

    // Ubah password
    Route::get('/ubah-password', [
        PasswordController::class, 'edit',
    ])->name('password.edit');

    Route::put('/ubah-password', [
        PasswordController::class, 'update',
    ])->name('password.update');
});
