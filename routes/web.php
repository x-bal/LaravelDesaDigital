<?php

use App\Http\Controllers\Desa\WargaController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::prefix('utama')->name('utama.')->group(function(){

    });
    Route::prefix('kabupaten')->name('kabupaten.')->group(function(){

    });
    Route::prefix('desa')->name('desa.')->group(function () {
        Route::resource('warga',WargaController::class);
    });
    Route::prefix('warga')->name('warga.')->group(function(){

    });
});
