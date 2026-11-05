<?php
use App\Http\Controllers\PetaController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

use Illuminate\Support\Facades\Route;

Route::get('/', [PetaController::class, 'index'])->name('peta.index');
Route::get('/data/sekolah', [PetaController::class, 'geojson'])
    ->name('peta.data');
    Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('sekolah', SekolahController::class)->except('show');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');
    Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');
});
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');
    Route::get('/dashboard', DashboardController::class)
    ->name('dashboard');
});

Route::middleware(['auth', 'role:dinas'])
    ->prefix('admin')->name('admin.')->group(function () {
    Route::resource('sekolah', SekolahController::class)->except('show');
 });

Route::get('/welcome', function () {
    return view('welcome');
});