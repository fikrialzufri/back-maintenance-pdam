<?php

use App\Http\Controllers\Api\AduanController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\JenisController;
use App\Http\Controllers\Api\JenisAduanController;
use App\Http\Controllers\Api\PelaksanaanPekerjaanController;
use App\Http\Controllers\Api\PenunjukanPekerjaanController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;


Route::get(
    '/csrf-cookie',
    CsrfCookieController::class . '@show'
)->middleware('web')->name('auth.cookies');


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('me', AuthController::class . '@me')->name('auth.me');
    Route::get('refresh', AuthController::class . '@refresh')->name('auth.refresh');
    Route::get('refresh', AuthController::class . '@refresh')->name('auth.refresh');

    // Item
    Route::get('item', ItemController::class . '@index')->name('item.api.index');
    Route::get('item/{slug}', ItemController::class . '@show')->name('item.api.show');

    // Jenis
    Route::get('jenis', JenisController::class . '@index')->name('jenis.api.index');

    // Jenis Aduan
    Route::get('jenis-aduan', JenisAduanController::class . '@index')->name('jenis-aduan.api.index');

    // Aduan
    Route::get('aduan', AduanController::class . '@index')->name('aduan.api.index');

    Route::get('penunjukan-pekerjaan', PenunjukanPekerjaanController::class . '@index')->name('penunjukan-pekerjaan.api.index');

    Route::post('penunjukan-pekerjaan', PenunjukanPekerjaanController::class . '@store')->name('penunjukan-pekerjaan.api.store');

    // aduan
    Route::get('pelaksanaan-pekerjaan', PelaksanaanPekerjaanController::class . '@index')->name('pelaksanaan-pekerjaan.api.index');

    Route::post(
        'pelaksanaan-pekerjaan',
        PelaksanaanPekerjaanController::class . '@store'
    )->name('pelaksanaan-pekerjaan.api.store');

    Route::put('pelaksanaan-pekerjaan-proses', PelaksanaanPekerjaanController::class . '@proses')->name('pelaksanaan-pekerjaan.api.proses');

    Route::put('pelaksanaan-pekerjaan-proses-akhir', PelaksanaanPekerjaanController::class . '@prosesAkhir')->name('pelaksanaan-pekerjaan.api.proses.akhir');

    Route::get('refresh', AuthController::class . '@refresh')->name('auth.refresh');
});
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
