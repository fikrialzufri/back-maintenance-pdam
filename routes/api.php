<?php

use App\Http\Controllers\Api\AduanController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\JenisController;
use App\Http\Controllers\Api\JenisAduanController;
use App\Http\Controllers\Api\PenunjukanPekerjaanController;
use App\Http\Controllers\Api\RekananController;
use App\Http\Controllers\Api\PelaksanaanPekerjaanController;
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

    // Rekanan
    Route::get('rekanan', RekananController::class . '@index')->name('rekanan.api.index');

    // Aduan
    Route::get('aduan', AduanController::class . '@index')->name('aduan.api.index');

    // list penunjukan-pekerjaan
    Route::get('list-pekerjaan', PenunjukanPekerjaanController::class . '@index')->name('penunjukan.api.index');

    // simpan penunjukan-pekerjaan
    Route::post('penunjukan-pekerjaan', PenunjukanPekerjaanController::class . '@store')->name('penunjukan.api.store');

    // pelaksanaan-pekerjaan
    Route::get('pelaksanaan-pekerjaan', PelaksanaanPekerjaanController::class . '@index')->name('pelaksanaan.api.index');

    // proses pelaksanaan-pekerjaan
    Route::post('proses-pekerjaan', PelaksanaanPekerjaanController::class . '@store')->name('pelaksanaan.api.store');
    // update pelaksanaan-pekerjaan
    Route::post('update-pekerjaan', PelaksanaanPekerjaanController::class . '@proses')->name('pelaksanaan.api.proses');

    // proses akhir pelaksanaan-pekerjaan
    Route::post('selesai-pekerjaan', PelaksanaanPekerjaanController::class . '@prosesAkhir')->name('pelaksanaan.api.selesai');

    // bahan dari pelaksanaan-pekerjaan
    Route::post('bahan-pekerjaan', PelaksanaanPekerjaanController::class . '@selesai')->name('pelaksanaan.api.selesai');

    Route::get('refresh', AuthController::class . '@refresh')->name('auth.refresh');
});
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
