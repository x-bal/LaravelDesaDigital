<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Desa\AduanController;
use App\Http\Controllers\Desa\WargaController;
use App\Http\Controllers\Desa\AntrianController;
use App\Http\Controllers\Desa\GalleryController;
use App\Http\Controllers\Desa\PermohonanSuratController;
use App\Http\Controllers\Desa\InformasiController;
use App\Http\Controllers\Desa\LoketController;
use App\Http\Controllers\Desa\PenggunaController;
use App\Http\Controllers\Desa\ProdukController;
use App\Http\Controllers\Warga\MasyarakatController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);

Route::middleware('guest')->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/antrian', [App\Http\Controllers\HomeController::class, 'antrian'])->name('antrian');
    Route::post('/antrian', [App\Http\Controllers\HomeController::class, 'storeAntrian'])->name('antrian.store');
    Route::get('/aduan', [App\Http\Controllers\HomeController::class, 'aduan'])->name('aduan');
    Route::post('/aduan', [App\Http\Controllers\HomeController::class, 'storeAduan'])->name('aduan.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('utama')->name('utama.')->group(function () {
    });
    Route::prefix('kabupaten')->name('kabupaten.')->group(function () {
    });
    Route::prefix('desa')->name('desa.')->group(function () {
        Route::resource('aduan', AduanController::class);
        Route::resource('pengguna', PenggunaController::class);
        Route::resource('gallery', GalleryController::class);
        Route::resource('warga', WargaController::class);
        Route::resource('informasi', InformasiController::class);
        Route::resource('produk', ProdukController::class);
        Route::resource('gallery', GalleryController::class);
        Route::resource('warga', WargaController::class);
        Route::resource('loket', LoketController::class);
        Route::get('loket/{loket:id}', [LoketController::class, 'reset'])->name('loket.reset');
        Route::resource('antrian', AntrianController::class);
        Route::get('antrian/{antrian:id}/status', [AntrianController::class, 'status'])->name('antrian.status');
        Route::resource('permohonan', PermohonanSuratController::class);
    });
    Route::prefix('warga')->middleware('verified')->name('warga.')->group(function () {
        Route::resource('masyarakat', MasyarakatController::class);
    });
});
